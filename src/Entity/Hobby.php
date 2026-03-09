<?php

namespace App\Entity;

use App\Repository\HobbyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HobbyRepository::class)]
class Hobby
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @var Collection<int, UserHobby>
     */
    #[ORM\OneToMany(targetEntity: UserHobby::class, mappedBy: 'hobby')]
    private Collection $userHobbies;

    public function __construct()
    {
        $this->userHobbies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, UserHobby>
     */
    public function getUserHobbies(): Collection
    {
        return $this->userHobbies;
    }

    public function addUserHobby(UserHobby $userHobby): static
    {
        if (!$this->userHobbies->contains($userHobby)) {
            $this->userHobbies->add($userHobby);
            $userHobby->setHobby($this);
        }

        return $this;
    }

    public function removeUserHobby(UserHobby $userHobby): static
    {
        if ($this->userHobbies->removeElement($userHobby)) {
            // set the owning side to null (unless already changed)
            if ($userHobby->getHobby() === $this) {
                $userHobby->setHobby(null);
            }
        }

        return $this;
    }
}
