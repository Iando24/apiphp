<?php
namespace Src\TableGateways;

class MatiereGateway {

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
                matiere;
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
                matiere
            WHERE nummat = ?;
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
            INSERT INTO matiere 
                (nummat, designation, nbheure)
            VALUES
                (:nummat, :designation, :nbheure);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'nummat' => $input['nummat'],
                'designation'  => $input['designation'],
                'nbheure' => $input['nbheure'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function update($id, Array $input)
    {
        $statement = "
            UPDATE matiere
            SET 
                designation  = :designation,
                nbheure = :nbheure
            WHERE nummat = :nummat;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'nummat' => (string) $id,
                'designation'  => $input['designation'],
                'nbheure' => $input['nbheure'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM matiere
            WHERE nummat = :id;
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