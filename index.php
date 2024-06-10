<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['search'])) {
        $searchTerm = $_POST['search'];
        $categoryFilter = isset($_POST['category']) ? $_POST['category'] : '';

        $sql = "SELECT * FROM quizzes WHERE title LIKE :search";

        if ($categoryFilter) {
            $sql .= " AND category = :category";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':search', '%' . $searchTerm . '%');

        if ($categoryFilter) {
            $stmt->bindValue(':category', $categoryFilter);
        }

        $stmt->execute();
        $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    $stmt = $conn->query("SELECT * FROM quizzes");
    $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Index</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2({
                ajax: {
                    url: 'search.php',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1
            });
        });
    </script>
</head>
<body>
<header>
    <h1>QuizzNight</h1>
    <form method="post" class="search-form">
        <input type="text" name="search" placeholder="Rechercher...">
        <select name="category">
            <option value="">Toutes catégories</option>
            <option value="pays">Pays</option>
            <option value="Foot">Foot</option>
            <option value="Musique">Musique</option>
        </select>
        <button type="submit">Rechercher</button>
    </form>
</header>

<body>
<h1>On en apprend tous les jours grâce à QuipoQuiz!</h1>

<div class="container">

    <div class="card-container">
        <?php foreach ($quizzes as $quiz): ?>
            <div class="card">
                <h2><?php echo htmlspecialchars($quiz['title']); ?></h2>
                <?php if (isset($quiz['description'])): ?>
                    <p><?php echo htmlspecialchars($quiz['description']); ?></p>
                <?php endif; ?>
                <?php if (isset($quiz['image'])): ?>
                    <img src="<?php echo htmlspecialchars($quiz['image']); ?>" alt="Quiz Image">
                <?php endif; ?>
                <div class="card-bottom">
                    <span class="plus">+</span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<footer>
    <p>© 2024 QuizNight. Tous droits réservés.</p>
</footer>
</body>
</html>
