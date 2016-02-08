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
                    header('Location:http://localhost:8888/fridgeList/home.php');
                }
                else {
                    header("Location: http://localhost:8888/fridgeList/signup.php/?$query");
                }
            }
            else{
                header("Location: http://localhost:8888/fridgeList/signup.php/?error_msg=Password+does+not+match");
            }
            
        } else {
            header("Location: http://localhost:8888/fridgeList/signup.php");
        }
    } else {
?>
<!DOCTYPE html>
<html>
	<head>
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
		<section id="form_wrapper">	
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
				<br>
				<label>Name</label>
				<input type="text" name="name">
				<div class="clear"></div>
				<label>Username<span>*</span></label>
				<input type="text" name="username" required>
				<div class="clear"></div>
				<label>Password<span>*</span></label>
				<input type="password" name="password" required>
				<div class="clear"></div>
				<label>Confirm<span>*</span></label>
				<input type="password" name="confirm" required>
				<div class="clear"></div>
				<section class="preference">
					<label>Preference</label>
					<section>
						<input type="checkbox" name="Preferences" value="gluten">Gluten Free<br><br>
						<div class="clear"></div>
						<input type="checkbox" name="Preferences" value="dairy">Dairy Free<br><br>
						<div class="clear"></div>
						<input type="checkbox" name="Preferences" value="vegetarian">Vegetarian<br><br>
					</section>
				</section>
				<button type="submit" class="main_button signup">Create</button><br>
			</form>
		</section>		
	</body>
</html>
<?php } ?>