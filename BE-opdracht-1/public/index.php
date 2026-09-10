<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../app/models/Magazijn.php';
require_once __DIR__ . '/../app/controllers/MagazijnController.php';


$database = new Database();
$db = $database->connect();

$magazijnModel = new Magazijn($db);

$controller = new MagazijnController($magazijnModel);


$page = $_GET['page'] ?? 'home';


switch ($page) {

    case 'magazijn':
        $controller->index();
        break;


    case 'levering':

        $productId = isset($_GET['id'])
            ? (int) $_GET['id']
            : 0;

        $controller->levering($productId);

        break;


    case 'allergenen':

        $productId = isset($_GET['id'])
            ? (int) $_GET['id']
            : 0;

        $controller->allergenen($productId);

        break;


    default:
        require __DIR__ . '/../app/views/home.php';
        break;
}