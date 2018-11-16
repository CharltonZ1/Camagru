<?php

include("assets/builders.php");
include("config/setup.php");

?>
<html>
<head>
	<?php
	head(1);
	?>
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
</head>
<body>
	<header>
		<h1 class="title">Camagru</h1>
		<ul>
			<li><a class="active" href="index">Home</a></li>
			<li><a class="" href="selfie">Snap Me!</a></li>
		</ul>
		<?php
		if (!isset($_SESSION['user']))
		{
		?>
		<div class="loginBox">
			<button onclick="location.href='auth/login';" class="loginButton Button">LOGIN</button>
			<button onclick="location.href='auth/register';" class="registerButton Button">REGISTER</button>
		</div>
		<?php
		}
		else {
			?>
		<div class="loginBox">
			<button class="loginButton Button"><?php username(); ?></button>
			<button onclick="location.href='auth/logout';" class="registerButton Button">LOGOUT</button>
		</div>
			<?php
		}
		?>
	</header>
	<?php
		commentPopulate($_GET['image'], $db);
	?>
	<script>
		var sub = document.getElementById('submit');
		var input = document.getElementById('input');
		var image = document.getElementById('image');
		var path = image.getAttribute("NAME");

		sub.addEventListener("click", function(ev) {
			var comment = input.value;
			if (comment) {
			  var xhr = new XMLHttpRequest();
			  xhr.open('POST', 'assets/interactions');
			  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			  xhr.send('action=comment' + 
			  	'&content=' + comment +
			  	'&path=' + path);
			  xhr.onreadystatechange = function () {
						var DONE = 4;
						var OK = 200;
						if (xhr.readyState === DONE) {
							if (xhr.status === OK) {
								location.reload();
							}
						}
					}
			}
			ev.preventDefault();
		});
	</script>
</body>
</html>