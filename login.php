<?php
require_once('./classes/User.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $users = new User();

    $user = $users -> findByUsername($username);
    
    if ($user) {
        // Vérifiez le mot de passe
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username']; // Ajoutez cette ligne pour stocker le nom d'utilisateur
            header("Location: quizzes.php");
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "Username not found!";
    }
}
?>

<form method="post">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Login">
</form>