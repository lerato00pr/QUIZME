<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_question'])) {
    // Insert a new question into the database
    $question = $_POST['question'];
    $stmt = $pdo->prepare("INSERT INTO questions (student_id, question) VALUES (?, ?)");
    $stmt->execute([$user_id, $question]);
}

// Clear question and answer logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_question_id'])) {
    $question_id = $_POST['delete_question_id'];

    // Delete the answer associated with the question
    $stmt = $pdo->prepare("DELETE FROM answers WHERE question_id = ?");
    $stmt->execute([$question_id]);

    // Delete the question
    $stmt = $pdo->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->execute([$question_id]);
}

// Fetching the student's questions along with answers
$questions = $pdo->query("
    SELECT q.id, q.question, u.username AS student_name, a.answer 
    FROM questions q 
    JOIN users u ON q.student_id = u.id 
    LEFT JOIN answers a ON q.id = a.question_id
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
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
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .card {
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Student Dashboard</h2>

        <form method="POST">
            <div class="form-group">
                <label for="question">Ask a Question</label>
                <textarea name="question" id="question" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" name="submit_question" class="btn btn-primary btn-block">Submit Question</button>
        </form>

        <h3 class="mt-5">Your Questions</h3>
        <?php foreach ($questions as $q): ?>
            <div class="card">
                <div class="card-body">
                    <p><strong>Student:</strong> <?= htmlspecialchars($q['student_name']) ?></p>
                    <p><strong>Question:</strong> <?= htmlspecialchars($q['question']) ?></p>
                    <?php if ($q['answer']): ?>
                        <p><strong>Answer:</strong> <?= htmlspecialchars($q['answer']) ?></p>
                    <?php else: ?>
                        <p><em>No answer yet.</em></p>
                    <?php endif; ?>
                    <form method="POST" class="mt-3">
                        <input type="hidden" name="delete_question_id" value="<?= $q['id'] ?>">
                        <button type="submit" class="btn btn-danger">Delete Question</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(event) {
                if (this.querySelector('button[type="submit"]').classList.contains('btn-danger')) {
                    const confirmation = confirm("Are you sure you want to delete this question?");
                    if (!confirmation) {
                        event.preventDefault();
                    }
                }
            });
        });
    </script>
</body>
</html>
