<?php

namespace App\Repository;

use App\Entity\Edition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Edition>
 */
class EditionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Edition::class);
    }

    public function livre_show($edition_id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT e.annee_edition, e.nbr_pages, e.format, e.prix_vente, e.image_name, l.titre_livre, ed.nom_editeur, a.nom_auteur, a.prenom_auteur, la.auteur_id
                FROM edition e
                LEFT JOIN livre l ON e.livre_id = l.id
                LEFT JOIN editeur ed ON e.editeur_id = ed.id
                LEFT JOIN livre_auteur la ON la.livre_id = l.id 
                LEFT JOIN auteur a ON la.auteur_id = a.id
                WHERE e.id = :eid
            
                ';
        $requete_preparee = $conn->prepare($sql);
        $resultSet = $requete_preparee->executeQuery(['eid' => $edition_id]);


        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAssociative();
    }
    //    /**
    //     * @return Edition[] Returns an array of Edition objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Edition
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
