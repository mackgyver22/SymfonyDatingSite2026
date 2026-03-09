<?php

namespace App\Entity;

use App\Repository\UserHobbyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserHobbyRepository::class)]
class UserHobby
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userHobbies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userHobbies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hobby $hobby = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getHobby(): ?Hobby
    {
        return $this->hobby;
    }

    public function setHobby(?Hobby $hobby): static
    {
        $this->hobby = $hobby;

        return $this;
    }
}
