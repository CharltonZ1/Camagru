<?php

include("../config/setup.php");

function mergeIMG($data, $filter, $user, $tmp, $uploaded) {
    list($type, $data) = explode(';', $data);
    list(, $data)      = explode(',', $data);
    $data = base64_decode($data);

    if (isset($uploaded)) {
        $filecheck = pathinfo($uploaded);
        $extension = $filecheck['extension'];
    }

    if ($extension === "png" || !isset($uploaded)) {
        $rpath 	= 'tmp/' . $user . 'tmp' . $tmp . '.png';
        $path 	= '../' . $rpath;
        file_put_contents($path, $data);

        $og 	= imagecreatefrompng($path);
        $src 	= imagecreatefrompng($filter);

        $result = imagecreatetruecolor(500, 375);

        imagealphablending($og, false);
        imagesavealpha($og, true);
        $trans_background = imagecolorallocatealpha($result, 0, 0, 0, 127);
        imagefill($result, 0, 0, $trans_background);

        imagecopy($result, $og, 0, 0, 0, 0, 500, 375);
        imagecopy($result, $src, 0, 0, 0, 0, 500, 375);


        imagepng($result, $path);

        imagedestroy($og);
        imagedestroy($src);
        imagedestroy($result);
        echo $rpath;
    }
    else
        echo "ERROR";
}

function noSaveIMG($user, $tmp) {
    $rpath 	= 'tmp/' . $user . 'tmp' . $tmp . '.png';
    $path 	= '../' . $rpath;

    unlink($path);
}

function saveIMG($user, $tmp, $db) {
    $rpath 			= 'tmp/' . $user . 'tmp' . $tmp . '.png';
    $path 			= '../' . $rpath;

    $DBuser 		= $db->quote($user);
    $select 		= $db->query("SELECT * FROM users WHERE username=$DBuser");
    $ret 			= $select->rowCount();
    if ($ret == 1) {
        $result 	= $select->fetchAll();
        $id 		= $result[0]['id'];
    }
    else
        die("Error - Please relog and clear your cookies");
    $image = imagecreatefrompng($path);
    $files = glob("../UsersIMG/" . $user . "/*.png");
    if ($files !== FALSE)
        $filecount 	= count($files);
    else
        $filecount 	= 0;

    $savepath 		= "./UsersIMG/" . $user . "/" . $user . "_" . $filecount . ".png";
    imagepng($image, "./../" . $savepath);
    $db->query("INSERT INTO images SET user_id='$id', im_path='$savepath', likes=0");
    unlink($path);
}

function deleteIMG($user, $path, $db) {
    $DBpath = $db->quote($path);

    $select = $db->query("SELECT * FROM images WHERE im_path=$DBpath");
    $result = $select->fetchAll();
    $imgID 	= $result[0]['id'];

    $db->query("DELETE FROM comments WHERE image_id='$imgID'");
    $db->query("DELETE FROM liked WHERE image_id='$imgID'");
    $db->query("DELETE FROM images WHERE im_path=$DBpath");
    $Rpath = "../" . $path;
    unlink($Rpath);
}

session_start();

$action = $_POST['action'];
$data = $_POST['data'];
$filter = $_POST['filter'];
$user = $_SESSION['user'];
$tmp = $_POST['tmp'];
$path = $_POST['path'];
$uploaded = $_POST['uploaded'];

if ($action === "merge")
    mergeIMG($data, $filter, $user, $tmp, $uploaded);
else if ($action == "delete")
    noSaveIMG($user, $tmp);
else if ($action == "save")
    saveIMG($user, $tmp, $db);
else if ($action == "trash")
    DeleteIMG($user, $path, $db);
?>