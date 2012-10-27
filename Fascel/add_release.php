<?php

require_once 'includes.php';

if (isset($_POST['submit'])) {

	//echo '<pre>';print_r($_POST);echo '</pre>';

	// Check if version already exists.
	$Fascel['vars']['sql'] = query("SELECT `id` FROM `Fascel_releases` WHERE `version` = '".sqlesc($_POST['version'])."' LIMIT 1");
	if (mysql_num_rows($Fascel['vars']['sql']) == 0) {
		$Fascel['vars']['sql'] = query("SELECT `id`, `version` FROM `Fascel_releases` ORDER BY `ts` DESC LIMIT 1");
		if (mysql_num_rows($Fascel['vars']['sql']) == 0) {
			$Fascel['vars']['id'] = 1;
		} else {
			$Fascel['vars']['row'] = mysql_fetch_assoc($Fascel['vars']['sql']);
			$Fascel['vars']['id']  = $Fascel['vars']['row']['id'] + 1;
		}

		query("INSERT INTO `Fascel_releases` (`id`, `version`, `codename`, `ts`) VALUES (".$Fascel['vars']['id'].", '".sqlesc($_POST['version'])."', '".sqlesc($_POST['codename'])."', ".$_POST['datetime'].")");
	} else {
		$Fascel['vars']['row'] = mysql_fetch_assoc($Fascel['vars']['sql']);
		$Fascel['vars']['id']  = $Fascel['vars']['row']['id'];
	}

	// Insert the changes.
	foreach ($_POST['changes'] as $Fascel['vars']['key'] => $Fascel['vars']['changes']) {
		if (!empty($Fascel['vars']['changes'])) {
			$Fascel['vars']['changes'] = explode("\n", preg_replace('/(\r\n|\r|\n)/', "\n", $Fascel['vars']['changes']));
			foreach ($Fascel['vars']['changes'] as $Fascel['vars']['change']) {
				query("INSERT INTO `Fascel_changes` (`id`, `type`, `change`) VALUES (".$Fascel['vars']['id'].", ".$Fascel['vars']['key'].", '".sqlesc($Fascel['vars']['change'])."')");
			}
		}
 	}

	?>
		<div id="fascel_release_added_msg">Release has been added.</div>
	<?php
}

?>

<form id="fascel_add_release_form" method="post">

	<div id="fascel_add_release_version_number">
		<div>Version number:</div>
		<div><input type="text" name="version" placeholder="e.g.: 1.0rc2" /></div>
	</div>

	<div id="fascel_add_release_codename">
		<div>Codename:</div>
		<div><input type="text" name="codename" placeholder="optional" /></div>
	</div>

	<div id="fascel_add_release_datetime">
		<div>Release date and time:</div>
		<div><input type="datetime" name="datetime" /></div>
	</div>

	<div id="fascel_add_release_added">
		<div>Added:</div>
		<div><textarea name="changes[1]" placeholder="one per line"></textarea></div>
	</div>

	<div id="fascel_add_release_changed">
		<div>Changed:</div>
		<div><textarea name="changes[2]" placeholder="one per line"></textarea></div>
	</div>

	<div id="fascel_add_release_fixed">
		<div>Fixed:</div>
		<div><textarea name="changes[3]" placeholder="one per line"></textarea></div>
	</div>

	<input type="submit" name="submit" value="Add" />

</form>

<?php

?>
