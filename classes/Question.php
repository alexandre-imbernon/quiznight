<?php
require_once('./config/DataBase.php');

class Question {
    private $db;

    public function __construct() {
        $this->db = (new DataBase())->getConnection();
    }

    // Récupérer toutes les questions et réponses pour un quiz spécifique
    public function getQuizQuestionsAndAnswers($quiz_id) {
        // Récupérer les questions
        $stmt = $this->db->prepare("SELECT * FROM questions WHERE quiz_id = :quiz_id");
        $stmt->bindParam(':quiz_id', $quiz_id, PDO::PARAM_INT);
        $stmt->execute();
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Récupérer les réponses
        $stmt = $this->db->prepare("SELECT * FROM answers WHERE question_id IN (SELECT id FROM questions WHERE quiz_id = :quiz_id)");
        $stmt->bindParam(':quiz_id', $quiz_id, PDO::PARAM_INT);
        $stmt->execute();
        $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['questions' => $questions, 'answers' => $answers];
    }

    // Insérer la question dans la table 'questions' et ses réponses dans la table 'answers'
    public function create($quiz_id, $user_id, $question_text, $answers, $correct_answer_index) {
        try {
            // Commencer une transaction
            $this->db->beginTransaction();

            // Insérer la question
            $stmt = $this->db->prepare("INSERT INTO questions (quiz_id, user_id, question_text) VALUES (:quiz_id, :user_id, :question_text)");
            $stmt->bindParam(':quiz_id', $quiz_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':question_text', $question_text, PDO::PARAM_STR);
            $stmt->execute();

            // Obtenir l'ID de la question insérée
            $question_id = $this->db->lastInsertId();

            // Insérer les réponses associées
            $stmt = $this->db->prepare("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (:question_id, :answer_text, :is_correct)");
            
            foreach ($answers as $index => $answer) {
                $is_correct = ($index == $correct_answer_index) ? 1 : 0; // Vérifie si l'index de la réponse correspond à la réponse correcte
                $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
                $stmt->bindParam(':answer_text', $answer, PDO::PARAM_STR);
                $stmt->bindParam(':is_correct', $is_correct, PDO::PARAM_INT);
                $stmt->execute();
            }

            // Confirmer la transaction
            $this->db->commit();
            echo "Question ajoutée avec succès !";
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $this->db->rollBack();
            echo "Une erreur s'est produite lors de l'ajout de la question : " . $e->getMessage();
        }
    }
}