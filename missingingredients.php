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
		<link rel="stylesheet" href="includes/style.css">
		<script src="includes/script.js"></script>
		<title>Fridge List - Missing Ingredients</title>
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
					<li class = "border"><a href = "profile.php">Profile</a></li>
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
			<h1>Missing Ingredients</h1>
		</header>
		<ol id="missIng">
               <?php
				include('controller.php');
				if(isset($_GET["recipeId"])){
					missingIngredients($_SESSION["username"], $_GET["recipeId"]);
					for($x = 0; $x < count($GLOBALS['missing']); $x++) {
						echo "<li>" . $GLOBALS['missing'][$x] . "</li>";
					}
				}
			?>
           </ol>
		<footer>
			<button id="backB" type="button" class="button">Back to Recipe</button>
		</footer>
	</body>
</html>