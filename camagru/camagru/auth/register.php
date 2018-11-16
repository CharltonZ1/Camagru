<?php

include("../assets/builders.php");

if (isset($_SESSION['user']))
	header("Location: ../");

?>
<html>
<head>
	<?php
	head(2);
	?>	
</head>
<body>
	<header>
		<h1 class="title">Camagru</h1>
		<ul>
			<li><a class="active" href="../index">Home</a></li>
			<li><a class="" href="../selfie">Seflie !</a></li>
		</ul>
		<div class="loginBox">
			<button onclick="location.href='login';" class="loginButton Button">LOGIN</button>
			<button onclick="location.href='register';" class="registerButton Button">REGISTER</button>
		</div>
	</header>
	<div class="registerModal">
			<form class="registerForm" action="auth" method="post">
				<span class="authLabel">Username</span> <br>
				<input type="text" name="username" required><br><br>
				<span class="authLabel">Email address</span> <br>
				<input type="email" name="email" required><br><br>
				<span class="authLabel">Password</span> <br>
				<input type="password" name="passwd" required><br><br>
				<input class="Button authButton" type="submit" name="registerSubmit" value="Sign Up"><br><br>
				<?php
				if (!isset($_SESSION['regInfo']))
				{
					?>
					<span class="authLabel">Already have an account ? <a class="red" href="login">LOGIN</a> now !</span> <br>
					<?php
				}
				if ($_SESSION['regInfo'] === "username")
				{
					unset($_SESSION['regInfo']);
					?>
					<span class="authLabel">This username is <span class="red">NOT</span> available !</span> <br>
					<?
				}
				if ($_SESSION['regInfo'] === "email")
				{
					unset($_SESSION['regInfo']);
					?>
					<span class="authLabel">This email is <span class="red">already</span> in use !</span> <br>
					<?php
				}
				if ($_SESSION['regPass']['len'] == TRUE)
				{
					unset($_SESSION['regPass']);
					?>
					<span class="authLabel">Password has to be at <span class="red">LEAST</span> 8 characters long !</span> <br>
					<?php
				}
				if ($_SESSION['regPass']['nbr'] == TRUE)
				{
					unset($_SESSION['regPass']);
					?>
					<span class="authLabel">Password must at <span class="red">LEAST</span> contain one number !</span> <br>
					<?php
				}
				if ($_SESSION['regPass']['alpha'] == TRUE)
				{
					unset($_SESSION['regPass']);
					?>
					<span class="authLabel">Password must at <span class="red">LEAST</span> contain one letter !</span> <br>
					<?php
				}
				?>
			</form>
		</div>
</body>
</html>