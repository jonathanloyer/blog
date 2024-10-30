<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{
    #[Route('/auth', name: 'app_auth')]
    public function index(Request $req, UserPasswordHasherInterface $passwordHasher, UserRepository $repo): Response
    {
        $user = new User($passwordHasher);

        $userType = $this->createForm(UserType::class, $user);

        $userType->handleRequest($req);

        if ($userType->isSubmitted() && $userType->isValid()){

            // $plainPassword = $user->getPassword();
            // $hashedPassword = $passwordHasher->hashPassword($user,$plainPassword);
            // $user->setPassword($hashedPassword);

            $user->hashPassword();



            $repo->save($user, true);

            return $this->render('pages/auth/index.html.twig', [
                // inscriptionform et lélement que j'injecte pour le mettre dans le form de la twig
                "inscriptionForm" => $userType,
                "message" => "vous êtes déjà membre,connectez vous!"
            ]);
        }
        

        
        return $this->render('pages/auth/index.html.twig', [
            'inscriptionForm' => $userType
        ]);
    }
}
