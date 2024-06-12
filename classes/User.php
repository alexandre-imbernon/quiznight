<?php
require_once('./config/DataBase.php');

class User {
    private $db;

    public function __construct() {
        $this -> db = (new DataBase()) -> getConnection();
    }

    // Rechercher l'utilisateur par nom d'utilisateur
    public function findByUsername($username) {
        $stmt = $this -> db -> prepare("SELECT * FROM users WHERE username = :username");
        $stmt -> bindParam(':username', $username);
        
        $stmt -> execute();
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);

        return($result);
    }

    public function register($username, $password) {
        $stmt = $this -> db -> prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        
        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $stmt->errorInfo()[2];
        }
    }
}