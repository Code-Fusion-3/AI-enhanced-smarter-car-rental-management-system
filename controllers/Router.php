<?php
class Router {
    public function route() {
        $page = isset($_GET['page']) ? $_GET['page'] : 'home';
        
        switch($page) {
            case 'home':
                require 'views/home.php';
                break;
            case 'cars':
                require 'controllers/CarController.php';
                $controller = new CarController();
                $controller->handle();
                break;
            case 'rentals':
                require 'controllers/RentalController.php';
                $controller = new RentalController();
                $controller->handle();
                break;
            case 'auth':
                require 'controllers/AuthController.php';
                $controller = new AuthController();
                $controller->handle();
                break;
            default:
                require 'views/404.php';
        }
    }
}
