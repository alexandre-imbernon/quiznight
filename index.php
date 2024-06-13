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
        }

        footer {
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

        h1 {
            text-align: center;
            font-size: 2rem;
        }

        .quiz-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            box-sizing: border-box; /* Assure que padding et margin sont inclus dans la taille totale */
        }

        .quiz {
            background: #FFF;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .quiz h3 {
            margin: 0 0 10px 0;
            font-size: 24px; /* Augmenter la taille de la police pour le titre */
        }

        .quiz img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .quiz ul {
            list-style-type: none;
            padding: 0;
        }

        .quiz ul ul {
            padding-left: 20px;
        }

        .quiz ul li {
            font-size: 18px;
            margin-bottom: 10px;
        }

        /* Media Queries pour rendre le design responsive */
        @media (max-width: 992px) {
            .quiz-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .header {
                flex-direction: column;
                height: auto;
            }

            .buttons {
                margin-top: 10px;
            }

            .logo {
                width: 90px;
                height: 90px;
            }

            .quiz {
                padding: 10px;
            }
        }

        @media (max-width: 600px) {
            .quiz-container {
                grid-template-columns: 1fr;
            }

            .header {
                padding: 10px;
            }

            .logo {
                width: 70px;
                height: 70px;
            }

            .buttons button {
                padding: 5px 10px;
                font-size: 14px;
            }

            .quiz h3 {
                font-size: 20px;
            }

            .quiz ul li {
                font-size: 16px;
            }
        }
    </style>
    <script>
        function redirectTo(url) {
            window.location.href = url;
        }
    </script>
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
            </div>
        </div>
    </header>
    <section>
        <h1>Quiz Index</h1>
        <div class="quiz-container">
            <?php foreach ($quizzes_data as $quiz_id => $quiz): ?>
                <div class="quiz">
                    <h3><?php echo htmlspecialchars($quiz['title']); ?></h3>
                    <?php if (!empty($quiz['cover_image'])): ?>
                        <img src="<?php echo htmlspecialchars($quiz['cover_image']); ?>" alt="<?php echo htmlspecialchars($quiz['title']); ?>">
                    <?php endif; ?>
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
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <footer>
        <div class="footer">
            <p>&copy; 2024 Quiz Night. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
