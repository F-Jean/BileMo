<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['read:Users']]
        ],
        'post' => [
            'denormalization_context' => ['groups' => ['post:Users']]
        ],
    ],
    attributes: [
        'pagination_items_per_page' => 10
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['read:User']],
            'security' => 'object.getclient(user)'
        ],
        'put' => [
            'denormalization_context' => ['groups' => ['mod:User']],
            'security' => 'object.getclient(user)'
        ],
        'delete' => [
            'security' => 'object.getclient(user)'
        ]
    ],
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    #[Groups(['read:Users', 'post:Users', 'read:User', 'mod:User'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Groups(['read:Users', 'post:Users', 'read:User', 'mod:User'])]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Groups(['read:Users', 'post:Users', 'read:User', 'mod:User'])]
    private string $lastname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Groups(['read:Users', 'post:Users', 'read:User', 'mod:User'])]
    private string $adress;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Groups(['read:Users', 'post:Users', 'read:User', 'mod:User'])]
    private string $creditCard;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotBlank]
    #[Groups(['read:Users', 'post:Users', 'read:User', 'mod:User'])]
    private \DateTimeImmutable $registeredAt;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    private $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getCreditCard(): ?string
    {
        return $this->creditCard;
    }

    public function setCreditCard(string $creditCard): self
    {
        $this->creditCard = $creditCard;

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeImmutable
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeImmutable $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    public function getclient(): ?Client
    {
        return $this->client;
    }

    public function setclient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
