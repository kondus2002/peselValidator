<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Classes\PeselValidator;
use Slim\App;
use Slim\Views\PhpRenderer;
use \App\Classes\Employees;

require '../classes/PeselValidator.php';
require '../classes/Employees.php';
require '../vendor/autoload.php';

$path = "/iwq/src/public";

$config['displayErrorDetails'] = true; // only in dev mode
$config['addContentLengthHeader'] = false;

$config['db']['host']   = 'localhost';
$config['db']['user']   = 'root';
$config['db']['pass']   = '';
$config['db']['dbname'] = 'iwq';

$app = new App(['settings' => $config]);

$container = $app->getContainer();

$container['view'] = new PhpRenderer('../templates/');

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$container['path'] = function ($c) use ($path) {
    return $path;
};

$app->get('/', function (Request $request, Response $response) {
    return $this->view->render($response, 'index.phtml');
});

$app->post('/submit', function (Request $request, Response $response) {
    $path = $this->get('path');
    $pesel = $request->getParam('pesel');

    $validator = new PeselValidator();
    $result = $validator->validatePesel($pesel);

//    if($result!=true){
//        return $response->write("Numer PESEL może składać się wyłącznie z cyfr i musi wynosić 11 znaków");
//    }


    return $response->withRedirect($path."/validate/$pesel");
});

$app->get('/validate/{pesel}', function (Request $request, Response $response, $args){
    $pesel = $args['pesel'];
    $validator = new PeselValidator();
    $result = $validator->validatePesel($pesel);

//    if($result!=true){
//        return $response->write("Numer PESEL może składać się wyłącznie z cyfr i musi wynosić 11 znaków");
//    }

    return $this->view->render($response, 'peselValidation.phtml', [
        'result' => $result, 'pesel' => $pesel
    ]);
});

$app->get('/employee/add', function (Request $request, Response $response) {

    return $this->view->render($response, 'addEmployee.phtml');
});


// Employee create
$app->post('/employee/add', function (Request $request, Response $response) {

    $input = $request->getParsedBody();

    $validator = new PeselValidator();
    $result = $validator->validatePesel($input['pesel']);

    if($result!=true){
        return $response->write("Numer PESEL jest niepoprawny!");
    }

    $stmt = $this->db->prepare("INSERT INTO pracownicy (pesel, imie, nazwisko, adres) VALUES (?, ?, ?, ?)");
    $stmt->execute([$input['pesel'], $input['firstName'], $input['lastName'], $input['address']]);

    return $response->withRedirect($this->get('path') . "/employees");
});


// Employee read
$app->get('/employees', function (Request $request, Response $response) {

    $stmt = $this->db->prepare("SELECT * FROM pracownicy");
    $stmt->execute();
    $data = $stmt->fetchAll();

    $employees = new Employees();
    $result = $employees->getAllEmployees($data);

    return $this->view->render($response, 'employees.phtml', [
        'data' => $result
    ]);
});

//Employee update
$app->post('/employee/update/{pesel}', function (Request $request, Response $response, $args) {

    $input = $request->getParsedBody();
    $pesel = $args['pesel'];
    $stmt = $this->db->prepare("UPDATE pracownicy SET imie = ?, nazwisko = ?, adres = ? WHERE pesel = ?");
    $stmt->execute([$input['firstName'], $input['lastName'], $input['address'], $pesel]);

    $path = $this->get('path');

    return $response->withRedirect($path."/employees");
});


//Employee delete
$app->post('/employee/delete/{pesel}', function (Request $request, Response $response, $args) {

    $pesel = $args['pesel'];
    $stmt = $this->db->prepare("DELETE FROM pracownicy WHERE pesel = ?");
    $stmt->execute([$pesel]);

    $path = $this->get('path');

    return $response->withRedirect($path."/employees");
});

$app->get('/employee/update/{pesel}', function (Request $request, Response $response, $args) {

    $pesel = $args['pesel'];

    $stmt = $this->db->prepare("SELECT * FROM pracownicy WHERE pesel = ?");
    $stmt->execute([$pesel]);
    $data = $stmt->fetchAll();

    return $this->view->render($response, 'employeeUpdate.phtml', [
        'data' => $data
    ]);
});

$app->run();