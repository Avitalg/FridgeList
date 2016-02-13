<?php
    include ('controller.php');
    $username = null;
    $password = null;
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["confirm"])) { 
            $username = $_POST["username"];
            $password = $_POST["password"];
            $confirm = $_POST["confirm"];
            if ($password ===  $confirm){
                $response = createUser($username, $password);
                $query = "error_msg=" . $response;
                if($response == "OK") {
                    session_start();
                    $_SESSION["authenticated"] = 'true';
                    $_SESSION["username"] = $username;
                    header('Location: home.php');
                }
                else {
                    header("Location: signup.php?$query");
                }
            }
            else{
                header("Location: signup.php?error_msg=Password+does+not+match");
            }
            
        } else {
            header("Location: signup.php");
        }
    } else {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href='https://fonts.googleapis.com/css?family=Cantora+One' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<link rel="stylesheet" href="includes/style.css" />
		<script src="includes/script.js"></script>
		<title>Fridge List - Sign Up</title>
	</head>
	<body id="signup_body">
		<nav id="navbar">
			<a href="#" id="title_logo">Fridge List</a>
		</nav>
		<header>
			<h1>Sign Up</h1>
		</header>
		<section id="signup_wrapper">	
			 <article id="msg">
                <?php 
                    if(isset($_GET["error_msg"])){
                        echo $_GET["error_msg"];
                    }
                ?>
                <br>
            </article> 
            <form method="post">
			<br>
				<p>*Required fields</p>
			<section id="labels">
				<label>Name</label>
				<label>Username<span>*</span></label>
				<label>Password<span>*</span></label>
				<label>Confirm<span>*</span></label>
				<label>Preference</label>
			</section>
			<section id="inputs">
				<input type="text" name="name"><br>
				<input type="text" name="username" required><br>
				<input type="password" name="password" required><br>
				<input type="password" name="confirm" required><br><br>
				<section class="preference">
					<input type="checkbox" name="Preferences[]" value="gluten"><label>Gluten Free</label><br>
					<input type="checkbox" name="Preferences[]" value="dairy"><label>Dairy Free</label><br>
					<input type="checkbox" name="Preferences[]" value="vegetarian"><label>Vegetarian</label><br>
				</section>
			</section>
			<div id="clear"></div><br><br><br><br><br><br>
				<button type="submit" class="main_button signup">Create</button>
			</form>
		</section>		
	</body>
</html>
<?php } ?>