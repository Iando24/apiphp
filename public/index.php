<?php
    require "../bootstrap.php";

    use Src\Controller\ProfesseurController;
    use Src\Controller\MatiereController;
    use Src\Controller\VolumehoraireController;
    use Src\Controller\BulletinController;

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri );
    $uri_available = ['professeur','matiere','volumehoraire','bulletindepaie'];
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    
    if(!in_array($uri[1], $uri_available)) {
        header("HTTP/1.1 404 Not Found");
        exit();
    }

    $id = null;

    switch ($uri[1]) {
        case 'professeur':
            if (isset($uri[2])) {
                $id = (string) $uri[2];
            }
            $controller = new ProfesseurController($dbConnection, $requestMethod, $id);
            break;
        case 'matiere':
            if (isset($uri[2])) {
                $id = (string) $uri[2];
            }
            $controller = new MatiereController($dbConnection, $requestMethod, $id);
            break;
        case 'volumehoraire':
            if (isset($uri[2])) {
                $id = (int) $uri[2];
            }
            $controller = new VolumehoraireController($dbConnection, $requestMethod, $id);
            break;
        case 'bulletindepaie':
            if(isset($uri[2])){
                $id = (string) $uri[2];
            }
            $controller = new BulletinController($dbConnection, $requestMethod, $id);
            break;
        default:
            $response = $this->notFoundResponse();
            break;
    }

    $requestMethod = $_SERVER["REQUEST_METHOD"];

// pass the request method and user ID to the Controller and process the HTTP request:
    $controller->processRequest();