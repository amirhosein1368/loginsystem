<?php
declare(strict_types=1);
namespace Entities;
class User
{
    private int $id;
    private string $naam;
    private string $familienaam;
    private string $email;
    private string $password;

    public function __construct(
        int $id,
        string $naam,
        string $familienaam,
        string $email,
        string $password
    ) {
        $this->id = $id;
        $this->naam = $naam;
        $this->familienaam = $familienaam;
        $this->email = $email;
        $this->password = $password;
    }

    public static function create(
        int $id,
        string $naam,
        string $familienaam,
        string $email,
        string $password
    ): User {
        return new User(
            $id,
            $naam,
            $familienaam,
            $email,
            $password
        );
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getNaam(): string
    {
        return $this->naam;
    }

    public function getFamilienaam(): string
    {
        return $this->familienaam;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function setPassword($password){
        $this->password = $password; 
    }
    public function setEmail($email){
        $this->email = $email;
    }   
}