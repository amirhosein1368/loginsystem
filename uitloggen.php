<?php 
declare(strict_types = 1);
spl_autoload_register();

use Business\UserService;

require_once "vendor/autoload.php";
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader('Presentation');
$twig = new Environment($loader);


$userService = new UserService();
$userService->userUitloggen();
header('location: aanmelden.php');
exit(0);