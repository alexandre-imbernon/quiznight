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
        .container {
            display: flex;
        }
        .column {
            flex: 1;
            padding: 10px;
        }
        .all-quizzes {
            border-right: 1px solid #ccc;
            min-width: 300px;
        }
        .main-content {
            flex: 2;
            padding: 10px;
        }
        .main-content .section {
            margin-bottom: 20px;
        }
        .logout-button {
            display: inline-block;
            padding: 10px 20px;
            margin-bottom: 20px;
            background-color: #dc3545;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .logout-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<h2>Bonjour <?php echo htmlspecialchars($username); ?>, bienvenue sur votre dashboard</h2>

<a href="logout.php" class="logout-button">Logout</a> <!-- Bouton pour se déconnecter -->

<div class="container">
    <div class="column all-quizzes">
        <h2>Tous les Quizz</h2>
        <ul>
            <?php foreach ($quizzes as $quiz): ?>
                <li>
                    <a href="?quiz_id=<?php echo $quiz['id']; ?>"><?php echo htmlspecialchars($quiz['title']); ?></a>
                    (Créé par <?php echo htmlspecialchars($quiz['username']); ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="main-content">
        <div class="section your-quizzes">
            <h2>Vos Quizz</h2>
            <form method="post">
                <input type="hidden" name="form_type" value="add_quiz">
                Title: <input type="text" name="title" required><br>
                <input type="submit" value="Add Quiz">
            </form>

            <ul>
                <?php foreach ($user_quizzes as $quiz): ?>
                    <li><a href="?quiz_id=<?php echo $quiz['id']; ?>"><?php echo htmlspecialchars($quiz['title']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <?php if ($selected_quiz_id && array_search($selected_quiz_id, array_column($user_quizzes, 'id')) !== false): ?>
            <div class="section add-question">
                <h2>Add a Question to <?php echo htmlspecialchars($user_quizzes[array_search($selected_quiz_id, array_column($user_quizzes, 'id'))]['title']); ?></h2>
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
                    
                    <input type="submit" value="Add Question">
                </form>
            </div>

            <div class="section questions-answers">
                <h2>Questions and Answers for <?php echo htmlspecialchars($user_quizzes[array_search($selected_quiz_id, array_column($user_quizzes, 'id'))]['title']); ?></h2>
                <ul>
                    <?php foreach ($questions as $question): ?>
                        <li>
                            <?php echo htmlspecialchars($question['question_text']); ?>
                            <ul>
                                <?php foreach ($answers as $answer): ?>
                                    <?php if ($answer['question_id'] == $question['id']): ?>
                                        <li <?php if ($answer['is_correct']) echo 'style="font-weight:bold;"'; ?>>
                                            <?php echo htmlspecialchars($answer['answer_text']); ?>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
