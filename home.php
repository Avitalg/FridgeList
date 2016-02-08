<?php
    session_start();
    if($_SESSION["authenticated"] != "true"){
        header('Location: index.html');
    }
	
	if (isset($_GET['logout'])) {
		include('controller.php');
		logOut();
		
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
		<title>Fridge List - My Fridge</title>
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
			<h1>My Fridge</h1>
		</header>
		<div id="wrapper">
			<main>
				<br>
				<a href="#" data-toggle="modal" data-target="#myFrizer"><img src="images/fridge1.png" title="frizer" alt="frizer"></a><br>
				<a href="#" data-toggle="modal" data-target="#myFridge"><img src="images/fridge2.png" title="fridge" alt="fridge"></a>
				<!-- Modal -->
				<div class="modal fade" id="myFrizer" role="dialog">
					<div class="modal-dialog">
					
				      <!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">My Freezer</h4>
							</div>
							<div class="modal-body">
								<ul class="popProds">
									<?php
									include ('controller.php');
									myFridge($_SESSION["username"]);
									for($x = 0; $x < count($GLOBALS['prods']); $x++) {
										if( $GLOBALS['place'][$x] == 1){
											echo "<li><section>"  . $GLOBALS['amount'][$x]."</section>";  
											echo "<section>". $GLOBALS['unit'][$x]."</section>";
											echo "<section>".$GLOBALS['prods'][$x] . "</section>"; 
											echo "<section>" . $GLOBALS['expiry'][$x] . "</section></li>";
										}
									}
									?>
								</ul>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					  
					</div>
				</div>
				
				<!-- Modal -->
				<div class="modal fade" id="myFridge" role="dialog">
					<div class="modal-dialog">
					
				      <!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">My Fridge</h4>
							</div>
							<div class="modal-body">
								<ul class="popProds">
									<?php
										myFridge($_SESSION["username"]);
										echo "<ul>";
										for($x = 0; $x < count($GLOBALS['prods']); $x++) {
											if($GLOBALS['place'][$x] == 2){
												echo "<li><section>"  . $GLOBALS['amount'][$x]. "</section>";
												echo "<section>". $GLOBALS['unit'][$x]."</section>";
												echo "<section>".$GLOBALS['prods'][$x] . "</section>"; 
												echo "<section>" . $GLOBALS['expiry'][$x] . "</section></li>";
											}
										}
										echo "</ul>";
									?>
								</ul>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			</main>
		
			<ul id="prods">
				<?php
					myFridge($_SESSION["username"]);
					for($x = 0; $x < count($GLOBALS['prods']); $x++) {
						echo "<li><article>"  . $GLOBALS['amount'][$x]. "</article>";  
						echo "<article>". $GLOBALS['unit'][$x] . "</article>";
						echo "<article>".$GLOBALS['prods'][$x] . "</article>"; 
						echo "<article>" . $GLOBALS['expiry'][$x] . "</article></li>";
					}
				?>
			</ul>
		</div>
		<footer>
			<button id="pRecipes" type="button" class="button">Possible Recipes</button>
		</footer>
	</body>
</html>