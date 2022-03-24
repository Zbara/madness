<?php

namespace App\Entity;

use App\Repository\PveUsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PveUsersRepository::class)]
class PveUsers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'pveUsers')]
    private $user;

    #[ORM\ManyToOne(targetEntity: Pve::class, inversedBy: 'pveUsers')]
    private $battle;

    #[ORM\Column(type: 'integer')]
    private $created;

    #[ORM\Column(type: 'integer')]
    private $visit;

    #[ORM\Column(type: 'integer')]
    private $health;

    #[ORM\Column(type: 'integer')]
    private $damage;

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

    public function getBattle(): ?Pve
    {
        return $this->battle;
    }

    public function setBattle(?Pve $battle): self
    {
        $this->battle = $battle;

        return $this;
    }

    public function getCreated(): ?int
    {
        return $this->created;
    }

    public function setCreated(int $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getVisit(): ?int
    {
        return $this->visit;
    }

    public function setVisit(int $visit): self
    {
        $this->visit = $visit;

        return $this;
    }

    public function getHealth(): ?int
    {
        return $this->health;
    }

    public function setHealth(int $health): self
    {
        $this->health = $health;

        return $this;
    }

    public function getDamage(): ?int
    {
        return $this->damage;
    }

    public function setDamage(int $damage): self
    {
        $this->damage = $damage;

        return $this;
    }
}
