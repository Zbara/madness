<?php

namespace App\Entity;

use App\Repository\EnergyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnergyRepository::class)]
class Energy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'energies')]
    private $user;

    #[ORM\Column(type: 'string', length: 255)]
    private $category;

    #[ORM\Column(type: 'integer')]
    private $current;

    #[ORM\Column(type: 'integer')]
    private $stamp;

    #[ORM\Column(type: 'integer')]
    private $used;

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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCurrent(): ?int
    {
        return $this->current;
    }

    public function setCurrent(int $current): self
    {
        $this->current = $current;

        return $this;
    }

    public function getStamp(): ?int
    {
        return $this->stamp;
    }

    public function setStamp(int $stamp): self
    {
        $this->stamp = $stamp;

        return $this;
    }

    public function getUsed(): ?int
    {
        return $this->used;
    }

    public function setUsed(int $used): self
    {
        $this->used = $used;

        return $this;
    }
}
