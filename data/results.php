<?php
# Require init.php to get session information
require '../core/init.php';

$user_id = $_SESSION['user_id'];

$query = "SELECT `replay` FROM `games` WHERE `user_id` = $user_id";
$result = mysql_query($query);
$replay = mysql_fetch_row($result, MYSQL_NUM);

exec("java postconverted/TestResults " . $replay[0], $output);

echo $output[0];
?>

<body onload="parent.alertsize(document.body.scrollHeight);">