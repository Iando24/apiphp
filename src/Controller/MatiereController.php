<?php
namespace Src\Controller;

use Src\TableGateways\MatiereGateway;

class MatiereController {

    private $db;
    private $requestMethod;
    private $nummat;

    private $matiereGateway;

    public function __construct($db, $requestMethod, $id)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->nummat = $id;

        $this->matiereGateway = new MatiereGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->nummat) {
                    $response = $this->getMatiere($this->nummat);
                } else {
                    $response = $this->getAllMatieres();
                };
                break;
            case 'POST':
                $response = $this->createMatiereFromRequest();
                break;
            case 'PUT':
                $response = $this->updateMatiereFromRequest($this->nummat);
                break;
            case 'DELETE':
                $response = $this->deleteMatiere($this->nummat);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        header($response['status_code_header']);

        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllMatieres()
    {
        $result = $this->matiereGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getMatiere($id)
    {
        $result = $this->matiereGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createMatiereFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateMatiere($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->matiereGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function updateMatiereFromRequest($id)
    {
        $result = $this->matiereGateway->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (!$this->validateMatiere($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->matiereGateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function deleteMatiere($id)
    {
        $result = $this->matiereGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->matiereGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validateMatiere($input)
    {
        if (!isset($input['designation'])) {
            return false;
        }
        if (! isset($input['nbheure'])) {
            return false;
        }
        return true;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}