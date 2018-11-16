<?php

include("builders.php");

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
        <li><a class="" href="../selfie">Snap Me!</a></li>
    </ul>
    <div class="loginBox">
        <button onclick="location.href='../auth/login';" class="loginButton Button">LOGIN</button>
        <button onclick="location.href='../auth/register';" class="registerButton Button">REGISTER</button>
    </div>
</header>
<div class="recoveryModal">
    <div class="loginForm">
        <span name="label" id="label" class="authLabel">Enter your email</span> <br />
        <input type="text" name="inputs" id="inputs"><br /><br />
        <input type="submit" class="Button authButton" value="Send Recovery Email" id="recovery"><br />
        <span style="position:relative;top:3;" name="email" id="emailLabel" class="authLabel"></span><br />
    </div>
</div>
</body>
<script src="../js/recovery.js"></script>
</html>