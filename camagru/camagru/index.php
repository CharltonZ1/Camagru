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
	<h1 class="title black centered">Some of our members!</h1>
	<?php
	if (isset($_GET['page']))
		frontButton($_GET['page']);
	else
		frontButton(1);
	?>
	<div class="frontGallery">
	<?php
	if (isset($_GET['page']))
		frontGallery($db, $_GET['page']);
	else
		frontGallery($db, 1);
	?>
	</div>
	<script>
		function likeImage(image) {
			var toLike = document.getElementById("likescount_" + image);
			var xhr = new XMLHttpRequest();
				xhr.open('POST', 'assets/interactions.php');
				xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				xhr.send('action=like' +
					'&image=' + image);
				xhr.onreadystatechange = function () {
					var DONE = 4;
					var OK = 200;
					if (xhr.readyState === DONE) {
						if (xhr.status === OK) {
							var output = xhr.responseText;
							if (output == "ok")
		        				toLike.innerHTML++;
		        			else if (output == "ko")
		        				toLike.innerHTML--;
						}
					}
				}
		}
		function redirectComments(image) {
			img = image.getAttribute("NAME");
			window.location.href = "comments.php?image=" + img;
		}
	</script>
</body>
</html>