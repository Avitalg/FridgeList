<?php
    session_start();
    if($_SESSION["authenticated"] != "true"){
        header('Location: index.html');
    }
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href='https://fonts.googleapis.com/css?family=Cantora+One' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="includes/style.css" />
		<script src="includes/script.js"></script>
		<title>Fridge List - Profile</title>
		<?php 
			include ('controller.php');
			profile($_SESSION["username"]);	
		?>
	</head>
	<body>
		<nav id="navbar" class = "navbar navbar-default" role = "navigation">
		    <div class = "navbar-header">
				<button type = "button" class = "navbar-toggle" data-toggle = "collapse" data-target = "#navbar-collapse">
					<span class = "icon-bar"></span>
					<span class = "icon-bar"></span>
					<span class = "icon-bar"></span>
					<span class = "icon-bar"></span>
					<span class = "icon-bar"></span>
				</button>		
				<a id="title_logo" class = "navbar-brand" href = "#">Fridge List</a>
		    </div>
			<div class = "collapse navbar-collapse" id = "navbar-collapse">
				<ul class = "nav navbar-nav">
					<li class = "active border"><a href = "profile.php">Profile</a></li>
					<li class="border"><a href = "home.php">My Fridge</a></li>
					<li class = "dropdown border">
						<a href = "#" class = "dropdown-toggle" data-toggle = "dropdown">
						   Recipes 
						   <b class = "caret"></b>
						</a>
						
						<ul class = "dropdown-menu">
						   <li><a href = "possibleRecipes.php?recipe=myFridge">Possible Recipes</a></li>
						   <li><a href = "possibleRecipes.php?recipe=quick">Quick Recipes</a></li>
						   <li><a href = "possibleRecipes.php?recipe=search">Search Recipes</a></li>
						</ul> 
					</li>
					<li class="border"><a href = "myRecipes.php">My Recipes</a></li>
					<li class="border"><a href = "home.php?logout=true">Log Out</a></li>
					
				</ul>
			</div>
		</nav>
		<header>
			<h1>Profile</h1>
		</header>
		<br>
		<section id="profile_wrapper">
			<img src="images/profile.png" title="profile" alt="profile">
			<br><br>
			<section id="label_section">
				<p>Name</p>
				<p>Username</p>
				<p>Preferences</p>
			</section>
			<section id="data_section">
				<p>
					<?php 
						echo $GLOBALS['user'][0];
					?>
				</p>
				<p>
					<?php 
						echo $GLOBALS['user'][1];
					?>
				</p>
				<p>
					<?php
						if($GLOBALS['user'][2]==1)
							echo '<p> Gluten Free </p>';
						if($GLOBALS['user'][3]==1)
							echo '<p> Dairy Free </p>';
						if($GLOBALS['user'][4]==1)
							echo '<p> Vegeterian </p>';				
					?>
				</p>
			</section>
			<div class="clear"></div>
			<br>
			<button id="profile" type="button" class="button">Change</button>
		</section>	
	</body>
</html>