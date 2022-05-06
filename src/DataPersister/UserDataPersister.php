<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;

final class UserDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(private ContextAwareDataPersisterInterface $decorated, private Security $security)
    {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function persist($data, array $context = [])
    {
        $data->setClient($this->security->getUser());
      
        $result = $this->decorated->persist($data, $context);

        return $result;
    }

    public function remove($data, array $context = [])
    {
        return $this->decorated->remove($data, $context);
    }
}