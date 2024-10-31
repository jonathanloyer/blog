<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\UserType;
use App\Repository\UserRepository;
use DateTimeImmutable;
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
    function showProfile(Request $req, UserRepository $repo)
    {
        if (!$this->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute('app_signup');
        }

        $newArticle = new Article();
        $form = $this->createForm(ArticleType::class, $newArticle);

        $form->handleRequest($req);

        // Récuperer l'utilisateur depuis la DB avec son email
        $user = $repo->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

        if ($form->isSubmitted() && $form->isValid()) {
            // Donner la date a l'article
            $newArticle->setDate(new DateTimeImmutable());



            // Ajouter l'article
            $user->addArticle($newArticle);

            // Enregistrer dans la DB
            $repo->save($user, true);
        }

        return $this->render('pages/profil/index.html.twig', ['articleForm' => $form, "articles" => $user->getArticles()]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout() {}
}