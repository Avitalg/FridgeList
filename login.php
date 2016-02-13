<?php
    include ('controller.php');
    $username = null;
    $password = null;
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
        if(isset($_POST["username"]) && isset($_POST["password"])) {
            $username = $_POST["username"];
            $password = $_POST["password"];
            $response = authenticate($username, $password);
            $query = "error_msg=" . $response;
            if($response == "OK") {
                session_start();
                $_SESSION["authenticated"] = 'true';
                $_SESSION["username"] = $username;
                header('Location: home.php');
            }
            else {
                header("Location: login.php?$query");
            }
            
        } else {
            header("Location: login.php");
        }
    } else {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href='https://fonts.googleapis.com/css?family=Cantora+One' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<link rel="stylesheet" href="includes/style.css" />
		<script src="includes/script.js"></script>
		<title>Fridge List - Log In</title>
	</head>
	<body id="login_body">
		<nav id="navbar">
			<a href="#" id="title_logo">Fridge List</a>
		</nav>
		<header>
			<h1>Log In</h1>
		</header>
		 <form id="login_form" method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>>
                   <p id="msg"><?php 
                           if(isset($_GET["error_msg"])){
                               echo $_GET["error_msg"];
                          }
                       ?>
                       <br>
                   </p>
			<label>Username</label>
			<input type="text" name="username" class="float_right" required>
			<div class="clear"></div>
			<label>Password</label>
			<input type="password" name="password" required>
			<div class="clear"></div>
			<button id="login" type="submit" class="main_button">Submit</button>
		</form>
	</body>
</html>
<?php } ?>