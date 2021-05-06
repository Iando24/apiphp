<?php
    namespace Src\TableGateways;

    class VolumehoraireGateway {
        private $db = null;

        public function __construct($db)
        {
            $this->db = $db;
        }

        public function findAll()
        {
            $statement = "
                SELECT 
                    volumehoraire.id,
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
                    matiere.nummat = volumehoraire.nummat;
            ";

            try {
                $statement = $this->db->query($statement);
                $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
                return $result;
            } catch (\PDOException $e) {
                exit($e->getMessage());
            }
        }

        public function find($id)
        {
            $statement = "
                SELECT 
                    *
                FROM
                    volumehoraire
                WHERE id = ?;
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

        public function insert(Array $input)
        {
            $statement = "
                INSERT INTO volumehoraire 
                    (matricule, nummat, tauxhoraire)
                VALUES
                    (:matricule, :nummat, :tauxhoraire);
            ";

            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array(
                    'matricule' => $input['matricule'],
                    'nummat'  => $input['nummat'],
                    'tauxhoraire' => $input['tauxhoraire'],
                ));
                return $statement->rowCount();
            } catch (\PDOException $e) {
                exit($e->getMessage());
            }    
        }

        public function update($id, Array $input)
        {
            $statement = "
                UPDATE volumehoraire
                SET 
                    matricule  = :matricule,
                    nummat = :nummat,
                    tauxhoraire = :tauxhoraire
                WHERE id = :id;
            ";

            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array(
                    'id' => (int) $id,
                    'matricule'  => $input['matricule'],
                    'nummat' => $input['nummat'],
                    'tauxhoraire' => $input['tauxhoraire'],
                ));
                return $statement->rowCount();
            } catch (\PDOException $e) {
                exit($e->getMessage());
            }    
        }

        public function delete($id)
        {
            $statement = "
                DELETE FROM volumehoraire
                WHERE id = :id;
            ";

            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array('id' => $id));
                return $statement->rowCount();
            } catch (\PDOException $e) {
                exit($e->getMessage());
            }    
        }

    }