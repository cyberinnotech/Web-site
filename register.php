<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        echo "Veuillez remplir tous les champs.";
    } else {
        // FIX: Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                header("location: login.html?registration=success");
            } else {
                echo "Erreur. Il est possible que ce nom d'utilisateur ou email soit déjà pris.";
            }

            $stmt->close();
        } else {
            echo "Erreur lors de la préparation de la requête.";
        }
    }
    $conn->close();
}
?>
