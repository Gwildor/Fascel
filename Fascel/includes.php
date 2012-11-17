<?php

if (isset($Fascel['vars'])) {
	$Fascel['config'] = array();
	$Fascel['constants'] = array();
} else {
	$Fascel = array('config' => array(), 'vars' => array(), 'constants' => array());
}

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



if (!isset($Fascel['vars']['Fascel_dir'])) {
	$Fascel['vars']['Fascel_dir'] = true;
}

$Fascel['vars']['t_re'] = sqlesc($Fascel['config']['table_namespace'].$Fascel['config']['table_names']['releases']);
$Fascel['vars']['t_ch'] = sqlesc($Fascel['config']['table_namespace'].$Fascel['config']['table_names']['changes']);



if (is_string($Fascel['config']['jQuery'])) {
	if ($Fascel['config']['jQuery'][0] != '/' && $Fascel['vars']['Fascel_dir']) {
		$Fascel['vars']['jQ'] = '../'.$Fascel['config']['jQuery'];
	} else {
		$Fascel['vars']['jQ'] = $Fascel['config']['jQuery'];
	}
	?>
	<script type="text/javascript" src="<?php echo $Fascel['vars']['jQ'];?>"></script>
	<?php
}

if (is_string($Fascel['config']['jQueryUI'])) {
	if ($Fascel['config']['jQueryUI'][0] != '/' && $Fascel['vars']['Fascel_dir']) {
		$Fascel['vars']['jQUI'] = '../'.$Fascel['config']['jQueryUI'];
	} else {
		$Fascel['vars']['jQUI'] = $Fascel['config']['jQueryUI'];
	}
	?>
	<script type="text/javascript" src="<?php echo $Fascel['vars']['jQUI'];?>"></script>
	<?php
}

if (is_string($Fascel['config']['jQueryUICSS'])) {
	if ($Fascel['config']['jQueryUICSS'][0] != '/' && $Fascel['vars']['Fascel_dir']) {
		$Fascel['vars']['jQUICSS'] = '../'.$Fascel['config']['jQueryUICSS'];
	} else {
		$Fascel['vars']['jQUICSS'] = $Fascel['config']['jQueryUICSS'];
	}
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo $Fascel['vars']['jQUICSS'];?>" />
	<?php
}

if (is_string($Fascel['config']['jQueryTimepickerAddon'])) {
	if ($Fascel['config']['jQueryTimepickerAddon'][0] != '/' && $Fascel['vars']['Fascel_dir']) {
		$Fascel['vars']['jQUITA'] = '../'.$Fascel['config']['jQueryTimepickerAddon'];
	} else {
		$Fascel['vars']['jQUITA'] = $Fascel['config']['jQueryTimepickerAddon'];
	}
	?>
	<script type="text/javascript" src="<?php echo $Fascel['vars']['jQUITA'];?>"></script>
	<?php
}

?>
