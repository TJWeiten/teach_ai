<?php
$select_db_error = '<b>Sorry, we\'re experiencing problems selecting the database!</b>';
$connect_error = '<b>Sorry, we\'re experiencing problems connecting to the database!</b>';
mysql_connect('localhost', 'root', 'X7TWLv9hSPbEWvtF') or die($connect_error);
mysql_select_db('ai') or die($select_db_error);
?>