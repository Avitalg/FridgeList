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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<link rel="stylesheet" href="includes/style.css" />
		<script src="includes/script.js"></script>
		<title>Fridge List - Missing Ingredients</title>
    </head>
    <body>
		<header>
			<a href="#" id="title_logo">Fridge List</a>
			<h1>Missing Ingredients</h1>
		</header>
		<main>
			<?php
				include('controller.php');
				if(isset($_GET["recipeId"])){
					missingIngredients($_SESSION["username"], $_GET["recipeId"]);
					echo '<ul id="prods">';
					for($x = 0; $x < count($GLOBALS['missing']); $x++) {
						echo "<li>" . $GLOBALS['missing'][$x] . "</li>";
					}
					echo "</ul>";
				}
			?>
		</main>
    </body>
</html>