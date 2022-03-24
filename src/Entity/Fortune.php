<?php

namespace App\Entity;

use App\Repository\FortuneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FortuneRepository::class)]
class Fortune
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'fortunes')]
    private $user;

    #[ORM\Column(type: 'integer')]
    private $stamp;

    #[ORM\Column(type: 'array')]
    private $cells = [];

    #[ORM\Column(type: 'integer')]
    private $finish = 0;

    #[ORM\OneToMany(mappedBy: 'fortune', targetEntity: Users::class)]
    private $users;

    #[ORM\Column(type: 'integer')]
    private $number = 0;

    public function __construct()
    {
        $this->stamp = time();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id ?? 0;
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

    public function getStamp(): ?int
    {
        return $this->stamp;
    }

    public function setStamp(int $stamp): self
    {
        $this->stamp = $stamp;

        return $this;
    }

    public function getCells(): ?array
    {
        return $this->cells;
    }

    public function setCells(array $cells): self
    {
        $this->cells = $cells;

        return $this;
    }

    public function getFinish(): ?int
    {
        return $this->finish;
    }

    public function setFinish(int $finish): self
    {
        $this->finish = $finish;

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setFortune($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getFortune() === $this) {
                $user->setFortune(null);
            }
        }

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $count, int $number = 0): self
    {
        if($number > 0) {
            $this->number = $number;
        } else $this->number = $count + 1;

        return $this;
    }
}
