<?php

namespace App\Entity;

use App\Repository\SkillsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkillsRepository::class)]
class Skills
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Users::class, cascade: ['persist', 'remove'], inversedBy: 'skills')]
    private $user;

    #[ORM\Column(type: 'string', length: 255)]
    private $sex;

    #[ORM\Column(type: 'array')]
    private $skills = [];

    #[ORM\Column(type: 'array')]
    private $store;


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

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getSkills(): ?array
    {
        return $this->skills;
    }

    public function setSkills(array $skills): self
    {
        $this->skills = $skills;

        return $this;
    }

    public function getStore(): ?array
    {
        return $this->store;
    }

    public function setStore(array $store): self
    {
        $this->store = $store;

        return $this;
    }
}
