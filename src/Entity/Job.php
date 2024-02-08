<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobRepository::class)]
class Job
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(inversedBy: 'job', targetEntity: Users::class, cascade: ['persist', 'remove'])]
    private $user;

    #[ORM\Column(type: 'json')]
    private $missions = [];

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

    public function getMissions(): ?array
    {
        return $this->missions;
    }

    public function setMissions(?array $missions): self
    {
        $this->missions = $missions;

        return $this;
    }
}
