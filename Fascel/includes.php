<?php

$Fascel = array('config' => array(), 'vars' => array(), 'constants' => array());

require_once 'constants.php';
require_once 'config.php';

if ($Fascel['config']['connect_to_database']) {
	$Fascel['mysql'] = array();
	require_once 'mysql.php';
	mysql_connect($Fascel['mysql']['ip'], $Fascel['mysql']['user'], $Fascel['mysql']['pw']);
	mysql_select_db($Fascel['mysql']['db']);
}

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

$Fascel['vars']['t_ns'] = sqlesc($Fascel['config']['table_namespace']);

?>
