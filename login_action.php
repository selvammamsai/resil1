<?php
include('connection.php');
session_start();

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the user exists in the database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // If a user with this username is found
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start a session and redirect to welcome page
            $_SESSION['username'] = $username;
            header("Location: welcome.php");
            exit();
        } else {
			
            // Incorrect password
            
			echo "<script>alert('Invalid username or password.'); window.location.href = 'login.php';</script>";
			exit(); // 
			   exit();
        }
    } else {
        // Username not found
      echo "<script>alert('Invalid username or password.'); window.location.href = 'login.php';</script>";
		exit(); // 
    }

    $stmt->close();
}

$conn->close();
?>