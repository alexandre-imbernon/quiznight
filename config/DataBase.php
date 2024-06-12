<?php
// DataBase.php
class DataBase {
    public $connection;

    public function getConnection() {
        $servername = "localhost";
        $dbname = "quiznight";
        $username = "root";
        $password = "";


        try {
            $this -> connection = new PDO("mysql:host={$servername};dbname={$dbname}", $username, $password);
            $this -> connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection failed: " . $e -> getMessage();
        }

        return($this -> connection);
    }
}
