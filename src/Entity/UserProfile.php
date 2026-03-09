<?php

namespace App\Entity;

use App\Repository\UserProfileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserProfileRepository::class)]
class UserProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'userProfile')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?int $dob = null;

    #[ORM\ManyToOne(inversedBy: 'userProfiles')]
    private ?City $city = null;

    #[ORM\ManyToOne(inversedBy: 'userProfiles')]
    private ?State $state = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $zip_code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(length: 255)]
    private ?string $looking_for = null;

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

    public function getDob(): ?int
    {
        return $this->dob;
    }

    public function setDob(?int $dob): static
    {
        $this->dob = $dob;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zip_code;
    }

    public function setZipCode(?string $zip_code): static
    {
        $this->zip_code = $zip_code;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getLookingFor(): ?string
    {
        return $this->looking_for;
    }

    public function setLookingFor(string $looking_for): static
    {
        $this->looking_for = $looking_for;

        return $this;
    }
}
