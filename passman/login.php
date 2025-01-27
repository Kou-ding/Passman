<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
</head>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start a new session (or resume an existing one)
session_start();

// Check if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['username'] !== '') {
    // Redirect to the dashboard page
	header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['username'], $_POST['password']) || trim($_POST['username']) == '' || trim($_POST['password']) == '') {
        $login_message = "Missing username or password.";
    } else {
		// Get user submitted information
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

		require 'connection.php';
		
        // Debugging: Output the username
        echo "Username entered: " . htmlspecialchars($username) . "<br>";
        
		$stmt = $conn->prepare("SELECT password FROM pwd_mgr.login_users WHERE username = ?");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();

            // Debugging: Output the hashed password
            echo "Hashed password from DB: " . htmlspecialchars($hashed_password) . "<br>";

            if (password_verify($password, $hashed_password)) {
                echo "Password verification successful.<br>";
                $_SESSION['username'] = $username;
                $_SESSION['loggedin'] = true;
                header("Location: dashboard.php");
                exit;
            } else {
                echo "Password verification failed.<br>";
                $login_message = "Invalid username or password";
            }
        } else {
            $login_message = "Invalid username or password";
        }

        $stmt->close();
        $conn->close();
	}
}

//////////////////////// Old code ////////////////////////
// // Start a new session (or resume an existing one)
// session_start();

// // Check if the user is already logged in
// if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['username'] !== '') {
//     // Redirect to the dashboard page
//     header("Location: dashboard.php");
//     exit;
// }

// if ($_SERVER["REQUEST_METHOD"] === "POST") {
// 	if(!isset($_POST['username'], $_POST['password']) || trim($_POST['username']) =='' || trim($_POST['password']) == '') {
// 		$login_message = "Missing username or password.";
// 	}
// 	else {
// 		// Get user submitted information
// 		$username = trim($_POST['username']);
// 		$password = trim($_POST['password']);

// 		// Connect to the database
// 		$conn=mysqli_connect("localhost","root","","pwd_mgr");
// 		// Check connection
// 		if (mysqli_connect_errno())	{
// 		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
// 		  exit();
// 		}

// 		// xxx' OR 1=1; -- '
// 		$sql_query = "SELECT * FROM login_users WHERE username='{$username}' AND password='{$password}';";
// 		//echo $sql_query;

// 		// Check if the credentials are valid
// 		$result = $conn->query($sql_query);
// 		unset($_POST['username']);
// 		unset($_POST['password']);

// 		if (!empty($result) && $result->num_rows >= 1) {
// 			// Regenerate session ID to prevent session fixation!
// 			//session_regenerate_id(true);

// 			// Successfully logged in
// 			$_SESSION['username'] = $username;
// 			$_SESSION['loggedin'] = true;

// 			//while ($row = $result -> fetch_assoc()) {
// 			//	print_r($row);
// 			//	$_SESSION['user_id'] = $row['id'];
// 			//}

// 			// Free result set
// 			$result -> free_result();
// 			$conn -> close();

// 			// Redirect to a dashboard page
// 			header("Location: dashboard.php");
// 			exit;
// 		} else {
// 			$login_message = "Invalid username or password";
// 		}

// 		$conn -> close();
// 	}
// }
?>

<body>
    <h3>Password Manager</h3>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required><br />
        <input type="password" name="password" placeholder="Password" required><br />
        <button type="submit">Login</button>
    </form>
	<br />
    <?php if (!empty($login_message)) { echo "<font color=red>$login_message</font>"; } ?>
	<p/>
    <a href="register.php">Register new user</a>
</body>
</html>