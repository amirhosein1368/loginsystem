<?php 
declare(strict_types = 1);

require_once "vendor/autoload.php";
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader('Presentation');
$twig = new Environment($loader);

spl_autoload_register();

use Business\UserService;

$userService = new UserService();

if(isset($_SESSION['isIngelogd']) && true === $_SESSION['isIngelogd']){
    print $twig->render('geheimeinformatie.twig');
}else{
    header('location: aanmelden.php');
    exit(0);
}