<?php
class Router
{
    public function route()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 'home';
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';

        switch ($page) {
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
            case 'profile':
                require 'controllers/UserController.php';
                $controller = new UserController();
                $controller->handle();
                break;
            case 'admin':
                require 'controllers/AdminController.php';
                $controller = new AdminController();
                $controller->handle();
                break;
            case 'terms':
                require 'views/terms.php';
                break;
            case 'privacy':
                require 'views/privacy.php';
                break;
            case 'payments':
                require_once 'controllers/PaymentsController.php';

            $paymentsController = new PaymentsController();
            $action = $_GET['action'] ?? 'pay';
    
    switch ($action)
    {
        case 'pay':
            $paymentsController->pay();
            break;
        case 'process':
            $paymentsController->process();
            break;
        case 'pending':
            $paymentsController->pending();
            break;
        case 'checkStatus':
            $paymentsController->checkStatus();
            break;
        case 'success':
            $paymentsController->success();
            break;
        case 'cancel':
            $paymentsController->cancel();
            break;
        case 'manualComplete':
            $paymentsController->manualComplete();
            break;
        default:
            $paymentsController->pay();
    }
    break;
            case 'promotions':
                require 'views/promotions.php';
                break;
            default:
                require 'views/404.php';
        }
    }
}