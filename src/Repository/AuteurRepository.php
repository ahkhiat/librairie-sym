<?php

namespace App\Repository;

use App\Entity\Auteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Auteur>
 */
class AuteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Auteur::class);
    }

    public function auteur_show($auteur_id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT l.titre_livre, e.annee_edition, e.image_name, ed.nom_editeur, e.id AS edition_id
                FROM edition e
                JOIN livre_auteur la ON la.livre_id = e.livre_id 
                LEFT JOIN auteur a ON la.auteur_id = a.id
                LEFT JOIN livre l ON l.id = la.livre_id
                JOIN editeur ed ON e.editeur_id = ed.id
                WHERE a.id = :aid

                ';
        $requete_preparee = $conn->prepare($sql);
        $resultSet = $requete_preparee->executeQuery(['aid' => $auteur_id]);

        return $resultSet->fetchAllAssociative();
    }
    //    /**
    //     * @return Auteur[] Returns an array of Auteur objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Auteur
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
