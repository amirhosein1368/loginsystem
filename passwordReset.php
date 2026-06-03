<?php
declare(strict_types = 1);
// Twig file loaders
require_once "vendor/autoload.php";
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader('Presentation');
$twig = new Environment($loader);


spl_autoload_register();

use Business\UserService;
use Exceptions\UserBestaatNietException;

$userService = new UserService();
$errors = [];
if (
    isset($_GET["action"]) &&
    $_GET["action"] === "reset"
) {

    // VALUES
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $confirmPassword = trim($_POST["confirmPassword"] ?? "");
    if("" !== $email){
        try{
            $userService->emailAlBestaat($email);

        }catch(UserBestaatNietException $error){
            $errors['userBestaatNiet'] = true;
            print $twig->render('passwordResetForm.twig', array("errors" => $errors, "email" => $email, "password" => $password, "confirmPassword" => $confirmPassword));
            exit(0);
        }
    }
    // VALIDATIONS
    if (empty($email)) {
        $errors["email_empty"] = true;
    }

    if (empty($password)) {
        $errors["password_empty"] = true;
    }

    if (empty($confirmPassword)) {
        $errors["confirmPassword_empty"] = true;
    }

    if (
        !empty($password) &&
        !empty($confirmPassword) &&
        $password !== $confirmPassword
    ) {
        $errors["passwords_notMatch"] = true;
    }

    if (empty($errors)) {
            $userService->resetPassword(
                $email,
                $password
            );
            header('location:aanmelden.php');
            exit(0);
    }
    print $twig->render('passwordResetForm.twig', array("errors" => $errors, "email" => $email, "password" => $password, "confirmPassword" => $confirmPassword));
    exit();
}
print $twig->render('passwordResetForm.twig');
exit();