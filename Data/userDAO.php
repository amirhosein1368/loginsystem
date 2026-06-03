<?php
declare(strict_types = 1);
namespace Data;

use \PDO;
use Entities\User;
use Exceptions\OngeldigEmailadresException;
use Exceptions\PasswordsKommenNietOverEenException;
use Exceptions\UserBestaatException;
use Exceptions\UserBestaatNietException;
use Exceptions\PasswordIncorrectException;

class UserDAO{

    private ?PDO $DBconnection; 

    public function __construct(){
        $this->DBconnection = new PDO(DBconfig::$connectionString, DBconfig::$DBuser, DBconfig::$DBpassword);
    }
    public function __destruct(){
        $this->DBconnection = null;
    }

    public function create(string $naam, string $familienaam, string $email, string $password):User{
        
        if($this->isEmailTaken((string)$email)){
            throw new UserBestaatException();
        }
        $sql = "INSERT INTO users (naam, familienaam, email, password) VALUES (:naam, :familienaam, :email, :password)";
        $statement = $this->DBconnection->prepare($sql);
        $statement->execute(array   (":naam" => $naam, ":familienaam" => $familienaam, ":email" => $email, ":password" => $password));
        $userId = $this->DBconnection->lastInsertId();
        return $this->getById((int)$userId);
    }

    public function resetPassword(string $email, string $password){
        
        if(!$this->isEmailTaken((string)$email)){
            throw new UserBestaatNietException();
        }
        $password = password_hash($password,PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password= :password 
        WHERE email= :email;";
        $statement = $this->DBconnection->prepare($sql);
        $statement->execute(array(":email" => $email, ":password" => $password));
        return $statement->rowCount();
    }

    public function getUsersLijst(){
        $sql = "SELECT * FROM users";
        $rijen = $this->DBconnection->query($sql);
        $users = [];
        foreach($rijen as $rij){
            $users[] = new User($rij['id'],$rij['naam'],$rij['familienaam'],$rij['email'],$rij['password']);
        }
        
        return $users;
    }

    public function getById(int $id){
        $sql = "SELECT * FROM users 
        WHERE id = :id";
        $statement = $this->DBconnection->prepare($sql);
        $statement->execute([':id' => $id]);
        $rij = $statement->fetch(PDO::FETCH_OBJ);
        $user = new User(
                $rij->id,
                $rij->naam,
                $rij->familienaam,
                $rij->email,
                $rij->password
                );
        return $user;
    }

    public function getUserByEmailAndPassword(string $email, string $password):?User{
        $sql = "SELECT * FROM users
        WHERE email = :email";
        $statement = $this->DBconnection->prepare($sql);
        $statement->execute([':email' => $email]);
        $rij = $statement->fetch(PDO::FETCH_OBJ);
        
        if(!empty($rij)){
            if(!password_verify($password, $rij->password)){
                throw new PasswordIncorrectException();
            }else{
            $user = new User(
                $rij->id,
                $rij->naam,
                $rij->familienaam,
                $rij->email,
                $rij->password
                );
            }
        }else{
            throw new UserBestaatNietException();
        }
        return $user;
    }

    public function getByEmail(string $email):?User{
        $sql = "SELECT * FROM users 
        WHERE  email = :email";
        $statement = $this->DBconnection->prepare($sql);
        $statement->execute(array(":email" => $email));
        $rij = $statement->fetch(PDO::FETCH_OBJ);
        
        if(!$rij){
            throw new UserBestaatNietException();
        }else {
            $user = User::create(
                (int)$rij->id, 
                (string)$rij->naam,
                (string)$rij->familienaam, 
                (string)$rij->email, 
                (string)$rij->password
                );
            return $user;
        }
    }
    public function isEmailTaken(string $email){
        $sql = "SELECT * FROM users 
        WHERE  email = :email";
        $statement = $this->DBconnection->prepare($sql);
        $statement->execute(array(":email" => $email));
        // $rij = $statement->fetch(PDO::FETCH_OBJ);

        if(0 === $statement->rowCount()){
            $emailAlBestaat = false;
        }else{
            $emailAlBestaat = true;
        }
        return $emailAlBestaat;
    }

}