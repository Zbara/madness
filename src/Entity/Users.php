<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $platformId;

    #[ORM\Column(type: 'string', length: 255)]
    private $real_name;

    #[ORM\Column(type: 'string', length: 255)]
    private $sex;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $avatar;

    #[ORM\Column(type: 'integer')]
    private $room;

    #[ORM\Column(type: 'bigint')]
    private $experience;

    #[ORM\Column(type: 'integer')]
    private $battle_rank;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Settings::class, cascade: ['persist', 'remove'])]
    private $settings;

    #[ORM\Column(type: 'array')]
    private $currency = [];

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Energy::class, cascade: ['persist', 'remove'])]
    private $energies;

    #[ORM\Column(type: 'integer')]
    private $created_at;

    #[ORM\Column(type: 'integer')]
    private $last_time;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Skills::class, cascade: ['persist', 'remove'])]
    private $skills;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Session::class, cascade: ['persist', 'remove'])]
    private $session;

    #[Pure]
    public function __construct()
    {
        $this->energies = new ArrayCollection();
        $this->skills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlatformId(): ?int
    {
        return $this->platformId;
    }

    public function setPlatformId(int $platformId): self
    {
        $this->platformId = $platformId;

        return $this;
    }

    public function getRealName(): ?string
    {
        return $this->real_name;
    }

    public function setRealName(string $real_name): self
    {
        $this->real_name = $real_name;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getRoom(): ?int
    {
        return $this->room;
    }

    public function setRoom(int $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(int $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getBattleRank(): ?int
    {
        return $this->battle_rank;
    }

    public function setBattleRank(int $battle_rank): self
    {
        $this->battle_rank = $battle_rank;

        return $this;
    }

    public function getSettings(): ?Settings
    {
        return $this->settings;
    }

    public function setSettings(Settings $settings): self
    {
        // set the owning side of the relation if necessary
        if ($settings->getUser() !== $this) {
            $settings->setUser($this);
        }

        $this->settings = $settings;

        return $this;
    }

    public function getCurrency(): ?array
    {
        return $this->currency;
    }

    public function setCurrency(array $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return Collection<int, Energy>
     */
    public function getEnergies(): Collection
    {
        return $this->energies;
    }

    public function addEnergy(Energy $energy): self
    {
        if (!$this->energies->contains($energy)) {
            $this->energies[] = $energy;
            $energy->setUser($this);
        }

        return $this;
    }

    public function removeEnergy(Energy $energy): self
    {
        if ($this->energies->removeElement($energy)) {
            // set the owning side to null (unless already changed)
            if ($energy->getUser() === $this) {
                $energy->setUser(null);
            }
        }

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

    public function getLastTime(): ?int
    {
        return $this->last_time;
    }

    public function setLastTime(int $last_time): self
    {
        $this->last_time = $last_time;

        return $this;
    }

    /**
     * @return Collection<int, Skills>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skills $skill): self
    {
        if (!$this->skills->contains($skill)) {
            $this->skills[] = $skill;
            $skill->setUser($this);
        }

        return $this;
    }

    public function removeSkill(Skills $skill): self
    {
        if ($this->skills->removeElement($skill)) {
            // set the owning side to null (unless already changed)
            if ($skill->getUser() === $this) {
                $skill->setUser(null);
            }
        }

        return $this;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): self
    {
        // unset the owning side of the relation if necessary
        if ($session === null && $this->session !== null) {
            $this->session->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($session !== null && $session->getUser() !== $this) {
            $session->setUser($this);
        }

        $this->session = $session;

        return $this;
    }
}
