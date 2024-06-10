<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    public function all_livres()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT l.titre_livre, e.id AS edition_id, e.editeur_id, e.prix_vente, e.image_name, 
                       e.format, a.nom_auteur, a.prenom_auteur, ed.nom_editeur, la.auteur_id
                FROM livre l
                LEFT JOIN edition e ON l.id = e.livre_id
                LEFT JOIN livre_auteur la ON l.id = la.livre_id 
                LEFT JOIN auteur a ON la.auteur_id = a.id
                LEFT JOIN editeur ed ON e.editeur_id = ed.id
                ';

        $resultSet = $conn->executeQuery($sql);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    //    /**
    //     * @return Livre[] Returns an array of Livre objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Livre
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
