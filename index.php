<?php
include 'config.php';

// Récupérer la liste des quiz existants 
$stmt = $conn->prepare("SELECT quizzes.id AS quiz_id, quizzes.title AS quiz_title, questions.id AS question_id, questions.question_text, answers.id AS answer_id, answers.answer_text, answers.is_correct FROM quizzes JOIN questions ON quizzes.id = questions.quiz_id JOIN answers ON questions.id = answers.question_id");
$stmt->execute();
$quiz_questions_answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

<h2>Quizzes et Questions</h2>
<?php foreach ($quizzes_data as $quiz_id => $quiz): ?>
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
<?php endforeach; ?>
