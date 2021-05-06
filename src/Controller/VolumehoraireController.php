<?php
    namespace Src\Controller;

    use Src\TableGateways\VolumehoraireGateway;

    class VolumehoraireController {

        private $db;
        private $requestMethod;
        private $id;

        private $volumehoraireGateway;

        public function __construct($db, $requestMethod, $id)
        {
            $this->db = $db;
            $this->requestMethod = $requestMethod;
            $this->id = $id;

            $this->volumehoraireGateway = new VolumehoraireGateway($db);
        }

        public function processRequest()
        {
            switch ($this->requestMethod) {
                case 'GET':
                    if ($this->id) {
                        $response = $this->getVolumehoraire($this->id);
                    } else {
                        $response = $this->getVolumehoraires();
                    };
                    break;
                case 'POST':
                    $response = $this->createVolumehoraireFromRequest();
                    break;
                case 'PUT':
                    $response = $this->updateVolumehoraireFromRequest($this->id);
                    break;
                case 'DELETE':
                    $response = $this->deleteVolumehoraire($this->id);
                    break;
                default:
                    $response = $this->notFoundResponse();
                    break;
            }
        }

        private function getVolumehoraire($id)
        {
            $result = $this->volumehoraireGateway->find($id);
            if (!$result) {
                return $this->notFoundResponse();
            }
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            echo $response['body'];
            return $response;
        }

        private function getVolumehoraires()
        {
            $result = $this->volumehoraireGateway->findAll();
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            echo $response['body'];
            return $response;
        }

        private function createVolumehoraireFromRequest()
        {
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (! $this->valideVolumehoraire($input)) {
                return $this->unprocessableEntityResponse();
            }
            $this->volumehoraireGateway->insert($input);
            $response['status_code_header'] = 'HTTP/1.1 201 Created';
            $response['body'] = null;
            return $response;
        }

        private function updateVolumehoraireFromRequest($id)
        {
            $result = $this->volumehoraireGateway->find($id);
            if (!$result) {
                return $this->notFoundResponse();
            }
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (!$this->valideVolumehoraire($input)) {
                return $this->unprocessableEntityResponse();
            }
            $this->volumehoraireGateway->update($id, $input);
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = null;
            return $response;
        }

        private function deleteVolumehoraire($id)
        {
            $result = $this->volumehoraireGateway->find($id);
            if (! $result) {
                return $this->notFoundResponse();
            }
            $this->volumehoraireGateway->delete($id);
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = null;
            return $response;
        }

        private function valideVolumehoraire($input)
        {
            if (!isset($input['matricule'])) {
                return false;
            }
            if (!isset($input['nummat'])) {
                return false;
            }
            if (! isset($input['tauxhoraire'])) {
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