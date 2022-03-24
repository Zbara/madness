<?php

namespace App\Entity;

use App\Repository\PveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PveRepository::class)]
class Pve
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'battlePve')]
    private $user;

    #[ORM\Column(type: 'integer')]
    private $battle_start;

    #[ORM\Column(type: 'integer')]
    private $battle_finish;

    #[ORM\Column(type: 'integer')]
    private $boss_id;

    #[ORM\Column(type: 'integer')]
    private $health;

    #[ORM\OneToMany(mappedBy: 'battle', targetEntity: Users::class)]
    private $users;

    #[ORM\OneToMany(mappedBy: 'battle', targetEntity: PveUsers::class, cascade: ['persist', 'remove'],)]
    private $pveUsers;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->pveUsers = new ArrayCollection();
    }

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

    public function getBattleStart(): ?int
    {
        return $this->battle_start;
    }

    public function setBattleStart(int $battle_start): self
    {
        $this->battle_start = $battle_start;

        return $this;
    }

    public function getBattleFinish(): ?int
    {
        return $this->battle_finish;
    }

    public function setBattleFinish(int $battle_finish): self
    {
        $this->battle_finish = $battle_finish;

        return $this;
    }

    public function getBossId(): ?int
    {
        return $this->boss_id;
    }

    public function setBossId(int $boss_id): self
    {
        $this->boss_id = $boss_id;

        return $this;
    }

    public function getHealth(): ?int
    {
        return $this->health;
    }

    public function setHealth(int $health): self
    {
        if ($health < 0) {
            $this->health = 0;
        } else $this->health = $health;

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
            $user->setBattle($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getBattle() === $this) {
                $user->setBattle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PveUsers>
     */
    public function getPveUsers(): Collection
    {
        return $this->pveUsers;
    }

    public function addPveUser(PveUsers $pveUser): self
    {
        if (!$this->pveUsers->contains($pveUser)) {
            $this->pveUsers[] = $pveUser;
            $pveUser->setBattle($this);
        }

        return $this;
    }

    public function removePveUser(PveUsers $pveUser): self
    {
        if ($this->pveUsers->removeElement($pveUser)) {
            // set the owning side to null (unless already changed)
            if ($pveUser->getBattle() === $this) {
                $pveUser->setBattle(null);
            }
        }

        return $this;
    }
}
