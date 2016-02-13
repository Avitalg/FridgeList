<?php

	
	//ajax handling
	if(isset($_POST['action'])) {
		session_start();
		$action = $_POST['action'];
		if(isset($_POST['recipeId'])){
			$recipeId = $_POST['recipeId'];
		}
		if(isset($_POST['type'])){
			$type = $_POST['type'];
		}
		$username = $_SESSION["username"];
		include ('connection.php');
		switch($action){
			case 'save': 
				setFavorite(1, $username, $recipeId);
				break;
				
			case 'delete': 
				setFavorite(0, $username, $recipeId);
				break;
				
			case 'byName':
				if($type == "myFridge"){
					recipes($username);
				}
				else if($type == "quick"){
					quickRecipes(15);
				}
				else{
					searchRecipes($type);
				}
				break;
				
			case 'byRating':
				if($type == "myFridge"){
					recipes($username);
				}
				else if($type == "quick"){
					quickRecipes(15);
				}
				else{
					searchRecipes($type);
				}
				break;
				
			case 'getRecipesList':
				recipes($username);
				break;
				
			case 'recipeDetails':
				getRecipe($recipeId);
				break;
			
			case 'saveRecipe':
				$id = $_POST['id'];
				$name = $_POST['name'];
				$ingredients = stripslashes($_POST['ingredients']);	
				$pic_url = $_POST['pic'];
				saveRecipe($id, $name, $username, $pic_url, $ingredients);
				break;
			
			case 'getOuickRecipes':
				quickRecipes(15);
				break;
				
			case 'searchRecipes':
				$searchParam = $_POST['search'];
				searchRecipes($searchParam);
				break;
		}
	}
	
	$data;
	$prods = array();
	$amount = array();
	$unit = array();  
	$expiry = array();
	$place = array();
	$user = array();
	$recipe_pic;
	$recipes_ingredients= array();
	$recipes_prep= array();
	$recipe_rating;
	$recipe_preptime;
	$recipeId;
	$recipeData;
	$title;
	$prep_time;
	$total_time;
	$recipe_pic;
	$rating;
	$missing;
	
	/*Authenticate user*/
	function authenticate($username, $password){
		include ('connection.php');
		if(isset($connection)){
		   $query = "SELECT password FROM tbl_users_213 WHERE user_name = '$username'";
		   $result = mysqli_query($connection, $query);
		}
		$row = mysqli_fetch_assoc($result);
		if(isset($connection)){
			mysqli_close($connection);
			unset($connection);
		}
		if ($row){
			if($row["password"] === $password){
				return "OK";
			}
			else return "Wrong password";
		}
		else return "User not found";
	}
	
	/*Create new user*/
	function createUser($username, $password){
		include ('connection.php');
		$name = "";
		$gluten = 0;
		$dairy = 0;
		$vegeterian = 0;
		if(isset($_POST['name'])) $name = $_POST['name'];
		foreach ($_POST['Preferences'] as $value) {
			if($value == "Gluten Free") $gluten = 1;
			if($value == "Dairy Free") $dairy = 1;
			if($value == "Vegetarian") $vegeterian = 1;
		}
		if(isset($connection)){
		   $query = "SELECT user_name FROM tbl_users_213 WHERE user_name = '$username'";
		   $result = mysqli_query($connection, $query);
		}
		$row = mysqli_fetch_assoc($result);
		if (! $row){
			$query = "INSERT INTO tbl_users_213(user_name, password, name, gluten, dairy, vegeterian) 
						VALUES('$username', '$password', '$name', '$gluten', '$dairy', '$vegeterian')";
			if(isset($connection)){
				mysqli_query($connection, $query);
				mysqli_close($connection);
				unset($connection);
			}
			return "OK";
		}
		else return "User already exists";
	}
	
	/*Get user's profile*/
	function profile($username){
		include ('connection.php'); 
		if(isset($connection)){
		   $query = "SELECT * FROM  tbl_users_213 WHERE user_name= '$username'";
		   $result = mysqli_query($connection, $query);
		}
		$index = 0;
		while ($row = mysqli_fetch_assoc($result)) {
			$GLOBALS['user'][$index++] = $row["name"];
			$GLOBALS['user'][$index++] = $row["user_name"];
			$GLOBALS['user'][$index++] = $row["gluten"];
			$GLOBALS['user'][$index++] = $row["dairy"];
			$GLOBALS['user'][$index] = $row["vegeterian"];
		}
		if (isset($connection)){
		   mysqli_close($connection);
		   unset($connection);
		}
	}
	
	/*Profile change*/
	function changeProfile($username){
		include ('connection.php');
		$name = "";
		$gluten = 0;
		$dairy = 0;
		$vegeterian = 0;
		if(isset($_POST['name'])) $name = $_POST['name'];
		if(isset($_POST['Preferences'])){
			foreach ($_POST['Preferences'] as $value) {
				if($value == "Gluten Free") $gluten = 1;
				if($value == "Dairy Free") $dairy = 1;
				if($value == "Vegetarian") $vegeterian = 1;
			}
		}
		if(isset($connection)){
			if($name != ""){
				$query = "UPDATE tbl_users_213 
						  SET name = '$name', gluten = '$gluten', dairy = '$dairy', vegeterian = '$vegeterian' 
						  WHERE user_name = '$username'";
			}
			else{
				$query = "UPDATE tbl_users_213 
						  SET gluten = '$gluten', dairy = '$dairy', vegeterian = '$vegeterian' 
						  WHERE user_name = '$username'";
			}
			$result = mysqli_query($connection, $query);
			if(isset($connection)){
				 mysqli_query($connection, $query);
				 mysqli_close($connection);
				 unset($connection);
			}
		}
	}
	
	function setFavorite($status, $username, $recipeId){
		include ('connection.php');
		if(isset($connection)){
			$query = "UPDATE tbl_recipes_213
					  SET favorite = '$status' 
					  WHERE user_name = '$username'
					  AND rId = '$recipeId'";
		mysqli_query($connection, $query);
		mysqli_close($connection);
		unset($connection);
		}
	}
	
	/*Log out*/
	function logOut(){
		session_unset();
		session_destroy();
		header('Location: index.html');
	}
	
	/*Connects to DB and shows the products in my fridge*/
	function myFridge($username){
		include ('connection.php'); 
		if(isset($connection)){
		   $query = "SELECT * FROM tbl_ingredients_213 WHERE user_name= '$username'";
		   $result = mysqli_query($connection, $query);
		}
		$index = 0;
		while ($row = mysqli_fetch_assoc($result)) {
			$GLOBALS['prods'][$index] = $row["product"];
			$GLOBALS['amount'][$index] = $row["amount"];
			$GLOBALS['unit'][$index] = $row["unit"];
			$GLOBALS['place'][$index] = $row["place"];
			$GLOBALS['expiry'][$index++] = $row["expiry_date"];
		}
		if (isset($connection)){
		   mysqli_close($connection);
		   unset($connection);
		}
	}
	
	/*Connects to json from 'campbellskitchen'*/			
	function recipes($username){
		include ('connection.php');
		include ('jsonurl.php'); 
		if(isset($connection)){
			$query = "SELECT product FROM tbl_ingredients_213 WHERE user_name= '$username'";
			$result = mysqli_query($connection, $query);
			$ingredients = "";
			while ($row = mysqli_fetch_assoc($result)) {
				$ingredients =  $ingredients . $row["product"] . "|";
			}
			rtrim($ingredients, "|");
			$url = $baseUrlSearch . $ingrediantSearch . $ingredients . $format . $appId . $appKey;
			$json = file_get_contents($url);
			mysqli_close($connection);
			unset($connection);
			echo $json;
		}
	}
	
	function quickRecipes($time){
		include ('jsonurl.php'); 
		$url = $baseUrlSearch . $preptime . $time . $format . $appId . $appKey;
		$json = file_get_contents($url);
		echo $json;
	}
	
	function getRecipe($recipeId){
		include ('jsonurl.php');
		$url = $baseUrlGet . $recipeId . "?" . $get_format . $appId . $appKey;
		$json = file_get_contents($url);
		echo $json;
	}
	
	function searchRecipes($searchParam){
		include ('jsonurl.php'); 
		$url = $baseUrlSearch . $keywords . $searchParam . $format . $appId . $appKey;
		$json = file_get_contents($url);
		echo $json;
	}
	
	/*Saves specsific recipe*/
	function saveRecipe($id, $name, $username, $pic_url, $ingredients){
		include('connection.php');
		$name = str_replace("'", "\'", $name);
		$ingredients = json_decode($ingredients);
		if (isset($id)){
			if(isset($connection)){
				$query = "SELECT rId FROM tbl_recipes_213 WHERE rId = '$id' AND user_name = '$username'";
				$result = mysqli_query($connection, $query);
				$row = mysqli_fetch_assoc($result);
				if(! $row){ 
					$query = "INSERT INTO tbl_recipes_213
								(title, rId, user_name, recipe_pic) 
							  VALUES
								('$name', '$id', '$username', '$pic_url')";
					mysqli_query($connection, $query);
				}
				$query = "SELECT rId FROM tbl_recipe_ingredients_213 WHERE rId = '$id'";
				$result = mysqli_query($connection, $query);
				$row = mysqli_fetch_assoc($result);
				if(! $row){
					foreach($ingredients as $key => $ingredient){
						$ingredient_name = $ingredient->ingredient_name;
						$ingredient = $ingredient->ingredient;
						$query = "INSERT INTO tbl_recipe_ingredients_213
									(rId, ingredient, ingredient_name) 
								  VALUES
									('$id', '$ingredient', '$ingredient_name')";
						mysqli_query($connection, $query);
					}
				}
				mysqli_close($connection);
				unset($connection);
			}              
		} 
	}
			   
	
	function missingIngredients($username, $recipeId){
		include ('connection.php');
		if(isset($connection)){
			$query = "SELECT 
						ingredient_name 
					  FROM 
						tbl_recipe_ingredients_213
					  WHERE 
						rId = '$recipeId'
					  AND ingredient_name NOT IN 
						 (SELECT 
							product 
						  FROM     
							tbl_ingredients_213 
						  WHERE 
							user_name = '$username')";
			$result = mysqli_query($connection, $query);
			$index = 0;
			while ($row = mysqli_fetch_assoc($result)) {
				if($row["ingredient_name"])
					$GLOBALS['missing'][$index++] = $row["ingredient_name"];
			}
			mysqli_close($connection);	
			unset($connection);
		}  
	}
	function myRecipes($username){
		include ('connection.php');
		if(isset($connection)){
			$query = "SELECT title, rId, recipe_pic FROM tbl_recipes_213 WHERE user_name = '$username' AND favorite = 1";
			$result = mysqli_query($connection, $query);
			$index = 0;
			while ($row = mysqli_fetch_assoc($result)) {
				if($row["title"])
					$GLOBALS['title'][$index] = $row["title"];
					$GLOBALS['recipe_pic'][$index] = $row["recipe_pic"];
					$GLOBALS['recipeId'][$index++] = $row["rId"];
			}
			mysqli_close($connection);
			unset($connection);
		}
	}
	
	function favorite($recipeId, $username){
		include ('connection.php');
		if(isset($connection)){
			$query = "SELECT favorite FROM tbl_recipes_213 WHERE user_name = '$username' AND rId = $recipeId";
			$result = mysqli_query($connection, $query);
			$row = mysqli_fetch_assoc($result);
			mysqli_close($connection);
			unset($connection);
			if ($row["favorite"] == 1) return true;
			else return false;
		}
	}
?>