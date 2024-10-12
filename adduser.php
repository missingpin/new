<?php
include 'connect.php';

if (isset($_POST['usernameSend']) && isset($_POST['passwordSend']) && isset($_POST['emailSend'])) {

    $usernameSend = $_POST['usernameSend'];
    $passwordSend = $_POST['passwordSend'];
    $emailSend = $_POST['emailSend'];

    $sql = "INSERT INTO form (email, username, password) values ('$emailSend', '$usernameSend', '$passwordSend')";
    mysqli_query($con, $sql);
}
else{
    echo "error";
}
?>