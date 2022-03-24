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

    #[ORM\Column(type: 'array')]
    private $skills = [];

    #[ORM\Column(type: 'array')]
    private $store;

    #[ORM\OneToOne(targetEntity: Users::class, cascade: ['persist', 'remove'])]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSkills($name = null): array|string
    {
        if($name) {
            return $this->skills[$name];
        } else return $this->skills;
    }

    public function setSkills(array $skills, bool $update = false, string $name= null): self
    {
        $this->skills = $skills;

        if($update){
            $this->skills[$name] += 1;
        }
        return $this;
    }

    public function getStore(string $skill = null, string $sex = null): int|array
    {
        if(isset($skill)) {
            return $this->store[$sex][$skill];
        }
        return $this->store;
    }

    public function setStore(array $store): self
    {
        $this->store = $store;

        return $this;
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
}
