<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use App\Entity\Collection as EntityCollection;

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

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Session::class, cascade: ['persist', 'remove'])]
    private $session;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Pve::class)]
    private $battlePve;

    #[ORM\ManyToOne(targetEntity: Pve::class, inversedBy: 'users')]
    private $battle;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: PveUsers::class)]
    private $pveUsers;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Fortune::class)]
    private $fortunes;

    #[ORM\Column(type: 'integer')]
    private $fortune_experince = 0;

    #[ORM\ManyToOne(targetEntity: Fortune::class, inversedBy: 'users')]
    private $fortune;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Skills::class, cascade: ['persist', 'remove'])]
    private $skills;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Job::class, cascade: ['persist', 'remove'])]
    private $job;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: EntityCollection::class, cascade: ['persist', 'remove'])]
    private $collection;

    #[Pure]
    public function __construct()
    {
        $this->energies = new ArrayCollection();
        $this->battlePve = new ArrayCollection();
        $this->pveUsers = new ArrayCollection();
        $this->fortunes = new ArrayCollection();
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

    public function getCurrency(string $type = 'all'): array|int
    {
        if($type == 'all') {
            return $this->currency;
        }
        return $this->currency[$type];
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

    /**
     * @return Collection<int, Pve>
     */
    public function getBattlePve(): Collection
    {
        return $this->battlePve;
    }

    public function addBattlePve(Pve $battlePve): self
    {
        if (!$this->battlePve->contains($battlePve)) {
            $this->battlePve[] = $battlePve;
            $battlePve->setUser($this);
        }

        return $this;
    }

    public function removeBattlePve(Pve $battlePve): self
    {
        if ($this->battlePve->removeElement($battlePve)) {
            // set the owning side to null (unless already changed)
            if ($battlePve->getUser() === $this) {
                $battlePve->setUser(null);
            }
        }

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
            $pveUser->setUser($this);
        }

        return $this;
    }

    public function removePveUser(PveUsers $pveUser): self
    {
        if ($this->pveUsers->removeElement($pveUser)) {
            // set the owning side to null (unless already changed)
            if ($pveUser->getUser() === $this) {
                $pveUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Fortune>
     */
    public function getFortunes(): Collection
    {
        return $this->fortunes;
    }

    public function addFortune(Fortune $fortune): self
    {
        if (!$this->fortunes->contains($fortune)) {
            $this->fortunes[] = $fortune;
            $fortune->setUser($this);
        }

        return $this;
    }

    public function removeFortune(Fortune $fortune): self
    {
        if ($this->fortunes->removeElement($fortune)) {
            // set the owning side to null (unless already changed)
            if ($fortune->getUser() === $this) {
                $fortune->setUser(null);
            }
        }

        return $this;
    }

    public function getFortuneExperince(): ?int
    {
        return $this->fortune_experince;
    }

    public function setFortuneExperince(int $fortune_experience): self
    {
        $this->fortune_experince = $fortune_experience + 1;

        return $this;
    }

    public function getFortune(): ?Fortune
    {
        return $this->fortune;
    }

    public function setFortune(?Fortune $fortune): self
    {
        $this->fortune = $fortune;

        return $this;
    }


    public function getSkills(): ?Skills
    {
        return $this->skills;
    }

    public function setSkills(Skills $skills): self
    {
        // set the owning side of the relation if necessary
        if ($skills->getUser() !== $this) {
            $skills->setUser($this);
        }

        $this->skills = $skills;

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): self
    {
        // unset the owning side of the relation if necessary
        if ($job === null && $this->job !== null) {
            $this->job->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($job !== null && $job->getUser() !== $this) {
            $job->setUser($this);
        }

        $this->job = $job;

        return $this;
    }

    public function getCollection(): ?EntityCollection
    {
        return $this->collection;
    }

    public function setCollection(?EntityCollection $collection): self
    {
        // unset the owning side of the relation if necessary
        if ($collection === null && $this->collection !== null) {
            $this->collection->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($collection !== null && $collection->getUser() !== $this) {
            $collection->setUser($this);
        }

        $this->collection = $collection;

        return $this;
    }

}
