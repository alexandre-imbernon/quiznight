<?php
require_once('./config/DataBase.php');
require_once('./classes/Quiz.php');
require_once('./classes/Question.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$quiz = new Quiz();
$question = new Question();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username']; // Récupérer le nom d'utilisateur de la session

// Traitement du formulaire pour ajouter un quiz
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type']) && $_POST['form_type'] == 'add_quiz') {
    $title = $_POST['title'];
    $quiz -> create($user_id, $title);
}

// Traitement du formulaire pour ajouter une question
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type']) && $_POST['form_type'] == 'add_question') {
    $question_text = $_POST['question'];
    $quiz_id = $_POST['quiz_id']; // ID du quiz auquel la question appartient
    $answers = $_POST['answers']; // Tableau des réponses
    $correct_answer_index = $_POST['correct_answer']; // Index de la réponse correcte dans le tableau des réponses
    
    $question->create($quiz_id, $user_id, $question_text, $answers, $correct_answer_index - 1);
}

// Récupérer tous les quiz existants
$quizzes = $quiz->getAllQuizzes();

// Récupérer les quiz de l'utilisateur connecté
$user_quizzes = $quiz->getUserQuizzes($user_id);

$selected_quiz_id = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : null;

// Récupérer les questions et les réponses en relation avec le quiz sélectionné
if ($selected_quiz_id) {
    $quiz_data = $question->getQuizQuestionsAndAnswers($selected_quiz_id);
    $questions = $quiz_data['questions'];
    $answers = $quiz_data['answers'];
} else {
    $questions = [];
    $answers = [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Manager</title>
    <style>
        /* Réinitialisation des marges et paddings par défaut */
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow-x: hidden; /* Empêche le défilement horizontal */
        }

        /* Style global */
        @font-face {
            font-family: 'Neon';
            src: url('font/Neon.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            background: linear-gradient(45deg, #FF00FF, #00FFFF);
            font-family: 'Neon', sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        header, footer {
            width: 100%;
        }

        .header {
            background: #060A19;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box; /* Assure que padding est inclus dans la taille totale */
            height: 125px;
        }

        .footer {
            background: #060A19;
            padding: 10px 20px;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box; /* Assure que padding est inclus dans la taille totale */
            width: 100%;
            text-align: center;
            padding: 10px;
            background-color: #060A19;
            color: white;
            margin-top: auto; /* Permet au footer de se coller au bas de la page */
        }

        .logo {
            width: 115px;
            height: 115px;
        }

        .logo img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }

        a {
            text-decoration: none;
        }

        p {
            color: white;
        }

        h2{
            font-size: 2rem;
        }

        .buttons button {
            background-color: white;
            color: black;
            border: 2px solid #fff;
            border-radius: 20px; 
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 0;
            font-family: 'Neon', sans-serif;
        }

        .buttons button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.2);
            filter: blur(10px);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .buttons button:hover::before {
            opacity: 1;
        }

        .buttons button:hover {
            color: #000;
            background-color: #0ff;
            box-shadow: 0 0 20px #0ff, 0 0 40px #0ff, 0 0 60px #0ff, 0 0 80px #0ff;
            border-color: #0ff;
        }

        .logout-button {
            background-color: red;
            color: #fff;
            border: 2px solid red;
            border-radius: 20px; 
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 0;
            font-family: 'Neon', sans-serif;
        }

        .logout-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.2);
            filter: blur(10px);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .logout-button:hover::before {
            opacity: 1;
        }

        .logout-button:hover {
            color: #000;
            background-color: #f00; /* Neon red */
            box-shadow: 0 0 20px #f00, 0 0 40px #f00, 0 0 60px #f00, 0 0 80px #f00;
            border-color: #f00;
        }

        .quiz-container {
            display: flex;
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .column {
            flex: 1;
            padding: 10px;
            color: white;
        }

        .column h2 {
            margin-top: 0;
            font-size: 24px;
            border-bottom: 2px solid #fff;
            padding-bottom: 10px;
            text-align: center; /* Centre le titre */
        }

        .all-quizzes {
            border-right: 1px solid #ccc;
        }

        .your-quizzes {
            border-right: 1px solid #ccc; /* Ajouter une bordure droite */
            text-align: center; /* Centre tout le contenu de la colonne */
        }

        .your-quizzes form,
        .your-quizzes ul {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        ul {
            list-style: none;
            padding: 0;
            text-align: center; /* Centre les éléments de la liste */
        }

        ul li {
            padding: 10px;
            border-bottom: 1px solid #fff;
        }

        ul li:last-child {
            border-bottom: none;
        }

        ul li a {
            color: #00FFFF;
            text-decoration: none;
        }

        ul li a:hover {
            text-decoration: underline;
        }

        form {
            margin-bottom: 20px;
            text-align: center; /* Centrer le contenu du formulaire */
        }

        form label {
            display: block; /* Faire en sorte que les labels s'affichent sur une nouvelle ligne */
            margin: 10px 0 5px;
            font-size: 14px; /* Réduire la taille de la police des labels */
        }

        form input[type="text"], form textarea, form select {
            width: 80%; /* Réduire la largeur à 80% */
            padding: 8px; /* Réduire le padding */
            margin: 5px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-family: 'Neon', sans-serif;
            font-size: 14px; /* Réduire la taille de la police */
            display: inline-block; /* Pour centrer avec text-align: center */
        }

        form input[type="submit"] {
            background-color: #FF00FF;
            border: none;
            padding: 8px 16px; /* Réduire le padding */
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            color: black;
            font-size: 14px; /* Réduire la taille de la police */
            width: auto; /* Ajuster la largeur automatiquement */
            margin-top: 10px;
            font-family: 'Neon', sans-serif;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        form input[type="submit"]::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 255, 255, 0.2);
            filter: blur(10px);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
            border-radius: 5px;
        }

        form input[type="submit"]:hover::before {
            opacity: 1;
        }

        form input[type="submit"]:hover {
            background-color: #00FFFF;
            box-shadow: 0 0 20px #00FFFF, 0 0 40px #00FFFF, 0 0 60px #00FFFF, 0 0 80px #00FFFF; /* Ombres cyan pour l'effet néon */
        }

        .questions-answers ul {
            list-style: none;
            padding: 0;
        }

        .questions-answers ul li {
            margin-bottom: 20px;
        }

        .questions-answers ul li strong {
            display: block;
            margin-bottom: 5px;
        }

        .questions-answers ul li ul {
            padding-left: 20px;
        }

        .questions-answers ul li ul li {
            margin-bottom: 5px;
        }

        ul li img {
            width: 50px;
            height: auto;
            margin-left: 10px;
            vertical-align: middle;
        }

        .correct-answer {
            color: #00FF00; /* Vert néon pour la bonne réponse */
            font-weight: bold;
            background-color: rgba(0, 255, 0, 0.1); /* Légère couleur de fond verte */
            padding: 5px;
            border-radius: 5px;
        }

    </style>
</head>
<body>

<header>
    <div class="header">
        <div class="logo">
            <a href="index.php">
                <img src="images/quiz-night.webp" alt="Quiz Night">
            </a>
        </div>
        <div class="buttons">
            <button onclick="redirectTo('login.php')">LOGIN</button>
            <button onclick="redirectTo('register.php')">SIGN UP</button>
            <a href="index.php" class="logout-button">LOGOUT</a> <!-- Bouton pour se déconnecter -->
        </div>
    </div>
</header>

<h2>Bonjour <?php echo htmlspecialchars($username); ?>, bienvenue sur votre dashboard</h2>

<div class="quiz-container">
    <div class="column all-quizzes">
        <h2>Tous les Quizz</h2>
        <ul>
        <?php foreach ($quizzes as $quiz): ?>
            <li>
                <a href="?quiz_id=<?php echo $quiz['id']; ?>">
                    <?php echo htmlspecialchars($quiz['title']); ?>
                    <?php if (!empty($quiz['cover_image'])): ?>
                        <img src="<?php echo htmlspecialchars($quiz['cover_image']); ?>" alt="<?php echo htmlspecialchars($quiz['title']); ?>">
                    <?php endif; ?>
                </a>
                (Créé par <?php echo htmlspecialchars($quiz['username']); ?>)
            </li>
        <?php endforeach; ?>
        </ul>
    </div>

    <div class="column your-quizzes">
        <h2>Vos Quizz</h2>
        <form method="post">
            <input type="hidden" name="form_type" value="add_quiz">
            <label for="title">Titre:</label>
            <input type="text" id="title" name="title" required><br>
            <label for="cover_image">Image de couverture (URL):</label>
            <input type="text" id="cover_image" name="cover_image"><br>
            <input type="submit" value="Ajouter un Quizz">
        </form>

        <ul>
            <?php foreach ($user_quizzes as $quiz): ?>
                <li>
                    <div>
                        <strong><?php echo htmlspecialchars($quiz['title']); ?></strong>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="form_type" value="update_cover_image">
                            <input type="hidden" name="quiz_id" value="<?php echo $quiz['id']; ?>">
                            <label for="cover_image_<?php echo $quiz['id']; ?>">Image de couverture (URL):</label>
                            <input type="text" id="cover_image_<?php echo $quiz['id']; ?>" name="cover_image"><!-- Champ vide par défaut -->
                            <input type="submit" value="Mettre à jour">
                        </form>
                        <?php if (!empty($quiz['cover_image'])): ?>
                            <img src="<?php echo htmlspecialchars($quiz['cover_image']); ?>" alt="<?php echo htmlspecialchars($quiz['title']); ?>">
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ($selected_quiz_id && array_search($selected_quiz_id, array_column($user_quizzes, 'id')) !== false): ?>
            <h2>Ajouter une Question à <?php echo htmlspecialchars($user_quizzes[array_search($selected_quiz_id, array_column($user_quizzes, 'id'))]['title'] ?? ''); ?></h2>
            <form method="post">
                <input type="hidden" name="form_type" value="add_question">
                <input type="hidden" name="quiz_id" value="<?php echo $selected_quiz_id; ?>">
                <label for="question">Question:</label><br>
                <textarea id="question" name="question" rows="4" cols="50" required></textarea><br>
                
                <label for="answer1">Réponse 1:</label><br>
                <input type="text" id="answer1" name="answers[]" required><br>
                
                <label for="answer2">Réponse 2:</label><br>
                <input type="text" id="answer2" name="answers[]" required><br>
                
                <label for="answer3">Réponse 3:</label><br>
                <input type="text" id="answer3" name="answers[]" required><br>
                
                <label for="answer4">Réponse 4:</label><br>
                <input type="text" id="answer4" name="answers[]" required><br>
                
                <label for="correct_answer">Réponse Correcte:</label><br>
                <select id="correct_answer" name="correct_answer" required>
                    <option value="1">Réponse 1</option>
                    <option value="2">Réponse 2</option>
                    <option value="3">Réponse 3</option>
                    <option value="4">Réponse 4</option>
                </select><br>
                
                <input type="submit" value="Ajouter la Question">
            </form>
        <?php endif; ?>
    </div>

    <div class="column questions-answers">
        <?php if ($selected_quiz_id): ?>
            <h2>Questions et Réponses pour 
            <?php 
                $quizIndex = array_search($selected_quiz_id, array_column($user_quizzes, 'id'));
                echo htmlspecialchars($quizIndex !== false ? $user_quizzes[$quizIndex]['title'] : '');
            ?></h2>
            <ul>
                <?php foreach ($questions as $question): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($question['question_text']); ?></strong>
                        <ul>
                            <?php foreach ($answers as $answer): ?>
                                <?php if ($answer['question_id'] == $question['id']): ?>
                                    <li class="<?php echo $answer['is_correct'] ? 'correct-answer' : ''; ?>">
                                        <?php echo htmlspecialchars($answer['answer_text']); ?>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

<footer>
    <div class="footer">
        <p>&copy; 2024 Quiz Night. Tous droits réservés.</p>
    </div>
</footer>
</body>
</html>

