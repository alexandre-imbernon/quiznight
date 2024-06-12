<?php
require_once('./config/DataBase.php');

class Quiz {
    private $db;

    public function __construct() {
        $this -> db = (new DataBase()) -> getConnection();
    }

    // Récupérer la liste des quiz existants 
    public function read() {
        $stmt = $this -> db -> prepare("SELECT quizzes.id AS quiz_id, quizzes.title AS quiz_title, questions.id AS question_id, questions.question_text, answers.id AS answer_id, answers.answer_text, answers.is_correct FROM quizzes JOIN questions ON quizzes.id = questions.quiz_id JOIN answers ON questions.id = answers.question_id");
        $stmt -> execute();
        $quiz_questions_answers = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        return($quiz_questions_answers);
    }

    public function create($user_id, $title) {
        $stmt = $this -> db -> prepare("INSERT INTO quizzes (user_id, title) VALUES (:user_id, :title)");
        $stmt -> bindParam(':user_id', $user_id);
        $stmt -> bindParam(':title', $title);
        $stmt -> execute();
    }

    // Récupérer tous les quiz existants
    public function getAllQuizzes() {
        $stmt = $this->db->prepare("SELECT quizzes.*, users.username FROM quizzes INNER JOIN users ON quizzes.user_id = users.id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les quiz d'un utilisateur spécifique
    public function getUserQuizzes($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM quizzes WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}