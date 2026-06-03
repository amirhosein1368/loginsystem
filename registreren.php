<?php 
declare(strict_types = 1);
spl_autoload_register();

use Business\UserService;
use Exceptions\UserBestaatException;

require_once "vendor/autoload.php";

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader('Presentation');
$twig = new Environment($loader);

$userService = new UserService();
if(isset($_GET['action']) && 'registreren' === $_GET['action']){
    $naam = trim($_POST["naam"] ?? "");
    $familienaam = trim($_POST["familienaam"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $confirmPassword = trim($_POST['confirmPassword'] ?? "");
    $values = array(
        "naam" => $naam,
        "familienaam" => $familienaam,
        "email" => $email,
        "password" => $password,
        "confirmPassword" => $confirmPassword 
       );
    $errors = array();
    if(empty($naam)){
        $errors['naam_empty'] = true;
    }
    if(empty($familienaam)){
        $errors['familienaam_empty'] = true;
    }
    if(empty($email)){
        $errors['email_empty'] = true;
    }
    if(empty($password)){
        $errors['password_empty'] = true;
    }
    if(empty($confirmPassword)){
        $errors['confirmPassword_empty'] = true;
    }
    if($password !== $confirmPassword){
        $errors['passwords_notMatch'] = true;
    }
    if(!empty($errors)){
        print $twig->render('registratieForm.twig', array('errors' => $errors, "values" => $values));
        exit(0);
    }
    try{
        $userService->voegUserToe(array(
            "naam" => $naam,
            "familienaam" => $familienaam,
            "email" => $email,
            "password" => $password
            ));
        header('location:aanmelden.php');
        exit(0);

    }catch(UserBestaatException $error){
        $errors['emailBestaat'] = true;
        print $twig->render('registratieForm.twig', array('errors' => $errors, "values" => $values));
        // include("Presentation/registratieForm.php");
        exit(0);
    }

}else{
    print $twig->render('registratieForm.twig');
    exit(0);
}


