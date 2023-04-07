<?php
session_start();
include "include/connection.php";

$title = $_POST['newTitle'];
$description = $_POST['newDescription'];
$userid = $_SESSION['userid'];
$sid = $_POST['sublueddit'];
$link = $_POST['newLink'];

//to prevent form mysqli injection  
$title = stripcslashes($title);
$title = mysqli_real_escape_string($conn, $title);
$description = stripcslashes($description);
$description = mysqli_real_escape_string($conn, $description);

$link = stripcslashes($link);
$link = mysqli_real_escape_string($conn, $link);

//Insert into database and determine if successful
//For now all posts go into the subblueddit with ID 1,
//but this should be changed once subblueddits work. 
if (!empty($link)){
    $sql = "INSERT INTO posts (title, content, userid, sid, link)
    Values ('$title', '$description', $userid, $sid, '$link')";
}
else {
    $sql = "INSERT INTO posts (title, content, userid, sid)
    VALUES ('$title','$description', $userid, $sid);";
    echo $sql;
}
$success = mysqli_query($conn, $sql);


//If successful
if (!$success) {
    $_SESSION['posted'] = false;
    header('Location: newPost.php');
    mysqli_close($conn);
    die();
} else {
    //Track usage
    $sql = "INSERT INTO usageTracking (type, sid, entryDate)
    Values ('POST', $sid, CURDATE())";
    mysqli_query($conn, $sql);

    $_SESSION['posted'] = true;
    header('Location: index.php');
    mysqli_close($conn);
    die();
}

?>