<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $req, MessageRepository $repo): Response
    {
        // je récupére les donnée depuis le corps de la requête
        $email = $req->request->get('email');
        $message = $req->request->get('message');

        // je valide les données sinon je retourne 400

        if (!isset($email) || !isset($message) || $email == "" || $message == ""){

            return $this->render('pages/home/index.html.twig', [
                'controller_name' => 'HomeController',
            ]);
        }

        // j'utilise mon Entity Message pour créer un nouveau message
        $newMessage = new Message();
        $newMessage->setEmail($email);
        $newMessage->setMessage($message);

        // j'utilise Repository pour enregistrer le message
        $repo->sauvegarder($newMessage, true);
        // je retourne le fait que c bien envoyé
        return $this->render('pages/home/index.html.twig',[
            'success' => true, 'message' =>"Message Envoyé" ]);

    }

}
