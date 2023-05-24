<?php
require 'config.php';

try {
    $stmt = $pdo->prepare("SELECT id, title, body, (SELECT COUNT(*) FROM Answers WHERE question_id = Questions.id) > 0 AS answered FROM Questions");
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($questions);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>