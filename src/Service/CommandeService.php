<?php

namespace App\Service;

use App\Entity\Commande;
use App\Entity\CommandeArticle;
use App\Entity\CommandeItem;
use App\Entity\Panier;
use Doctrine\ORM\EntityManagerInterface;

class CommandeService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function transformerPanierEnCommande(Panier $panier): Commande
    {
        $commande = new Commande();
        $commande->setUser($panier->getUser());
        $commande->setStatut('en cours');
        $commande->setDateCommande(new \DateTime());
        // Ajouter d'autres informations nécessaires à la commande
        $totalCost = 0;

        foreach ($panier->getPanierArticles() as $panierItem) {
            $commandeItem = new CommandeArticle();
            $commandeItem->setEdition($panierItem->getEdition());
            $commandeItem->setQuantite($panierItem->getQuantite());
            $commandeItem->setPrixAchat($panierItem->getEdition()->getPrixVente());

             // Mise à jour du stock
             $edition = $panierItem->getEdition();
             $nouveauStock = $edition->getStock() - $panierItem->getQuantite();
 
             if ($nouveauStock < 0) {
                 throw new \Exception('Stock insuffisant pour l\'article: ' . $edition->getLivre()->getTitreLivre());
             }
 
             $edition->setStock($nouveauStock);

            $commande->addCommandeArticle($commandeItem);
            $commandeItem->setCommande($commande);

            $totalCost += $commandeItem->getPrixAchat() * $commandeItem->getQuantite();
        }
        $commande->setCoutTotal($totalCost); 


        $this->em->persist($commande);
        $this->em->flush();

        $this->em->remove($panier);
        $this->em->flush();

        return $commande;
    }
}
