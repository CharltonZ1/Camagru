<?php

include("assets/builders.php");
include("config/setup.php");
if (!isset($_SESSION['user']))
{
	$_SESSION['logInfo'] = "redirect";
	header("Location: auth/login");
}

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
			<li><a class="" href="index">Home</a></li>
			<li><a class="active" href="selfie">Snap me!</a></li>
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
	<form class="camForm">
			<input id="img1" class="black" type="radio" name="yoyo">Filter 1
			<input id="img2" class="black" type="radio" name="yoyo">Filter 2
			<input id="img3" class="black" type="radio" name="yoyo">No filter
	</form>
	<form>
		<input style="margin-top:-42;" type="file" id="uploadedFile">
	</form>
	
	<video id="video" class="camera"></video>
	<button id="smile" class="Button cameraButton">Smile !</button>
	<button id="delete" class="Button buttonDelete" style="visibility:hidden;">Delete</button>
	<button id="save" class="Button buttonSave" style="visibility:hidden;">Save</button>
	<canvas id="canvas" class="camera"></canvas>
	<div style="clear:both;">
	<h1 class="GalleryName">Double Click to Delete</h1>
	<div id="gallery"  class="gallery">
		<?php
		galleryPopulate($_SESSION['user'], $db);
		?>
	</div>
	</div>
	<script>
		function trashImage(path) {
			if (window.confirm('Are you sure ? This photo will be gone forever !'))
			{
				var xhr = new XMLHttpRequest();
				xhr.open('POST', 'assets/processing.php');
				xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				xhr.send('action=trash' +
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
		}
	</script>
	<script src="js/cam.js"></script>
</body>
</html>