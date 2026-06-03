<?php
declare(strict_types = 1);
namespace Business;
use Data\UserDAO;
use Exceptions\UserBestaatNietException;
// require_once("Data/userDAO.php");

class UserService{
    private string $email;
    private string $password;
    public function __construct(){
        session_start();
    }
    public function isCredentialsOke(array $credentials):bool{

        $userDAO = new UserDAO();
        $user = $userDAO->getUserByEmailAndPassword($credentials['email'], $credentials['password']);  
        if(null === $user){
            return false;
        }else{
            return true;
        }
    }
    public function userAanmelden(array $credentials){
        $userDAO = new UserDAO();
        $user = $userDAO->getUserByEmailAndPassword($credentials['email'], $credentials['password']);
        $user->setPassword('');
        $_SESSION['user'] = serialize($user);
        $_SESSION['isIngelogd'] = true;
        // var_dump($_SESSION);
    }
    public function userUitloggen(){
        // session_unset();
        unset($_SESSION['user'],
        $_SESSION['isIngelogd']
        );
    }
    public function voegUserToe(array $userData){
        $userDAO = new UserDAO();
        $user = $userDAO->create(
            $userData['naam'],
            $userData['familienaam'],
            $userData['email'],
            $userData['password']
            );
        return $user;
    }
    public function resetPassword(string $email, string $password){
         $userDAO = new UserDAO();
         $aantalRij = $userDAO->resetPassword($email, $password);
         return $aantalRij;
    }
    public function emailAlBestaat(string $email):bool{
        $userDAO = new UserDAO();
        $emailAlBestaat = $userDAO->isEmailTaken($email);
        if(!$emailAlBestaat){
            throw new UserBestaatNietException();
        }
        return true;
    
    }
}