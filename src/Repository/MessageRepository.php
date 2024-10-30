<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $doctrine)
    {
        parent::__construct($doctrine, Message::class);
    }

    // Methode pour enregistrer un Message dans la BDD
    public function sauvegarder(Message $nouveauMessage, ?bool $isSave)
    {
        // Créer la requete SQL
        $this->getEntityManager()->persist($nouveauMessage);
        if ($isSave) {
            // Execute la requete
            $this->getEntityManager()->flush();
        }
        return $nouveauMessage;
    }
}
