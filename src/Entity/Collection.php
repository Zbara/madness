<?php

namespace App\Entity;

use App\Repository\CollectionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CollectionRepository::class)]
class Collection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(inversedBy: 'collection',targetEntity: Users::class, cascade: ['persist', 'remove'])]
    private $user;

    #[ORM\Column(type: 'integer')]
    private $created_at;

    #[ORM\Column(type: 'json')]
    private $collection = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?int
    {
        return $this->created_at;
    }

    public function setCreatedAt(int $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getCollection(): ?array
    {
        return $this->collection;
    }

    public function setCollection(array $collection): self
    {
        $this->collection = $collection;

        return $this;
    }
}
