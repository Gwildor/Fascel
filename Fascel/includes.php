<?php

$Fascel = array('config' => array(), 'vars' => array(), 'constants' => array(), 'mysql' => array());

require_once 'constants.php';
require_once 'config.php';
require_once 'mysql.php';

mysql_select_db($Fascel['mysql']['db'], mysql_connect($Fascel['mysql']['ip'], $Fascel['mysql']['user'], $Fascel['mysql']['pw']));

function sqlesc($str) {
	return mysql_real_escape_string($str);
}

function query($query) {
	$sql = mysql_query($query); // Execute query.
	if (mysql_errno()) { // Errors?
		$backtrace = debug_backtrace();
		die($backtrace[0]['line'].' '.$backtrace[0]['file'].' '.mysql_error());
	} else {
		return $sql; // No errors → return reference object.
	}
}

?>
