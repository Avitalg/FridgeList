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
		<title>Fridge List - Recipe</title>
    </head>
    <body id="recipe_body">
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
			<h1>Recipe</h1>
		</header>
		<div id="wrapper">
			<?php
				include('controller.php');
				if(isset($_GET["recipeId"])){
					saverecipe($_GET["recipeId"], $_SESSION["username"]);
					recipeDetails($_GET["recipeId"], $_SESSION["username"]);
					echo '<h2 id="rhead">' . $GLOBALS['title'];
					if (favorite($_GET['recipeId'], $_SESSION['username'])){
						echo '<img src="images/heart2.png" alt="heart" id="heart" title="heart">';
					}
					else{
						echo '<img src="images/heart1.png" alt="heart" id="heart" title="heart">';
					}
					echo '</h2>';
					echo '<article><img src="images/watch.png" alt="watch" title="watch"></article>';
					echo "<article><p>Preparation time: " . $GLOBALS['prep_time'] . "</p>";
					echo "<p>Total time: " . $GLOBALS['total_time']."</p></article>";
					if(isUrlExist($GLOBALS['recipe_pic'])){
						echo '<br><br><img class="rpic" src="' . $GLOBALS['recipe_pic']  . '" alt="' . $GLOBALS['title'] . '">';
					}
					else{
						echo '<img class="rpic" src="images/noimage.jpg" alt="no image available">';
					}
					echo "<p>".'<img src="images/stars.png" title="rating" alt="rating">' . $GLOBALS['rating'] . "</p>";
					echo "<section>";
					echo "<h3>Ingredients</h3>";
					echo '<ul class="recDetails">';
					for($x = 0; $x < count($GLOBALS['recipes_ingredients']); $x++) {
						echo "<li>" . $GLOBALS['recipes_ingredients'][$x] . "</li>";
					}
					echo "</ul></section>";
					echo "<section><h3>Preparation</h3>";
					echo '<ol>';
					for($x = 0; $x < count($GLOBALS['recipes_prep']); $x++) {
						echo "<li>" . $GLOBALS['recipes_prep'][$x] . "</li>";
					}
					echo "</ol></section>";
					echo "<footer>";
					echo '<a class="button" href="missingingredients.php/?recipeId=' . $_GET["recipeId"] . '">Missing Ingredients</a></footer>';
				}
			?>
		</div>
    </body>
</html>   
   
