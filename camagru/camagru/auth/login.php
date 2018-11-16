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
	<div class="loginModal">
			<form class="loginForm" action="auth.php" method="post">
				<span class="authLabel">Username</span> <br />
				<input type="text" name="username" required><br /><br />
				<span class="authLabel">Password</span> <br />
				<input type="password" name="passwd" required><br /><br />
				<input class="Button authButton" type="submit" name="loginSubmit" value="Sign In"><br /><br />
				<?php
				if (!isset($_SESSION['logInfo']))
				{
					?>
					<span class="authLabel">You don't have an account yet ? <a class="red" href="register">REGISTER</a> now !</span> <br />
					<?php
				}
				if ($_SESSION['logInfo'] === "registered")
				{
					unset($_SESSION['logInfo']);
					?>
					<span class="authLabel">Thanks for registering, please <span class="red">VERIFY</span> your email to log in !</span> <br />
					<?php
				}
				if ($_SESSION['logInfo'] === "credentials")
				{
					unset($_SESSION['logInfo']);
					?>
					<span class="authLabel">The credentials provided are <span class="red">INCORRECT</span> !</span> <br />
					<?php
				}
				if ($_SESSION['logInfo'] === "verification")
				{
					unset($_SESSION['logInfo']);
					?>
					<span class="authLabel">Please verify your <span class="red">EMAIL</span> to log in !</span> <br />
					<?php
				}
				if ($_SESSION['logInfo'] === "redirect")
				{
					unset($_SESSION['logInfo']);
					?>
					<span class="authLabel">Please <span class="red">LOG IN</span> to proceed !</span> <br />
					<?php
				}
				?>
				<span class="authLabel">Forgot your password ? <a class="red" href="../assets/recovery">RECOVER</a> it now !</span> <br />
			</form>
		</div>
</body>
</html>