<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'lecturer') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question_id = $_POST['question_id'];
    $answer = $_POST['answer'];

    $stmt = $pdo->prepare("INSERT INTO answers (question_id, lecturer_id, answer) VALUES (?, ?, ?)");
    $stmt->execute([$question_id, $user_id, $answer]);
}

// Fetch unanswered questions
$questions = $pdo->query("
    SELECT q.id, q.question, u.username AS student_name 
    FROM questions q 
    JOIN users u ON q.student_id = u.id 
    LEFT JOIN answers a ON q.id = a.question_id 
    WHERE a.answer IS NULL
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2, h3 {
            color: #343a40;
        }
        .card {
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Lecturer Dashboard</h2>

        <h3 class="mt-4">Unanswered Questions</h3>
        <?php if (count($questions) > 0): ?>
            <?php foreach ($questions as $q): ?>
                <div class="card">
                    <div class="card-body">
                        <p><strong>Student:</strong> <?= htmlspecialchars($q['student_name']) ?></p>
                        <p><strong>Question:</strong> <?= htmlspecialchars($q['question']) ?></p>
                        <form method="POST">
                            <div class="form-group">
                                <label>Your Answer</label>
                                <textarea name="answer" class="form-control" rows="4" required></textarea>
                            </div>
                            <input type="hidden" name="question_id" value="<?= $q['id'] ?>">
                            <button type="submit" class="btn btn-success">Submit Answer</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                No unanswered questions at the moment.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
