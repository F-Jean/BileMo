<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'text')]
    private text $description;

    #[ORM\Column(type: 'string', length: 255)]
    private string $image;

    #[UploadedFile]
    private UploadedFile $imageFile;

    #[ORM\Column(type: 'string', length: 255)]
    private string $slug;

    #[ORM\Column(type: 'float')]
    private float $priceWT;

    #[ORM\Column(type: 'float')]
    private float $priceATI;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $addedAt;

    #[ORM\ManyToMany(targetEntity: Client::class, inversedBy: 'products')]
    private Collection $clients;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile(): ?UploadedFile
    {
        return $this->imageFile;
    }

    public function setImageFile(UploadedFile $imageFile): self
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPriceWT(): ?float
    {
        return $this->priceWT;
    }

    public function setPriceWT(float $priceWT): self
    {
        $this->priceWT = $priceWT;

        return $this;
    }

    public function getPriceATI(): ?float
    {
        return $this->priceATI;
    }

    public function setPriceATI(float $priceATI): self
    {
        $this->priceATI = $priceATI;

        return $this;
    }

    public function getAddedAt(): ?\DateTimeImmutable
    {
        return $this->addedAt;
    }

    public function setAddedAt(\DateTimeImmutable $addedAt): self
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        $this->clients->removeElement($client);

        return $this;
    }
}
