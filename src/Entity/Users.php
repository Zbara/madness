<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $platform;

    #[ORM\Column(type: 'string', length: 255)]
    private $real_name;

    #[ORM\Column(type: 'string', length: 255)]
    private $sex;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $avatar;

    #[ORM\Column(type: 'array')]
    private $skills = [];

    #[ORM\Column(type: 'integer')]
    private $room;

    #[ORM\Column(type: 'smallint')]
    private $experience;

    #[ORM\Column(type: 'integer')]
    private $battle_rank;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Settings::class, cascade: ['persist', 'remove'])]
    private $settings;

    #[ORM\Column(type: 'array')]
    private $currency = [];

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Energy::class)]
    private $energies;

    public function __construct()
    {
        $this->energies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlatform(): ?int
    {
        return $this->platform;
    }

    public function setPlatform(int $platform): self
    {
        $this->platform = $platform;

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

    public function getSkills(): ?array
    {
        return $this->skills;
    }

    public function setSkills(array $skills): self
    {
        $this->skills = $skills;

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
}
