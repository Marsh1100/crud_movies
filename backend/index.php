<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ .'\autoload.php';

use database\DatabaseConnection;

use app\exceptions\DataException;
use app\exceptions\ValidationException;

use app\validators\MovieValidator;


use app\controllers\MovieController;
use app\controllers\GenreController;
use app\controllers\CountryController;

use app\repositories\MovieRepository;
use app\repositories\CountryRepository;
use app\repositories\GenreRepository;
use app\repositories\MovieGenreRepository;
use app\repositories\MovieStaffRepository;
use app\repositories\StaffRepository;

use app\services\MovieService;
use app\services\CountryService;
use app\services\GenreService;

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204); // No Content
    exit();
}

try{
        $db = new DatabaseConnection;
        $pdo = $db -> getConnection();

        $movieRepository = new MovieRepository(pdo: $pdo);
        $genreRepository = new GenreRepository($pdo); 
        $staffRepository = new StaffRepository($pdo); 
        $countryRepository = new CountryRepository($pdo); 
        $movieGenreRepository = new MovieGenreRepository($pdo);
        $movieStaffRepository = new MovieStaffRepository($pdo);


    if(strpos($_SERVER['REQUEST_URI'], '/movie') !== false)
    {
        $service = new MovieService($movieRepository,$genreRepository, $staffRepository, $countryRepository, $movieGenreRepository,  $movieStaffRepository, $pdo);
        $validator = new MovieValidator();
    
        $movie = new MovieController($service, $validator);
        typeRequest(controller: $movie);
    }elseif(strpos($_SERVER['REQUEST_URI'], '/country') !== false){
        $service = new CountryService($countryRepository);
        $country = new CountryController($service);
        typeRequest(controller: $country);

    }elseif(strpos($_SERVER['REQUEST_URI'], '/genre') !== false){
        
        $service = new GenreService($genreRepository);
        $genre = new GenreController($service);
        typeRequest(controller: $genre);

    }



}catch(ValidationException $e){
    http_response_code(400);
    echo json_encode(['error'=>$e->getMessage()]);
}
catch(DataException $e){
    http_response_code(404);
    echo json_encode(['error'=>$e->getMessage()]);

}catch(PDOException $e){
    http_response_code(500);
    echo json_encode(['error BD'=>$e->getMessage()]);
}catch(\Exception $e){
    http_response_code(500);
    echo json_encode(['error'=>$e->getMessage()]);
}catch(TypeError $e)
{
    http_response_code(500);
    echo "Se capturó un TypeError: ".$e->getMessage(); 
}


function typeRequest($controller)
{
    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            $id = empty($_GET['id']) ? null : $_GET['id'];
           
            $id == null ?  $result = json_encode($controller->get()) :  $result = json_encode($controller->getById($id));
            echo $result;
            break;
        case 'POST':
        $body = json_decode(file_get_contents('php://input'), true);
            $controller->add($body);
            echo json_encode(["success" => "Registro agregado con éxito"]);
            break;
        case 'PUT':
            $body = json_decode(file_get_contents('php://input'), true);
            $controller->update($body);
            echo json_encode(["success" => "Registro actualizado con éxito"]);
            break;
        case 'DELETE':
            $id = empty($_GET['id']) ? null : $_GET['id'];
            $controller->delete($id);
            echo json_encode(["success" => "Registro eliminado con éxito"]);
            break;
        default:
            http_response_code(405);
            break;
    }
}