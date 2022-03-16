<?php

namespace App\Entity;

use App\Repository\SettingsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SettingsRepository::class)]
class Settings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(inversedBy: 'settings', targetEntity: Users::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'boolean')]
    private $sound;

    #[ORM\Column(type: 'integer')]
    private $sound_volume;

    #[ORM\Column(type: 'boolean')]
    private $music;

    #[ORM\Column(type: 'integer')]
    private $music_volume;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSound(): ?bool
    {
        return $this->sound;
    }

    public function setSound(bool $sound): self
    {
        $this->sound = $sound;

        return $this;
    }

    public function getSoundVolume(): ?int
    {
        return $this->sound_volume;
    }

    public function setSoundVolume(int $sound_volume): self
    {
        $this->sound_volume = $sound_volume;

        return $this;
    }

    public function getMusic(): ?bool
    {
        return $this->music;
    }

    public function setMusic(bool $music): self
    {
        $this->music = $music;

        return $this;
    }

    public function getMusicVolume(): ?int
    {
        return $this->music_volume;
    }

    public function setMusicVolume(int $music_volume): self
    {
        $this->music_volume = $music_volume;

        return $this;
    }
}
