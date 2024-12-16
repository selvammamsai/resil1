<?php
// Include the database connection
include('connection.php');

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$username = $_POST['username'];
    $password = $_POST['password'];
	
	// Check if the username already exists
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // If username exists, show an error message
    if ($result->num_rows > 0) {
       echo  "<script>alert('Username already exists. Please choose a different one.')</script>";
		 header("Location: register.php");
    } 

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
	$role_type = 2; // Assuming 2 is the default role type
	$active = 1;     // Assuming 1 means active
	$created_at = date("Y-m-d H:i:s");  // Current timestamp

    // Prepare SQL query to insert the user into the database
    $sql = "INSERT INTO users (username, password,role_type,active,created_at) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $username, $hashedPassword,$role_type,$active,$created_at);

    if ($stmt->execute()) {
        echo  "<script>alert('Username register success.')</script>";
		 header("Location: register.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
	 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
     crossorigin="anonymous">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card" style="width: 20rem;">
            <div class="card-body">

                <h5 class="card-title text-center mb-4">Register</h5>
				<?php if (isset($_GET['error'])) { ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php } ?>
                <!-- Login Form -->
                <form action="#" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
