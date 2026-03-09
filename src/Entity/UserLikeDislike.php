<?php

namespace App\Entity;

use App\Repository\UserLikeDislikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserLikeDislikeRepository::class)]
class UserLikeDislike
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userLikeDislikes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userLikeDislikes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?LikeDislike $like_relation = null;

    #[ORM\Column]
    private ?bool $does_like = null;

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

    public function getLikeRelation(): ?LikeDislike
    {
        return $this->like_relation;
    }

    public function setLikeRelation(?LikeDislike $like_relation): static
    {
        $this->like_relation = $like_relation;

        return $this;
    }

    public function isDoesLike(): ?bool
    {
        return $this->does_like;
    }

    public function setDoesLike(bool $does_like): static
    {
        $this->does_like = $does_like;

        return $this;
    }
}
