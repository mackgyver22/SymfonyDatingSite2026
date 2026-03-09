<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/users', name: 'api_users_')]
class UserController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();

        $data = array_map(fn($user) => $this->serializeUser($user), $users);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($this->serializeUser($user));
    }

    private function serializeUser(\App\Entity\User $user): array
    {
        $p = $user->getUserProfile();
        $profile = $p ? [
            'dob'         => $p->getDob(),
            'gender'      => $p->getGender(),
            'looking_for' => $p->getLookingFor(),
            'zip_code'    => $p->getZipCode(),
            'city'        => $p->getCity()?->getCity(),
            'state'       => $p->getState()?->getState(),
            'state_code'  => $p->getState()?->getCode(),
        ] : null;

        $hobbies = $user->getUserHobbies()->map(
            fn($uh) => $uh->getHobby()->getTitle()
        )->toArray();

        $likeDislikes = $user->getUserLikeDislikes()->map(fn($uld) => [
            'item'      => $uld->getLikeRelation()->getTitle(),
            'does_like' => $uld->isDoesLike(),
        ])->toArray();

        return [
            'id'            => $user->getId(),
            'name'          => $user->getName(),
            'email'         => $user->getEmail(),
            'profile'       => $profile,
            'hobbies'       => array_values($hobbies),
            'like_dislikes' => array_values($likeDislikes),
            'created_at'    => $user->getCreatedAt()->format(\DateTimeInterface::ATOM),
        ];
    }
}
