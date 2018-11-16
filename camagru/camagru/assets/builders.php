<?php

function head($index) {

    if ($index === 1) {
        $title 		= "<title>Camagru</title>";
        $charset 	= "<meta charset='UTF-8'>";
        $cssf 		= "style.css";
        $css 		= "<link rel='stylesheet' type='text/css' href='css/" . $cssf . "'>";
        $fav_type 	= "icon";
        $fav_file 	= "fav.ico";
        $favicon 	= "<link rel='icon' type='image/" . $fav_type . "' href='../img/" . $fav_file . "'>";
        $viewport	= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        $footer		= '<footer class="footer">Camagru by chadams@student.wethinkcode.co.za</footer>';
    }
    else if ($index === 2) {
        $title 		= "<title>Camagru</title>";
        $charset 	= "<meta charset='UTF-8'>";
        $cssf 		= "style.css";
        $css 		= "<link rel='stylesheet' type='text/css' href='../css/" . $cssf . "'>";
        $fav_type 	= "ico";
        $fav_file 	= "fav.icon";
        $favicon 	= "<link rel='icon' type='image/" . $fav_type . "' href='img/" . $fav_file . "'>";
        $viewport	= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        $footer		= '<footer class="footer">Camagru by chadams@student.wethinkcode.co.za</footer>';
    }
    echo $charset, $viewport, $css, $favicon, $title, $footer;
}

function galleryPopulate($username, $db) {

    $DBuser 			= $db->quote($username);
    $select 			= $db->query("SELECT * FROM users WHERE username=$DBuser");
    if ($select->rowCount() == 1)
    {
        $result 		= $select->fetchAll();
        $id 			= $result[0]['id'];
    }
    $select 			= $db->query("SELECT * FROM images WHERE user_id='$id' ORDER BY id DESC");
    $ret 				= $select->rowCount();
    if ($ret != 0) {
        $rows 			= $select->fetchAll();
        $floatStart 	= '<div class="floating-box">';
        $floatEnd 		= '</div>';
        $imageStart 	= '<img ondblclick="trashImage(this.id)" width="250" height="185" src="';
        $imageID 		= '" id="';
        $imageMid 		= '">';
        $imageEnd 		= '</img>';

        foreach ($rows as $key => $elem) {
            echo $floatStart, $imageStart, $elem['im_path'], $imageID, $elem['im_path'], $imageMid, $imageEnd, $floatEnd, "<br />";
        }
    }
}

function frontButton($info) {
    $page					= $info;
    $next 					= $page - 1;
    $previous 				= $page + 1;

    $buttonPreviousStart 	= '<button class="registerButton Button previous"><a href="index?page=';
    $buttonPreviousEnd 		= '"><</a></button>';
    $buttonCurrentStart 	= '<button class="registerButton Button previous">';
    $buttonCurrentEnd 		= '</button>';
    $buttonNextStart 		= '<button class="registerButton Button previous"><a href="index?page=';
    $buttonNextEnd 			= '">></a></button>';
    if ($page > 1)
        echo $buttonPreviousStart, $next, $buttonPreviousEnd, $buttonCurrentStart, $page, $buttonCurrentEnd, $buttonNextStart, $previous, $buttonNextEnd;
    else
        echo $buttonCurrentStart, $page, $buttonCurrentEnd, $buttonNextStart, $previous, $buttonNextEnd;
}

function frontGallery($db, $page) {
    $bValDOWN			= 1 + 6 * ($page - 1);
    $bValUP				= 6 + 6 * ($page - 1);
    $bValDOWN			-= 1;
    $bValUP				-= 1;
    $select 			= $db->query("SELECT * FROM images ORDER BY pub_time DESC");
    $ret 				= $select->rowCount();
    if ($ret > 0)
    {
        $rows			= $select->fetchAll();
        $floatStart		= '<div class="front-floating-box">';
        $floatEnd 		= '</div>';
        $imageStart 	= '<img ondblclick="likeImage(this.id)" width="500" height="375" src="';
        $imageID 		= '" id="';
        $imageMid 		= '">';
        $imageEnd 		= '</img>';
        $h1Start 		= '<h1 class="author">By: ';
        $h1End 			= '</h1>';
        $likesStart 	= '<h1 class="likes">Likes: <span id="likescount_';
        $likesName 		= '">';
        $likesEnd 		= '</span></h1>';
        $commentsStart 	= '<h1 onclick="redirectComments(this)" class="comments" name="';
        $commentsMid 	= '">Comments (';
        $commentsEnd 	= ')</h1>';

        foreach ($rows as $key => $elem) {
            $id 			= $elem['user_id'];
            $users 			= $db->query("SELECT * FROM users WHERE id='$id'");
            $result 		= $users->fetchAll();
            $user 			= $result[0]['username'];
            $imgID 			= $elem['id'];
            $select 		= $db->query("SELECT * FROM comments WHERE image_id='$imgID'");
            $commentCount 	= $select->rowCount();

            if ($key >= $bValDOWN && $key <= $bValUP)
                echo $floatStart, $imageStart, $elem['im_path'], $imageID, $elem['im_path'], $imageMid, $imageEnd, $commentsStart, $elem['im_path'], $commentsMid, $commentCount, $commentsEnd, $h1Start, $user, $h1End, $likesStart, $elem['im_path'], $likesName, $elem['likes'], $likesEnd, $floatEnd;
        }
    }
}

function commentPopulate($image, $db) {
    $DBimage 		= $db->quote($image);
    $select 		= $db->query("SELECT * FROM images WHERE im_path=$DBimage");
    $result 		= $select->fetchAll();
    $imgID 			= $result[0]['id'];

    $displayStart 	= "<img class='commentDisplay' src='$image' id='image' width='500' height='375' name='";
    $displayEnd 	= "'></img>";
    $commentsTitle 	= "<h1 class='commentsTitle'>Comments</h1>";
    $commentsDiv 	= "<div class='commentsDiv' id='commentsDiv'>";
    $commentsEndDiv = "</div>";
    $inputComment 	= "<form><input class='input-floating-box' style='width:389px;outline: none; text-align:left;' type='text' id='input'><input class='Button commentButton' id='submit' type='submit' value='SEND'></form>";
    echo $displayStart, $image, $displayEnd, $commentsTitle, $commentsDiv, $inputComment;

    $select 			= $db->query("SELECT * FROM comments WHERE image_id='$imgID'");
    if ($select->rowCount() > 0) {
        $commentsList 	= $select->fetchAll();
        foreach ($commentsList as $key => $elem) {
            $comment 	= $elem['content'];
            $tmpID 		= $elem['user_id'];
            $tmpID 		= $db->query("SELECT * FROM users WHERE id='$tmpID'");
            $ret 		= $tmpID->fetchAll();
            $user 		= $ret[0]['username'];
            echo "<p class='aComment'>", $user, ": ", $comment, "</p>";
        }
    }

    echo $commentsEndDiv;
}

function username() {
    echo $_SESSION['user'];
}

session_start();

?>