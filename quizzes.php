<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title'])) {
    $title = $_POST['title'];

    $stmt = $conn->prepare("INSERT INTO quizzes (user_id, title) VALUES (:user_id, :title)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':title', $title);
    $stmt->execute();
}

$stmt = $conn->prepare("SELECT * FROM quizzes WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Your Quizzes</h2>
<form method="post">
    Title: <input type="text" name="title" required><br>
    <input type="submit" value="Add Quiz">
</form>

<ul>
    <?php foreach ($quizzes as $quiz): ?>
        <li><?php echo htmlspecialchars($quiz['title']); ?></li>
    <?php endforeach; ?>
</ul>
