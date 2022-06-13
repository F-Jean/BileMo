<?php

namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

final class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function __construct(private Security $security)
    {
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ): void {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        string $operationName = null,
        array $context = []
    ): void {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        // on vérifie que la ressource demandé est bien de type User || on met en place une exception si l'user à ROLE_ADMIN || on récupère l'user connecté au niveau du composant security
        if (User::class !== $resourceClass || $this->security->isGranted('ROLE_ADMIN') || null === $user = $this->security->getUser()) {
            return;
        }

        // on récupère le premier alias racine
        $rootAlias = $queryBuilder->getRootAliases()[0];
        // on peut alors utiliser cet alias pour la requête
        $queryBuilder->andWhere(sprintf('%s.client = :current_user', $rootAlias));
        // on passe ensuite le paramètre ici current_user est l'id de l'user
        $queryBuilder->setParameter('current_user', $user->getId());
    }
}
