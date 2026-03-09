<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 100)]
    private ?string $name = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToOne(targetEntity: UserProfile::class, mappedBy: 'user', orphanRemoval: true)]
    private ?UserProfile $userProfile = null;

    /**
     * @var Collection<int, UserLikeDislike>
     */
    #[ORM\OneToMany(targetEntity: UserLikeDislike::class, mappedBy: 'user')]
    private Collection $userLikeDislikes;

    /**
     * @var Collection<int, UserHobby>
     */
    #[ORM\OneToMany(targetEntity: UserHobby::class, mappedBy: 'user')]
    private Collection $userHobbies;

    public function __construct()
    {
        $this->userLikeDislikes = new ArrayCollection();
        $this->userHobbies = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function eraseCredentials(): void
    {
        // Clear temporary plain-text password if stored
    }

    public function getUserProfile(): ?UserProfile
    {
        return $this->userProfile;
    }

    public function setUserProfile(?UserProfile $userProfile): static
    {
        if ($userProfile === null && $this->userProfile !== null) {
            $this->userProfile->setUser(null);
        }

        if ($userProfile !== null && $userProfile->getUser() !== $this) {
            $userProfile->setUser($this);
        }

        $this->userProfile = $userProfile;

        return $this;
    }

    /**
     * @return Collection<int, UserLikeDislike>
     */
    public function getUserLikeDislikes(): Collection
    {
        return $this->userLikeDislikes;
    }

    public function addUserLikeDislike(UserLikeDislike $userLikeDislike): static
    {
        if (!$this->userLikeDislikes->contains($userLikeDislike)) {
            $this->userLikeDislikes->add($userLikeDislike);
            $userLikeDislike->setUser($this);
        }

        return $this;
    }

    public function removeUserLikeDislike(UserLikeDislike $userLikeDislike): static
    {
        if ($this->userLikeDislikes->removeElement($userLikeDislike)) {
            // set the owning side to null (unless already changed)
            if ($userLikeDislike->getUser() === $this) {
                $userLikeDislike->setUser(null);
            }
        }

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
            $userHobby->setUser($this);
        }

        return $this;
    }

    public function removeUserHobby(UserHobby $userHobby): static
    {
        if ($this->userHobbies->removeElement($userHobby)) {
            // set the owning side to null (unless already changed)
            if ($userHobby->getUser() === $this) {
                $userHobby->setUser(null);
            }
        }

        return $this;
    }
}
