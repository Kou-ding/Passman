<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
</head>
<body>
    <h3>New user registration</h3>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start a new session (or resume an existing one)
session_start();

// Check if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['username'] !== '') {
	echo "<font color=red>You are already logged in!</font></br>";
	echo "Please <a href='logout.php'>logout</a> first";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	if(!isset($_POST['new_username'], $_POST['new_password']) || trim($_POST['new_username']) =='' || trim($_POST['new_password']) == '') {
		$login_message = "Missing username or password.";
	}
	else {
		// Get user submitted information
		$new_username = trim($_POST['new_username']);
		$new_password = trim($_POST['new_password']);

		require 'connection.php';

        // Hash the password before storing it
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO pwd_mgr.login_users (username, password) VALUES (?, ?)");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("ss", $new_username, $hashed_password);

        if ($stmt->execute()) {
            echo "<font color=red>Successful registration!</font>";
            echo "<p />You can now use the <a href='login.php'>login</a> page";
            exit;
        } else {
            $login_message = "Error, probably user already exists!";
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
// 	echo "<font color=red>You are already logged in!</font></br>";
// 	echo "Please <a href='logout.php'>logout</a> first";
//     exit;
// }

// if ($_SERVER["REQUEST_METHOD"] === "POST") {
// 	if(!isset($_POST['new_username'], $_POST['new_password']) || trim($_POST['new_username']) =='' || trim($_POST['new_password']) == '') {
// 		$login_message = "Missing username or password.";
// 	}
// 	else {
// 		// Get user submitted information
// 		$new_username = trim($_POST['new_username']);
// 		$new_password = trim($_POST['new_password']);
// 		// Connect to the database
// 		$conn=mysqli_connect("localhost","root","","pwd_mgr");
// 		// Check connection
// 		if (mysqli_connect_errno())	{
// 		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
// 		  exit();
// 		}

// 		// Insert a new user
// 		$sql_query = "INSERT INTO login_users (username,password) VALUES ('{$new_username}','{$new_password}');";
// 		//echo $sql_query;

// 		$result = $conn->query($sql_query);

// 		unset($_POST['new_username']);
// 		unset($_POST['new_password']);

// 		if ($result == true) {
// 			echo "<font color=red>Successful registration!</font>";
// 			echo "<p />You can now use the <a href='login.php'>login</a> page";
// 			exit;
// 		}
// 		else 
// 			$login_message = "Error, probably user already exists!";

// 		// Free result set
// 		$conn -> close();
// 	}
// }
?>


<body>
	<p/>
	<form method="POST" action="register.php">
        <input type="text" name="new_username" placeholder="Username"><br />
        <input type="password" name="new_password" placeholder="Password"><br />
        <button type="submit">Register</button>
    </form>

	<br />

    <?php
		if (!empty($login_message)) { 
			echo "<font color=red>$login_message</font>";
			echo "<p />Go to the <a href='login.php'>login</a> page";
		}
	?>

</body>
</html>