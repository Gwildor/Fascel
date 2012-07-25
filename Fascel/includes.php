<?php

require_once 'mysql.php';
mysql_select_db($mysql_db, mysql_connect($mysql_ip, $mysql_user, $mysql_pw));

function sqlesc($str) {
	return mysql_real_escape_string($str);
}

function query($query) {
	$sql = mysql_query($query); // execute query
	if (mysql_errno()) { // errors?
		$backtrace = debug_backtrace();
		die($backtrace[0]['line'].' '.$backtrace[0]['file'].' '.mysql_error());
	} else {
		return $sql; // no errors → return reference object
	}
}

?>
