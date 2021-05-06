<?php
    namespace Src\TableGateways;

    class BulletinGateway {

    	private $db = null;

    	public function __construct($db)
        {
            $this->db = $db;
        }

        public function findByMatricule($id)
        {
			$statement = "
                SELECT 
                    professeur.matricule,
                    professeur.nom,
                    matiere.nummat,
                    matiere.designation,
                    matiere.nbheure,
                    volumehoraire.tauxhoraire
                FROM 
                    volumehoraire inner join professeur on
                    professeur.matricule = volumehoraire.matricule
                    inner join matiere on
                    matiere.nummat = volumehoraire.nummat
                WHERE volumehoraire.matricule = ?;
            ";

            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array($id));
                $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
                return $result;
            } catch (\PDOException $e) {
                exit($e->getMessage());
            }
        }

    }