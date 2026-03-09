<?php

namespace App\Entity;

use App\Repository\LikeDislikeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeDislikeRepository::class)]
class LikeDislike
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @var Collection<int, UserLikeDislike>
     */
    #[ORM\OneToMany(targetEntity: UserLikeDislike::class, mappedBy: 'like_relation')]
    private Collection $userLikeDislikes;

    public function __construct()
    {
        $this->userLikeDislikes = new ArrayCollection();
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
            $userLikeDislike->setLikeRelation($this);
        }

        return $this;
    }

    public function removeUserLikeDislike(UserLikeDislike $userLikeDislike): static
    {
        if ($this->userLikeDislikes->removeElement($userLikeDislike)) {
            // set the owning side to null (unless already changed)
            if ($userLikeDislike->getLikeRelation() === $this) {
                $userLikeDislike->setLikeRelation(null);
            }
        }

        return $this;
    }
}
