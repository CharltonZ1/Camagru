<?php

include ("../config/setup.php");

function recovery($email, $code)
{
    $body 		= "Hi," . "\nYour Recovery Code is the following : " . $code . "\nIf you didn't request a Recovery Code, please ignore this message.";
    mail($email,"Password Recovery for Camagru",$body, "From: no-reply@camagru.com\r\n");
}

function recoverPassword($email, $password, $db)
{
    $found 		= "email";

    $DBemail 	= $db->quote($email);
    $DBpassword = $db->quote(hash("whirlpool", $password));

    $select 	= $db->query("SELECT * FROM users WHERE email=$DBemail");
    $ret 		= $select->rowCount();
    if ($ret != 0) {
        $found 	= "found";
        $db->query("UPDATE users
			SET password=$DBpassword
			WHERE email=$DBemail");
    }
    return ($found);
}

$email 		= $_POST['email'];
$code 		= $_POST['code'];
$action 	= $_POST['action'];
$password 	= $_POST['password'];

if ($action === "mailCode")
    recovery($email, $code);

if ($action === "changePass")
{
    $passCheck = TRUE;
    switch ($passCheck) {
        case (strlen($password) < 8):
            $passCheck = FALSE;
            $return = "len";
            break;
        case (!preg_match("#[0-9]+#", $password)):
            $passCheck = FALSE;
            $return = "nbr";
            break;
        case (!preg_match("#[a-zA-Z]+#", $password)):
            $passCheck = FALSE;
            $return = "alpha";
            break;
        case ($passCheck == TRUE):
            $return = recoverPassword($email, $password, $db);
            break;
    }
}

echo $return;

?>