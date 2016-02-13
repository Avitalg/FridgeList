$(window).load(function(){
	
	var heartCheck=1;
	var url = window.location.href;
	
	if(url.indexOf("recipeDetails") != -1 && $("#heart").attr('src').indexOf("2") != -1){
		heartCheck=2;
	}
	
	if(url.indexOf("home") != -1){
		$( ".date" ).each(function(){
			var dateString = $( this ).text();
			var date = new Date(dateString);
			var today = new Date();
			if(date.getTime() - today.getTime() < 0){
				$( this ).css( "color", "#FF0000" ); 
			}
		});
	}
	
	if(url.indexOf("myFridge") != -1){
		$.post('controller.php', { action: "getRecipesList" }, function(data){
			var json = $.parseJSON(data);
			var jsonData = "";
			$.each(json.recipes, function(index, recipe){
				jsonData += '<li><a href="recipeDetails.php?recipeId=' + recipe.recipe_id + '">';
				jsonData += '<img src="' + recipe.recipelink + '" alt="' + recipe.name + '"><span></span><p>' + recipe.name + '</p></a></li>';
			});
			$("#pRec").append(jsonData);
		});
	}
	
	if(url.indexOf("quick") != -1){
		$.post('controller.php', { action: "getOuickRecipes" }, function(data){
			var json = $.parseJSON(data);
			var jsonData = "";
			$.each(json.recipes, function(index, recipe){
				jsonData += '<li><a href="recipeDetails.php?recipeId=' + recipe.recipe_id + '">';
				jsonData += '<img src="' + recipe.recipelink + '" alt="' + recipe.name + '"><span></span><p>' + recipe.name + '</p></a></li>';
			});
			$("#pRec").append(jsonData);
		});
	}
	
	if(url.indexOf("search") != -1){
		$("#content").empty().fadeIn();
		var search = '<form method="GET">' + 
						'<div class="row">' +
							'<div class="col-lg-6">' +
								'<div class="input-group">' +
									'<input type="text" id="searchText" class="form-control" name="param" placeholder="Search for recipes...">' +
									'<span class="input-group-btn">' +
										'<input type="submit" id="go" class="btn btn-default" type="button" value="Go!">' +
									'</span>' +
								'</div><!-- /input-group -->' +
							'</div><!-- /.col-lg-6 -->' +
						'</div><!-- /.row -->' +
					'</form>';
		$("#content").append(search);
	}
	
	if(url.indexOf("param") != -1){
		$("#content").empty().fadeIn();
		var content = '<!-- Single button -->' +
						'<div class="btn-group">' +
							'<button type="button" class="btn btn-success btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
								'Order By <span class="caret"></span>' +
							'</button>' +
							'<ul class="dropdown-menu">' +
								'<li><a id="byName" href="#" role="button">Name</a></li>' +
								'<li><a id="byRating" href="#" role="button">Rating</a></li>' +
							'</ul>' +
						'</div>' +
					'<ul id="pRec"></ul>';
		$("#content").append(content);
		var query = window.location.search.substring(1).split('=');
		var searchParam = query[1];
		$.post('controller.php', { action: "searchRecipes", search: searchParam }, function(data){
			var json = $.parseJSON(data);
			var jsonData = "";
			if(json.totalrecipes == 0){
				$("#content").empty().fadeIn();
				jsonData = '<h4>No recipes found</h4>';
				$("#content").append(jsonData);
			}
			else{
				$.each(json.recipes, function(index, recipe){
					jsonData += '<li><a href="recipeDetails.php?recipeId=' + recipe.recipe_id + '">';
					jsonData += '<img src="' + recipe.recipelink + '" alt="' + recipe.name + '"><p>' + recipe.name + '</p></a></li>';
				});
				$("#pRec").append(jsonData);
			}
			var backButton = '<footer id="searchOnly">' +
								'<button id="backToSearch" type="button" class="button">Back to Search</button>' +
							 '</footer>';
			$("body").append(backButton);
		});
	}
	
	if(url.indexOf("recipeDetails") != -1){
		var query = window.location.search.substring(1).split('=');
		var rId = query[1];
		$.post('controller.php', { action: "recipeDetails", recipeId: rId }, function(data){
			var json = $.parseJSON(data);
			var jsonData = "";
			json = json.results;
			$( '#rhead' ).prepend(json.name);
			jsonData += '<article><img src="images/watch.png" alt="watch" title="watch"></article>';
			jsonData += "<article><p>Preparation time: " + json.glance.prep + "</p>";
			jsonData += "<p>Total time: " + json.glance.totaltime + "</p></article>";
			jsonData += '<br><br><img class="rpic" src="' + json.mobileimg + '" alt="' + json.name + '">';
			jsonData += '<p><img src="images/stars.png" title="rating" alt="rating"></p>';
			jsonData += "<section>";
			jsonData += "<h3>Ingredients</h3>";
			jsonData += '<ul class="recDetails">';
			$.each(json.ingredients, function(index, ingredient){
				jsonData += "<li>" + ingredient.ingredient + "</li>";
			});
			jsonData += "</ul></section>";
			jsonData += "<section><h3>Preparation</h3>";
			jsonData += '<ol>';
			var steps = _.sortBy(json.steps, ['TipNumber', 'asc']);
			$.each(steps, function(index, step){
				jsonData += "<li>" + step.TipText + "</li>";
			});
			jsonData += "</ol></section>";
			jsonData += "<footer>";
			jsonData += '<a class="button" href="missingingredients.php?recipeId=' + json.recipe_id + '">Missing Ingredients</a></footer>';
			$("#wrapper").append(jsonData);
			var ingredients = JSON.stringify(json.ingredients);
			$.post('controller.php', { 
										action: "saveRecipe", 
										name: json.name, 
										id: json.recipe_id,
										pic: json.mobileimg,
										ingredients: ingredients 
									 }, function(data){});
		});
	}
	
	$( "#login" ).click( function () {
		location.href = "login.php";
	});
	
	$( "#signup" ).click( function () {
		location.href = "signup.php";
	});

	$( "#pRecipes" ).click(function () {
		location.href = "possibleRecipes.php?recipe=myFridge";
	});
	
	$( "#qRecipes" ).click(function () {
		location.href = "possibleRecipes.php?recipe=quick";
	});
	
	$( "#fRecipes" ).click(function () {
		location.href = "possibleRecipes.php?recipe=search";
	});

	$( "#backB" ).click(function () {
		history.back();
	});
	
	$( "#profile" ).click(function () {
		location.href = "changeProfile.php";
	});
	
	$( "#byName" ).click(function(){
		$("#pRec").empty().fadeIn();
		var query = window.location.search.substring(1).split('=');
		var type = query[1];
		$.post('controller.php', { action: "byName", type: type }, function(data){
			var json = $.parseJSON(data);
			var jsonData = "";
			json.recipes = _.sortBy(json.recipes, ['name', 'asc']);
			$.each(json.recipes, function(index, recipe){
				jsonData += '<li><a href="recipeDetails.php?recipeId=' + recipe.recipe_id + '">';
				jsonData += '<img src="' + recipe.recipelink + '" alt="' + recipe.name + '"><span></span><p>' + recipe.name + '</p></a></li>';
			});
			$("#pRec").append(jsonData);
		});
	});
	
	$( "#byRating" ).click(function(){
		$("#pRec").empty().fadeIn();
		var query = window.location.search.substring(1).split('=');
		var type = query[1];
		$.post('controller.php', { action: "byRating", type: type }, function(data){
			var json = $.parseJSON(data);
			var jsonData = "";
			json.recipes = _.sortBy(json.recipes, ['rating', 'asc']);
			$.each(json.recipes, function(index, recipe){
				jsonData += '<li><a href="recipeDetails.php?recipeId=' + recipe.recipe_id + '">';
				jsonData += '<img src="' + recipe.recipelink + '" alt="' + recipe.name + '"><span></span><p>' + recipe.name + '</p></a></li>';
			});
			$("#pRec").append(jsonData);
		});
	});
	
	$( 'body' ).on( 'click', 'footer #backToSearch', function(){
		location.href = "possibleRecipes.php?recipe=search";
	});
	
	$( '#rhead' ).on( 'click', '#heart', function () {
		if(heartCheck ==1 ){
			$(this).attr("src", "images/heart2.png");
			heartCheck=2;
			var name = "save";
			var query = window.location.search.substring(1).split('=');
			var rId = query[1];		
			$.post('controller.php', { action : name, recipeId : rId }, function(data){});
		}
		else{
			$(this).attr("src", "images/heart1.png");
			heartCheck=1;
			var name = "delete";
			var query = window.location.search.substring(1).split('=');
			var rId = query[1];
			$.post('controller.php', { action : name, recipeId : rId }, function(data){});
		}
		
		$(this).fadeIn('fast');
	});
});
