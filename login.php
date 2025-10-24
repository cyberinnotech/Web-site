<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // FIX: Use prepared statement to prevent SQL Injection
    $sql = "SELECT id, username, password, is_admin FROM users WHERE username = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // FIX: Verify hashed password
            if (password_verify($password, $user['password'])) {
                // Password is correct, start a new session
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = $user['is_admin'];

                if ($user['is_admin']) {
                    header("location: admin.php");
                } else {
                    header("location: workspace.php");
                }
            } else {
                // Password is not valid
                echo "Nom d'utilisateur ou mot de passe incorrect.";
            }
        } else {
            // Username doesn't exist
            echo "Nom d'utilisateur ou mot de passe incorrect.";
        }
        $stmt->close();
    } else {
        echo "Erreur lors de la préparation de la requête.";
    }
}
$conn->close();
?>