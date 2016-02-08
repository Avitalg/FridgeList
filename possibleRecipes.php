<?php
    session_start();
    if($_SESSION["authenticated"] != "true"){
        header('Location: index.html');
    }
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
		<title>Fridge List - Possible Recipes</title>
	</head>
	<body>	
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
			<h1>Possible Recipes</h1>
		</header>
		<div id="pRec_wrapper">
			<main id="content">
				<ul id="pRec">
					<?php 
						include ('controller.php');
						recipes($_SESSION["username"]);
						for($i = 0; $i < count($GLOBALS['data']->recipes); $i++){
							if(isUrlExist($GLOBALS['data']->recipes[$i]->recipelink)){
								echo '<li><img src="' . $GLOBALS['data']->recipes[$i]->recipelink
								 . '" alt="' . $GLOBALS['data']->recipes[$i]->name . '">';
							}
							else{
								echo '<li><img src="images/noimage.jpg" alt="no image available">';
							}
							echo '<a href="recipeDetails.php?recipeId=' 
								,urlencode($GLOBALS['data']->recipes[$i]->recipe_id),'">' 
								. $GLOBALS['data']->recipes[$i]->name . '</a></li>';
						} 
					?>
				</ul>
			</main>
		</div>		
	</body>
</html>
