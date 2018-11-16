<?php

include("../assets/builders.php");
include("../config/setup.php");

$verif = $_GET;
$salt = "a42b8over9000";
$email = $db->quote($verif['email']);
$token = $verif['token'];
$select = $db->query("SELECT * FROM users WHERE email=$email");
if ($select->rowCount() == 0)
    $status = "error";
else
{
    $select = $db->query("SELECT * FROM users WHERE email=$email AND verified=1");
    if ($select->rowCount() != 0)
        $status = "verified";
    else
    {
        $select = $db->query("SELECT * FROM users WHERE email=$email AND verified=0");
        if ($select->rowCount() == 1)
        {
            $result = $select->fetchAll();
            $vHash = $result[0]['vHash'];
            $username = $result[0]['username'];
            if (hash("whirlpool", $vHash . $salt) === $token)
            {
                $status = "success";
                $db->query("UPDATE users
                    SET verified=1
                    WHERE email=$email");
                @mkdir("../UsersIMG/" . $username);
            }
            else $status = "error";
        }
    }
}

?>

<html>
    <head>
    <?php
	head(2);
	?>	
        <meta http-equiv="refresh" content="5;url=login" />
    </head>
    <body>
    <?php

    if ($status === "error")
    {
    	?>
    	<center><span class="redirect">The email or verification token are incorrect.</span></center>
    	<?php
    }

    if ($status === "verified")
    {
    	?>
    	<center><span class="redirect">Your email is already verified !</span></center>
    	<?php
    }

    if ($status === "success")
    {
    	?>
    	<center><span class="redirect">Your email has been verified, you can now log in !</span></center>
    	<?php
    }

    ?>
		<center><span class="redirect">You will be redirected in 5 seconds, click <a class="red" href="login">here</a> to redirect manually !</span></center>
    </body>
</html>