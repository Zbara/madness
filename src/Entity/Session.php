<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(inversedBy: 'session', targetEntity: Users::class, cascade: ['persist', 'remove'])]
    private $user;

    #[ORM\Column(type: 'string', length: 255)]
    private $session_key;

    #[ORM\Column(type: 'integer')]
    private $created;

    #[ORM\Column(type: 'integer')]
    private $count;

    #[ORM\Column(type: 'array')]
    private $friends = [];

    #[ORM\Column(type: 'string', length: 255)]
    private $referrer;

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

    public function getSessionKey(): ?string
    {
        return $this->session_key;
    }

    public function setSessionKey(string $session_key): self
    {
        $this->session_key = $session_key;

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

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getFriends(): ?array
    {
        return $this->friends;
    }

    public function setFriends(array $friends): self
    {
        $this->friends = $friends;

        return $this;
    }

    public function getReferrer(): ?string
    {
        return $this->referrer;
    }

    public function setReferrer(string $referrer): self
    {
        $this->referrer = $referrer;

        return $this;
    }
}
