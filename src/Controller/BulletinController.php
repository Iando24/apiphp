<?php
    namespace Src\Controller;

    use Src\TableGateways\BulletinGateway;

    class BulletinController {
    	private $db;
        private $requestMethod;
        private $id;

        private $bulletinGateway;

        public function __construct($db, $requestMethod, $id)
        {
            $this->db = $db;
            $this->requestMethod = $requestMethod;
            $this->id = $id;

            $this->bulletinGateway = new BulletinGateway($db);
        }

        public function processRequest()
        {
        	switch ($this->requestMethod) {
        		case 'GET':
        			$response = $this->getMatricule($this->id);
        			break;
    			default:
    				$response = $this->notFoundResponse();
    				break;
        	}
        }

        private function getMatricule($id)
        {
            $result = $this->bulletinGateway->findByMatricule($id);
            if (!$result) {
                return $this->notFoundResponse();
            }
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            echo $response['body'];
            return $response;
        }

    }