<?php

namespace App\Repository;

use App\Entity\PanierArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PanierArticle>
 */
class PanierArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PanierArticle::class);
    }

    public function panier_contenu($user_id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT l.titre_livre, e.id AS edition_id, e.editeur_id, e.prix_vente, e.image_name, 
                       e.format, a.nom_auteur, a.prenom_auteur, ed.nom_editeur, la.auteur_id, pa.quantite
                FROM panier_article pa
                JOIN panier p ON pa.panier_id = p.id
                JOIN user u ON p.user_id = u.id
                JOIN edition e ON pa.edition_id = e.id
                JOIN editeur ed ON e.editeur_id = ed.id
                JOIN livre l ON l.id = e.livre_id
                JOIN livre_auteur la ON l.id = la.livre_id 
                JOIN auteur a ON la.auteur_id = a.id
                WHERE u.id = :usid
                ';

                $requete_preparee = $conn->prepare($sql);
                $resultSet = $requete_preparee->executeQuery(['usid' => $user_id]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    //    /**
    //     * @return PanierArticle[] Returns an array of PanierArticle objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PanierArticle
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
