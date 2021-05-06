<?php
    namespace Src\TableGateways;

    class ProfesseurGateway {

        private $db = null;

        public function __construct($db)
        {
            $this->db = $db;
        }

        public function findAll()
        {
            $statement = "
                SELECT 
                    *
                FROM
                    professeur;
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
                    matricule, nom
                FROM
                    professeur
                WHERE matricule = ?;
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
                INSERT INTO professeur 
                    (matricule, nom)
                VALUES
                    (:matricule, :nom);
            ";

            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array(
                    'matricule' => $input['matricule'],
                    'nom'  => $input['nom'],
                ));
                return $statement->rowCount();
            } catch (\PDOException $e) {
                exit($e->getMessage());
            }    
        }

        public function update($id, Array $input)
        {
            $statement = "
                UPDATE professeur
                SET 
                    nom  = :nom
                WHERE matricule = :matricule;
            ";

            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array(
                    'matricule' => (string) $id,
                    'nom' => $input['nom']
                ));
                return $statement->rowCount();
            } catch (\PDOException $e) {
                exit($e->getMessage());
            }    
        }

        public function delete($id)
        {
            $statement = "
                DELETE FROM professeur
                WHERE matricule = :matricule;
            ";

            try {
                $statement = $this->db->prepare($statement);
                $statement->execute(array('matricule' => $id));
                return $statement->rowCount();
            } catch (\PDOException $e) {
                exit($e->getMessage());
            }    
        }

    }