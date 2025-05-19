<?php
include 'db.php';
session_start();

// Registration logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['reg_username'];
    $password = password_hash($_POST['reg_password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $role]);
    $registration_success = "User registered successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Register</h2>
        <?php if (isset($registration_success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($registration_success) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="reg_username">Username</label>
                <input type="text" name="reg_username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="reg_password">Password</label>
                <input type="password" name="reg_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" class="form-control" required>
                    <option value="" disabled selected>Select your role</option>
                    <option value="student">Student</option>
                    <option value="lecturer">Lecturer</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success btn-block">Register</button>
        </form>
        <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
