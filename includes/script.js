$(window).load(function(){
	var heartCheck=1;
	$( "#login").click( function () {
		location.href = "login.php";
	});
	$( "#signup").click( function () {
		location.href = "signup.php";
	});

	$( "#pRecipes" ).click(function () {
		location.href = "possibleRecipes.php";
	});

	$( "#backB" ).click(function () {
		history.back();
	});
	
	$( "#profile" ).click(function () {
		location.href = "changeProfile.php";
	});
	
	$('#rhead').on('click', '#heart', function () {
		
		if(heartCheck ==1 ){
			$(this).attr("src", "images/heart2.png");
			heartCheck=2;
			var name = "save";
			var query = window.location.search.substring(1).split('=');
			var rId = query[1];			
			$.ajax({ 
				type: 'POST',				
				url: '/fridgeList/controller.php',
				data: { action : name, recipeId: rId},
				dataType: "html",
				success: function(output){
					alert(name);
				}
			});
		}
		else{
			$(this).attr("src", "images/heart1.png");
			heartCheck=1;
			var name = "delete";
			var query = window.location.search.substring(1).split('=');
			var recipeId = query[1];
			$.ajax({ 
				type: 'POST',				
				url: '/fridgeList/controller.php',
				data: { action : name, recipeId: rId },
				success: function(output){
					alert(name);
				}
			});
		}
		
		$(this).fadeIn('fast');

	});
	
});
