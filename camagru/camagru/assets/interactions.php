<?php

include("../config/setup.php");

function checkLikes($user, $image, $db) {
    $DBpath 		= $db->quote($image);
    $DBuser 		= $db->quote($user);

    $select 		= $db->query("SELECT * FROM images WHERE im_path=$DBpath");
    $ret 			= $select->fetchAll();
    $imgID = $ret[0]['id'];

    $select 		= $db->query("SELECT * FROM users WHERE username=$DBuser");
    $ret 			= $select->fetchAll();
    $userID 		= $ret[0]['id'];

    $select 		= $db->query("SELECT * FROM liked WHERE image_id='$imgID' AND user_id='$userID'");
    $ret 			= $select->rowCount();
    if ($ret == 0) {
        $db->query("INSERT INTO liked SET user_id='$userID', image_id='$imgID'");
        $result 	= "ok";
    }
    else {
        $db->query("DELETE FROM liked WHERE image_id='$imgID' AND user_id='$userID'");
        $result 	= "ko";
    }

    $select 		= $db->query("SELECT * FROM liked WHERE image_ID='$imgID'");
    $likesCount = $select->rowCount();
    $db->query("UPDATE images SET likes='$likesCount' WHERE id='$imgID'");
    echo $result;
}

function sendComment($user, $comment, $image ,$db) {
    $DBcomment 		= $db->quote($comment);
    $DBuser 		= $db->quote($user);
    $DBpath 		= $db->quote($image);

    $select 		= $db->query("SELECT * FROM users WHERE username=$DBuser");
    if ($select->rowCount() == 1) {
        $result 	= $select->fetchAll();
        $userID 	= $result[0]['id'];
    }

    $select 		= $db->query("SELECT * FROM images WHERE im_path=$DBpath");
    if ($select->rowCount() == 1) {
        $result 	= $select->fetchAll();
        $imgID 		= $result[0]['id'];
    }
    $db->query("INSERT INTO comments SET user_id='$userID', image_id='$imgID', content=$DBcomment");

    $select = $db->query("SELECT * FROM images WHERE im_path=$DBpath");
    if ($select->rowcount() == 1) {
        $result 	= $select->fetchAll();
        $userID 	= $result[0]['user_id'];
    }

    $select 		= $db->query("SELECT * FROM users WHERE id='$userID'");
    if ($select->rowcount() == 1) {
        $result 	= $select->fetchAll();
        $to = $result[0]['email'];
    }
    
    $link 			= "http://127.0.0.1:8081/camagru/comments?image=" . $image;
    $body 			= "Hi !\nSomeone just commented one of your montages, check it out here : " . $link;

    mail($to, "Someone commented on your picture!", $body, "From: no-reply@camagru.com\r\n");
}

session_start();

$ajax = $_POST;
$user = $_SESSION['user'];

if ($ajax['action'] == "like" && isset($user))
    checkLikes($user, $ajax['image'], $db);
else if ($ajax['action'] == "comment" && isset($user))
    sendComment($user, $ajax['content'], $ajax['path'], $db);

?>