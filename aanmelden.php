<?php 
declare(strict_types = 1);
spl_autoload_register();

require_once "vendor/autoload.php";
use Twig\Loader\FilesystemLoader; 
use Twig\Environment;

use Business\UserService;
use Exceptions\PasswordIncorrectException;
use Exceptions\UserBestaatNietException;
// session_unset();

$loader = new FilesystemLoader('Presentation');
$twig = new Environment($loader);

if(isset($_GET['action']) && 'aanmelden' === $_GET['action']){
    $userService = new UserService();
    $errors = array();
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");
    if(empty($email)){
        $errors['email_empty'] = true;
    } 
    if(empty($password)){
        $errors['password_empty'] = true;
    }
    if(!empty($errors)){
        print $twig->render('loginForm.twig');
        exit();
    }
    try{
        $credentials = array('email' => $email,'password' => $password);
        $userService->userAanmelden($credentials);
        header('location:toonGeheim.php');
        exit(0);
    }catch(PasswordIncorrectException $error){
        $errors["passwordIncorrect"] = true;
        print $twig->render('loginForm.twig', array("errors" => $errors,'email' => $email, 'password' => $password));   
        exit();     
    }catch(UserBestaatNietException $error){
        $errors["login_failed"] = true;
        print $twig->render('loginForm.twig', array("errors" => $errors,'email' => $email, 'password' => $password));
        // include("Presentation/loginForm.php");   
        exit();
    }

}

print $twig->render('loginForm.twig');