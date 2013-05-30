<?php
$select_db_error = '<b>Sorry, we\'re experiencing problems selecting the database!</b>';
$connect_error = '<b>Sorry, we\'re experiencing problems connecting to the database!</b>';
mysql_connect('localhost', 'username', 'password') or die($connect_error);
mysql_select_db('database') or die($select_db_error);
?>