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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    #[Route('/auth', name: 'app_auth')]
    public function index(Request $req, UserPasswordHasherInterface $passwordHasher, UserRepository $repo): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_profil');
        }
        $user = new User($passwordHasher);

        $userType = $this->createForm(UserType::class, $user);

        $userType->handleRequest($req);

        if ($userType->isSubmitted() && $userType->isValid()) {

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

    #[Route('/login', name: 'app_login')]
    public function login()
    {
        return new Response("Connexion Réussie");
    }

    #[Route('/profil', name: 'app_profil')]
    public function showprofile()
    {

        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_auth');
        }
        return $this->render('pages/profil/index.html.twig');
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout() {
      // Controleur peut etre vide!
    }
}