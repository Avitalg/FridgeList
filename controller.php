<?php

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
        $data;
        $recipeData;
    	$title;
        $prep_time;
        $total_time;
        $recipe_pic;
        $rating;
        $missing;
		
		if(isset($_POST['action'])&&isset($_POST['recipeId'])) {
			$action = $_POST['action'];
			$recipeId = $_POST['recipeId'];
			$username = $_SESSION["username"];
			include ('connection.php');
			switch($action) {
				case 'save': 
					if(isset($connection)){
					$query = 'UPDATE tbl_recipes_213
							  SET favorite=1 
							  WHERE user_name = $username
							  AND rId = $recipeId';
					mysqli_query($connection, $query);
					break;
					}
				case 'delete': 
					if(isset($connection)){
					$query = 'UPDATE tbl_recipes_213
							  SET favorite=0 
							  WHERE user_name = $username
							  AND rId = $recipeId';
					mysqli_query($connection, $query);
					break;
					}
			}
		}
        
        /*Authenticate user*/
        function authenticate($username, $password){
            include ('connection.php');
            if(isset($connection)){
               $query = "SELECT * FROM tbl_users_213 WHERE user_name = '$username'";
               $result = mysqli_query($connection, $query);
            }
            $row = mysqli_fetch_assoc($result);
            if ($row){
                if(password_verify($password , $row["password"])){
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
            $pwdhash = password_hash($password, PASSWORD_DEFAULT);
            if(isset($_POST['name'])) $name = $_POST['name'];
            foreach ($_POST['Preferences'] as $value) {
                if($value == "Gluten Free") $gluten = 1;
                if($value == "Dairy Free") $dairy = 1;
                if($value == "Vegetarian") $vegeterian = 1;
            }
            if(isset($connection)){
               $query = "SELECT * FROM tbl_users_213 WHERE user_name = '$username'";
               $result = mysqli_query($connection, $query);
            }
            $row = mysqli_fetch_assoc($result);
            if (! $row){
                $query = "INSERT INTO tbl_users_213(user_name, password, name, gluten, dairy, vegeterian) 
                            VALUES('$username', '$pwdhash', '$name', '$gluten', '$dairy', '$vegeterian')";
                if(isset($connection)){
                    mysqli_query($connection, $query);
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
                $query = "UPDATE tbl_users_213 
                          SET name = '$name', gluten = '$gluten', dairy = '$dairy', vegeterian = '$vegeterian' 
                          WHERE user_name = '$username'";
                $result = mysqli_query($connection, $query);
                if(isset($connection)){
                     mysqli_query($connection, $query);
                }
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
        	}
    	}
    	/*Connects to json from 'campbellskitchen'*/			
    	function recipes($username){
            include ('connection.php');
            include ('jsonurl.php'); 
            if(isset($connection)){
                $query = "SELECT * FROM tbl_ingredients_213 WHERE user_name= '$username'";
                $result = mysqli_query($connection, $query);
                $ingredients = "";
                while ($row = mysqli_fetch_assoc($result)) {
                    $ingredients =  $ingredients . $row["product"] . "|";
    			}
                rtrim($ingredients, "|");
                $url = $baseUrlSearch . $ingrediantSearch . $ingredients . $format . $appId . $appKey;
                $json = file_get_contents($url);
                $GLOBALS['data'] = json_decode($json);
            }
        }
		
        
    	/*Saves spesific recipe*/
        function saverecipe($recipeId, $username){
            include('connection.php');
            include ('jsonurl.php');
            if (isset($recipeId)){
                if(isset($connection)){
                    $query = "SELECT * FROM tbl_recipes_213 WHERE rId = '$recipeId' AND user_name = '$username'";
                    $result = mysqli_query($connection, $query);
                }
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                if(! $row){ 
                    $url = $baseUrlGet . $recipeId . "?" . $get_format . $appId . $appKey;
                    $json = file_get_contents($url);
                    $GLOBALS['recipeData'] = json_decode($json);
                    $title = $GLOBALS['recipeData']->results->name;
                    $prep_time = $GLOBALS['recipeData']->results->glance->prep;
                    $total_time = $GLOBALS['recipeData']->results->glance->totaltime;
                    $recipe_pic = $GLOBALS['recipeData']->results->mobileimg;
                    $rating = intval($GLOBALS['recipeData']->results->rating);
                    $ingredients = $GLOBALS['recipeData']->results->ingredients;
                    $preps = $GLOBALS['recipeData']->results->steps;
                    $query = "INSERT INTO tbl_recipes_213
                                (title, rId, prep_time, total_time, user_name, pic_url, rating) 
                              VALUES
                                ('$title', '$recipeId', '$prep_time', '$total_time', '$username', '$recipe_pic', '$rating')";
                    if(isset($connection)){
                        mysqli_query($connection, $query);
                    }
                    if (is_array($ingredients)){
                        foreach ($ingredients as $ingredient){
                            $ingredient_name = $ingredient->ingredient_name;
                            $ingredient = $ingredient->ingredient;
                            $query = "INSERT INTO tbl_recipe_ingredients_213(rId, ingredient, ingredient_name) 
                                        VALUES('$recipeId', '$ingredient', '$ingredient_name')";
                            if(isset($connection)){
                                mysqli_query($connection, $query);
                            }
                        }
                    }
                    if (is_array($preps)){
                        foreach ($preps as $prep){
                            $prep_num = $prep->TipNumber;    
                            $prep = $prep->TipText;
                            $query = "INSERT INTO tbl_recipe_prep_213(rId, prep) 
                                        VALUES('$recipeId', '$prep')";
                            if(isset($connection)){
                                mysqli_query($connection, $query);
                            }
                        }
                    }
                }              
            } 
        }
    	           
        function recipeDetails($recipeId, $username){
            include ('connection.php');
            if(isset($connection)){
                $query = "SELECT * FROM tbl_recipes_213 WHERE rId = '$recipeId' AND user_name = '$username'";
                $result = mysqli_query($connection, $query);
            }        
            while ($row = mysqli_fetch_assoc($result)) {
                $GLOBALS['title'] = $row["title"];
                $GLOBALS['prep_time']= $row["prep_time"];
                $GLOBALS['total_time']= $row["total_time"];
                $GLOBALS['recipe_pic']= $row["pic_url"];
                $GLOBALS['rating']= $row["rating"];
            }
            $index = 0;
            if(isset($connection)){
                $query = "SELECT * FROM tbl_recipe_ingredients_213 WHERE rId = '$recipeId'";
                $result = mysqli_query($connection, $query);
            }
            while ($row = mysqli_fetch_assoc($result)) {
                if($row["ingredient"])
                    $GLOBALS['recipes_ingredients'][$index++] = $row["ingredient"];
            }
            $index = 0;
            if(isset($connection)){
                $query = "SELECT * FROM tbl_recipe_prep_213 WHERE rId = '$recipeId'";
                $result = mysqli_query($connection, $query);
            }
            while ($row = mysqli_fetch_assoc($result)) {
                if($row["prep"])
                    $GLOBALS['recipes_prep'][$index++] = $row["prep"];
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
            }  
        }
		function myRecipes($username){
			include ('connection.php');
			if(isset($connection)){
				$query = "SELECT title, pic_url FROM tbl_recipes_213 WHERE user_name = '$username' AND favorite = 1";
				$result = mysqli_query($connection, $query);
                $index = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    if($row["title"])
                        $GLOBALS['title'][$index] = $row["title"];
						$GLOBALS['$recipe_pic'][$index++] = $row["pic_url"];
                }
			}
		}
		
		function favorite($recipeId, $username){
			include ('connection.php');
			if(isset($connection)){
				$query = "SELECT favorite FROM tbl_recipes_213 WHERE user_name = '$username' AND rId = $recipeId";
				$result = mysqli_query($connection, $query);
				$row = mysqli_fetch_assoc($result);
				if ($row["favorite"] == 1) return true;
				else return false;
			}
		}
        
        function isUrlExist($url){
            $ch = curl_init($url);    
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if($code == 200){
                $status = true;
            }else{
                $status = false;
            }
            curl_close($ch);
            return $status;
        }
?>