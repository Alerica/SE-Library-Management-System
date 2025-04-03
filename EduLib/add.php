

<?php
include "sql.php";

$ID = "AD001";
$phone =  81806170710 ;
$username = "Ray";
$password = "ANJAY123";
$email = "ray123@gmail.com";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO visitor (UserID, Name , Email , Phone , Password) VALUES ('$ID','$username', '$email','$phone', '$hashed_password')";
$conn->query($sql);

?>



