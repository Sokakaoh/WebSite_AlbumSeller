<?php

namespace App\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;

class ProduitModel {

    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }
    // http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/query-builder.html#join-clauses
    public function getAllProduits() {
//        $sql = "SELECT p.id, t.libelle, p.nom, p.prix, p.photo
//            FROM produits as p,typeProduits as t
//            WHERE p.typeProduit_id=t.id ORDER BY p.nom;";
//        $req = $this->db->query($sql);
//        return $req->fetchAll();
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('p.id', 't.libelle', 'p.nom', 'p.prix', 'p.photo')
            ->from('album', 'p')
            ->innerJoin('p', 'typeAlbum', 't', 'p.typeAlbum_id=t.id')
            ->addOrderBy('p.nom', 'ASC');
        return $queryBuilder->execute()->fetchAll();

    }

    public function insertProduit($donnees) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('album')
            ->values([
                'nom' => '?',
                'typeAlbum_id' => '?',
                'prix' => '?',
                'photo' => '?'
            ])
            ->setParameter(0, $donnees['nom'])
            ->setParameter(1, $donnees['typeAlbum_id'])
            ->setParameter(2, $donnees['prix'])
            ->setParameter(3, $donnees['photo'])
        ;
        return $queryBuilder->execute();
    }

    function getProduit($id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('id', 'typeAlbum_id', 'nom', 'prix', 'photo')
            ->from('album')
            ->where('id= :id')
            ->setParameter('id', $id);
        return $queryBuilder->execute()->fetch();
    }

    public function updateProduit($donnees) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->update('album')
            ->set('nom', '?')
            ->set('typeAlbum_id','?')
            ->set('prix','?')
            ->set('photo','?')
            ->where('id= ?')
            ->setParameter(0, $donnees['nom'])
            ->setParameter(1, $donnees['typeAlbum_id'])
            ->setParameter(2, $donnees['prix'])
            ->setParameter(3, $donnees['photo'])
            ->setParameter(4, $donnees['id']);
        return $queryBuilder->execute();
    }

    public function deleteProduit($id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('album')
            ->where('id = :id')
            ->setParameter('id',(int)$id)
        ;
        return $queryBuilder->execute();
    }



}