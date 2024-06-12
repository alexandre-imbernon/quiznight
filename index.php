<?php
require_once('./classes/Quiz.php');

$quiz = new Quiz();
$quiz_questions_answers = $quiz -> read();

// Organiser les données par quiz
$quizzes_data = [];
foreach ($quiz_questions_answers as $qa) {
    $quizzes_data[$qa['quiz_id']]['title'] = $qa['quiz_title'];
    $quizzes_data[$qa['quiz_id']]['questions'][$qa['question_id']]['question_text'] = $qa['question_text'];
    $quizzes_data[$qa['quiz_id']]['questions'][$qa['question_id']]['answers'][] = [
        'answer_text' => $qa['answer_text'],
        'is_correct' => $qa['is_correct']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Index</title>
    <style>
        .login-button {
            display: inline-block;
            padding: 10px 20px;
            margin-bottom: 20px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .login-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Quiz Index</h1>

    <a href="login.php" class="login-button">Login</a> <!-- Bouton pour accéder à la page de connexion -->

    <ul>
        <?php foreach ($quizzes_data as $quiz_id => $quiz): ?>
            <li>
                <h3><?php echo htmlspecialchars($quiz['title']); ?></h3>
                <ul>
                    <?php foreach ($quiz['questions'] as $question): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($question['question_text']); ?></strong><br>
                            <ul>
                                <?php foreach ($question['answers'] as $answer): ?>
                                    <li>
                                        <?php echo htmlspecialchars($answer['answer_text']); ?>
                                        <?php if ($answer['is_correct']): ?>
                                            (Correct)
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>

