<?php

include("../config/setup.php");

function verifyMail($username, $email, $vHash) {
	$token = hash("whirlpool", $vHash . "a42b8over9000");
	$body = "Hi " . $username .
	",\nThank you for registering at Camagru ! To log in on our site, please verify your email by clicking on the link below.\nhttp://127.0.0.1:8081/camagru/auth/verification?email=" . $email . "&token=" . $token;
	$headers = 'From:no-reply@camagru.com' . "\r\n";
	mail($email,"Signup | Verification",$body,$headers);
}

function registerUser($username, $email, $password, $db) {
	$serialized_file = "../private/users";
	$DBusername = $db->quote($username);
	$DBemail = $db->quote($email);
	$DBpassword = $db->quote(hash("whirlpool", $password));

	$select = $db->query("SELECT * FROM users WHERE username=$DBusername");
	if ($select->rowCount() != 0)
		$_SESSION['regInfo'] = "username";
	$select = $db->query("SELECT * FROM users WHERE email=$DBemail");
	if ($select->rowCount() != 0)
		$_SESSION['regInfo'] = "email";
	if (!isset($_SESSION['regInfo'])) {
		$vHash = hash("whirlpool", rand(0, 1000));
		$db->query("INSERT INTO users SET username=$DBusername, password=$DBpassword, email=$DBemail, vHash='$vHash'");
		$_SESSION['logInfo'] = "registered";
		verifyMail($username, $email, $vHash);
	}
}

function login($username, $password, $db) {
	$DBusername = $db->quote($username);
	$hashed = hash("whirlpool", $password);
	$DBpassword = $db->quote(hash("whirlpool", $password));

	$select = $db->query("SELECT * FROM users WHERE username=$DBusername AND password=$DBpassword");
	$res = $select->rowCount();
	if ($res != 0) {
		$_SESSION['user'] = $username;
		$select = $db->query("SELECT * FROM users WHERE username=$DBusername AND password=$DBpassword AND verified=0");
		$res = $select->rowCount();
		if ($res != 0) {
			unset($_SESSION['user']);
			$_SESSION['logInfo'] = "verification";
		}
	}
	else
		$_SESSION['logInfo'] = "credentials";
	if (!isset($_SESSION['user']) && $_SESSION['logInfo'] !== "verification")
		$_SESSION['logInfo'] = "credentials";
}

session_start();

$auth = $_POST;
if ($auth['registerSubmit'] && $auth['username'] && $auth['email'] && $auth['passwd'])
{
	$passwdchk = TRUE;
	switch ($passwdchk) {
		case (strlen($auth['passwd']) < 8):
			$passwdchk = FALSE;
			$_SESSION['regPass']['len'] = TRUE;
			break;
		case (!preg_match("#[0-9]+#", $auth['passwd'])):
			$passwdchk = FALSE;
			$_SESSION['regPass']['nbr'] = TRUE;
			break;
		case (!preg_match("#[a-zA-Z]+#", $auth['passwd'])):
			$passwdchk = FALSE;
			$_SESSION['regPass']['alpha'] = TRUE;
			break;
		case ($passwdchk == TRUE):
			registerUser($auth['username'], $auth['email'], $auth['passwd'], $db);
			break;
	}
}
else if ($auth['loginSubmit'] && $auth['username'] && $auth['passwd'])
	login($auth['username'], $auth['passwd'], $db);

if ($_SESSION['regPass']['len'] == FALSE || $_SESSION['regPass']['nbr'] == FALSE || $_SESSION['regPass']['alpha'] == FALSE)
	header("Location: register.php");
if (isset($_SESSION['user']) && !isset($_SESSION['logInfo']))
	header("Location: ../");
if (isset($_SESSION['logInfo']))
	header("Location: login.php");
if (isset($_SESSION['regInfo']))
	header("Location: register.php");

?>