<?php 
declare(strict_types = 1);
spl_autoload_register();
use Business\UserService;
use Data\UserDAO;
use Exceptions\Exceptions;
// require_once("Business/userService.php");
// require_once("Data/userDAO.php");
// require_once("Exceptions/Exceptions.php");
// require_once("Business/userService.php");
// require_once("Business/userService.php");
// $userDAO = new UserDAO();
// $users = $userDAO->getUserByUsernameAndPassword('amirhosein','amirhosein');
// var_dump($users);
$usersrvc = new UserService();
$usersrvc->userAanmelden(array('email'=>'user2@gmail.com', 'password'=>'12345'));
// var_dump($users);