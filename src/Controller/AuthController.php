<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/auth', name: 'api_auth_')]
class AuthController extends AbstractController
{
    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        UserRepository $userRepository,
    ): JsonResponse {
        $data = $request->getPayload();

        if (!$data->has('email') || !$data->has('password') || !$data->has('name')) {
            return $this->json(['error' => 'Missing required fields: email, password, name'], Response::HTTP_BAD_REQUEST);
        }

        if ($userRepository->findOneBy(['email' => $data->get('email')])) {
            return $this->json(['error' => 'Email already in use'], Response::HTTP_CONFLICT);
        }

        $user = new User();
        $user->setEmail($data->get('email'));
        $user->setName($data->get('name'));
        $user->setPassword($passwordHasher->hashPassword($user, $data->get('password')));

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json(['errors' => $messages], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $em->persist($user);
        $em->flush();

        return $this->json([
            'id'    => $user->getId(),
            'email' => $user->getEmail(),
            'name'  => $user->getName(),
        ], Response::HTTP_CREATED);
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(): never
    {
        // This is intercepted by the json_login security firewall.
        // The firewall handles authentication and returns the JWT token before this runs.
        throw new \LogicException('This should never be reached.');
    }
}
