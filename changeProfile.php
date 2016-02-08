<?php
    session_start();
    if($_SESSION["authenticated"] != "true"){
        header('Location: index.html');
    }
    include ('controller.php');
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        changeProfile($_SESSION["username"]);
        header('Location:http://localhost:8888/fridgeList/profile.php/?changed=yes');
    } else {
?>
<!DOCTYPE html>
<html>
	<head>
		<link href='https://fonts.googleapis.com/css?family=Cantora+One' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="includes/style.css" />
		<script src="includes/script.js"></script>
		<title>Fridge List - Change Profile</title>
	</head>
	<body id="signup_body">
		<?php 
        if(isset($_GET["changed"])){
            echo "<p>Successfully changed</p>";
        }
        ?>
		<nav id="navbar" class = "navbar navbar-default" role = "navigation">
		    <div class = "navbar-header">
				<button type = "button" class = "navbar-toggle" data-toggle = "collapse" data-target = "#navbar-collapse">
					<span class = "icon-bar"></span>
					<span class = "icon-bar"></span>
					<span class = "icon-bar"></span>
					<span class = "icon-bar"></span>
				</button>		
				<a id="title_logo" class = "navbar-brand" href = "#">Fridge List</a>
		    </div>
			<div class = "collapse navbar-collapse" id = "navbar-collapse">
				<ul class = "nav navbar-nav">
					<li class = "border"><a href = "profile.php">Profile</a></li>
					<li class="border"><a href = "home.php">My Fridge</a></li>
					<li class="border"><a href = "myRecipes.php">My Recipes</a></li>
					<li class="border"><a href = "home.php?logout=true">Log Out</a></li>
					
				</ul>
			</div>
		</nav>
		<header>
			<h1>Change Profile</h1>
		</header>
		<section id="form_wrapper" class="profile">
			<p>*Required fields</p>
			<form>
				<label class="float_left">Name</label>
				<input type="text" class="float_right" name="name">
				<div class="clear"></div>
				<label class="float_left" name="username">Username<span>*</span></label>
				<input type="text" class="float_right" required>
				<div class="clear"></div>
				<label class="float_left" name="password">Password<span>*</span></label>
				<input type="password" class="float_right" required>
				<div class="clear"></div>
				<label class="float_left" name="confirm">Confirm<span>*</span></label>
				<input type="password" class="float_right" required>
				<div class="clear"></div>
				<section class="preference">
					<label class="float_left">Preference</label>
					<section class="float_right">
						<input class="float_left" name="Preferences" type="checkbox" value="Gluten Free">Gluten Free<br><br>
						<div class="clear"></div>
						<input class="float_left" name="Preferences" type="checkbox" value="Dairy Free">Dairy Free<br><br>
						<div class="clear"></div>
						<input class="float_left" name="Preferences" type="checkbox" value="Vegetarian">Vegetarian<br><br>
					</section>
					<div class="clear"></div>
				</section>
				<button type="submit" value="Change" class="button">Save Changes</button><br>
			</form>
		</section>	
	</body>
</html>
	<?php }?>