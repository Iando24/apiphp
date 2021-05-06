<?php
    namespace Src\Controller;
    use Src\TableGateways\ProfesseurGateway;

    class ProfesseurController {
        private $db;
        private $requestMethod;
        private $matricule;

        private $professeurGateway;

        public function __construct($db, $requestMethod, $id)
        {
            $this->db = $db;
            $this->requestMethod = $requestMethod;
            $this->matricule = $id;

            $this->professeurGateway = new ProfesseurGateway($db);
        }

        public function processRequest()
        {
            switch ($this->requestMethod) {
                case 'GET':
                    if ($this->matricule) {
                        $response = $this->getProfesseur($this->matricule);
                    } else {
                        $response = $this->getAllProfesseurs();
                    };
                    break;
                case 'POST':
                    $response = $this->createProfesseurFromRequest();
                    break;
                case 'PUT':
                    $response = $this->updateProfesseurFromRequest($this->matricule);
                    break;
                case 'DELETE':
                    $response = $this->deleteProfesseur($this->matricule);
                    break;
                default:
                $response = $this->notFoundResponse();
                    break;
            }
        }

        private function getAllProfesseurs()
        {
            $result = $this->professeurGateway->findAll();
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            echo $response['body'];
            return $response;
        }

        private function getProfesseur($id)
        {
            $result = $this->professeurGateway->find($id);
            if (!$result) {
                return $this->notFoundResponse();
            }
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            return $response;
        }

        private function createProfesseurFromRequest()
        {
            $input = (array)json_decode(file_get_contents('php://input'), TRUE);
            if (! $this->validateProfesseur($input)) {
                return $this->unprocessableEntityResponse();
            }
            $this->professeurGateway->insert($input);
            $response['status_code_header'] = 'HTTP/1.1 201 Created';
            $response['body'] = null;
            return $response;
        }

        private function updateProfesseurFromRequest($id)
        {
            echo $id;
            $result = $this->professeurGateway->find($id);
            if (!$result) {
                return $this->notFoundResponse();
            }
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (!$this->validateProfesseur($input)) {
                return $this->unprocessableEntityResponse();
            }
            $this->professeurGateway->update($id, $input);
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = null;
            return $response;
        }

        private function deleteProfesseur($id)
        {
            $result = $this->professeurGateway->find($id);
            if (! $result) {
                return $this->notFoundResponse();
            }
            $this->professeurGateway->delete($id);
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = null;
            return $response;
        }

        private function validateProfesseur($input)
        {
            if (!isset($input['nom'])) {
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