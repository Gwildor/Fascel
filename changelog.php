<?php

$Fascel = array('vars' => array('Fascel_dir' => false));
require_once 'Fascel/includes.php';


// See which versions the user wants to view.
$Fascel['vars']['errors'] = false;
$Fascel['vars']['version1'] = '';
$Fascel['vars']['version2'] = '';
if (isset($_POST['submit'])) {

	if (empty($_POST['version_1']) || !is_numeric($_POST['version_1'])) {
		$Fascel['vars']['errors'] = true;
		?><div id="fascel_changelog_empty_version_1">Please select a version to view the changelog of.</div><?php
	}
	if (empty($_POST['version_2']) || !is_numeric($_POST['version_2'])) {
		$Fascel['vars']['errors'] = true;
		?><div id="fascel_changelog_empty_version_2">Please select a version to compare the version against.</div><?php
	}

	if (!$Fascel['vars']['errors']) {
		$Fascel['vars']['version1'] = $_POST['version_1'];
		$Fascel['vars']['version2'] = $_POST['version_2'];
	}

}

// Set default versions.
if (empty($Fascel['vars']['version1']) || empty($Fascel['vars']['version2'])) {
	$Fascel['vars']['first_set']  = false;
	$Fascel['vars']['second_set'] = false;
	$Fascel['vars']['sql'] = query("SELECT * FROM `".$Fascel['vars']['t_re']."` ORDER BY `ts` DESC, `id` DESC");
	while ($Fascel['vars']['row'] = mysql_fetch_assoc($Fascel['vars']['sql'])) {
		if (empty($Fascel['vars']['version1'])) {
			$Fascel['vars']['version1'] = $Fascel['vars']['row']['id'];
		}
		if (empty($Fascel['vars']['version2']) && $Fascel['vars']['first_set'] && !$Fascel['vars']['second_set']) {
			$Fascel['vars']['version2'] = $Fascel['vars']['row']['id'];
		}
		if ($Fascel['vars']['version1'] == $Fascel['vars']['row']['id']) { // $Fascel['vars']['version1'] can be set as well when handling the $_POST data, so seperate if.
			$Fascel['vars']['first_set'] = true;
		}
		if ($Fascel['vars']['version2'] == $Fascel['vars']['row']['id']) {
			$Fascel['vars']['second_set'] = true;
		}
	}
}

// Still not done well? Fine, let's just use same version.
if (empty($Fascel['vars']['version2'])) {
	$Fascel['vars']['version2'] = $Fascel['vars']['version1'];
}

/*
 * Select timeframe. Perhaps in the future this should be changed into
 * using id's or something like that, for futures where no release date
 * is known.
 */
$Fascel['vars']['sql'] = query("SELECT `ts` FROM `".$Fascel['vars']['t_re']."` WHERE `id` = '".sqlesc($Fascel['vars']['version1'])."' LIMIT 1");
$Fascel['vars']['row'] = mysql_fetch_assoc($Fascel['vars']['sql']);
$Fascel['vars']['ts1'] = $Fascel['vars']['row']['ts'];

if ($Fascel['vars']['version1'] == $Fascel['vars']['version2']) {
	$Fascel['vars']['ts2'] = $Fascel['vars']['ts1'];
	$Fascel['vars']['gt']  = '>=';
} else {
	$Fascel['vars']['gt']  = '>';
	$Fascel['vars']['sql'] = query("SELECT `ts` FROM `".$Fascel['vars']['t_re']."` WHERE `id` = '".sqlesc($Fascel['vars']['version2'])."' LIMIT 1");
	$Fascel['vars']['row'] = mysql_fetch_assoc($Fascel['vars']['sql']);
	$Fascel['vars']['ts2'] = $Fascel['vars']['row']['ts'];
}

if ($Fascel['vars']['ts2'] > $Fascel['vars']['ts1']) { // User accidentally the whole thing → switch versions.
	$Fascel['vars']['ts_temp'] = $Fascel['vars']['ts1'];
	$Fascel['vars']['ts1'] = $Fascel['vars']['ts2'];
	$Fascel['vars']['ts2'] = $Fascel['vars']['ts_temp'];
	$Fascel['vars']['vers_temp'] = $Fascel['vars']['version1'];
	$Fascel['vars']['version1']  = $Fascel['vars']['version2'];
	$Fascel['vars']['version2']  = $Fascel['vars']['vers_temp'];
}

?>

<form id="fascel_changelog_form" method="post">

	<div id="fascel_changelog_versions">
		View changes of version
		<select name="version_1">

			<?php

			// Assemble HTML for first dropdown.
			$Fascel['vars']['sql'] = query("SELECT * FROM `".$Fascel['vars']['t_re']."` ORDER BY `ts` DESC, `id` DESC");
			while ($Fascel['vars']['row'] = mysql_fetch_assoc($Fascel['vars']['sql'])) {
				echo "\n".'			<option value="'.$Fascel['vars']['row']['id'].'"';
				if ($Fascel['vars']['row']['id'] == $Fascel['vars']['version1']) {
					echo ' selected="selected"';
				}
				echo '>'.$Fascel['vars']['row']['version'];
				if (!empty($Fascel['vars']['row']['codename'])) {
					echo ' "'.$Fascel['vars']['row']['codename'].'"';
				}
				echo '</option>';
			}

			?>

		</select>
		since the release of version
		<select name="version_2">

			<?php

			// Assemble HTML for second dropdown.
			$Fascel['vars']['sql'] = query("SELECT * FROM `".$Fascel['vars']['t_re']."` ORDER BY `ts` DESC, `id` DESC");
			while ($Fascel['vars']['row'] = mysql_fetch_assoc($Fascel['vars']['sql'])) {
				echo "\n".'			<option value="'.$Fascel['vars']['row']['id'].'"';
				if ($Fascel['vars']['row']['id'] == $Fascel['vars']['version2']) {
					echo ' selected="selected"';
				}
				echo '>'.$Fascel['vars']['row']['version'];
				if (!empty($Fascel['vars']['row']['codename'])) {
					echo ' "'.$Fascel['vars']['row']['codename'].'"';
				}
				echo '</option>';
			}

			?>

		</select>
	</div>

	<input type="submit" name="submit" value="View changelog" />

</form>

<?php

// Fetch changes.
$Fascel['vars']['sql'] = query("SELECT `ch`.`type` as `type`, `ch`.`change` as `change` FROM `".$Fascel['vars']['t_re']."` AS `re`, `".$Fascel['vars']['t_ch']."` AS `ch` WHERE `re`.`id` = `ch`.`id` AND `re`.`ts` <= ".$Fascel['vars']['ts1']." AND `re`.`ts` ".$Fascel['vars']['gt']." ".$Fascel['vars']['ts2']." ORDER BY `ch`.`type` ASC, `re`.`ts` DESC, `re`.`id` DESC");

$Fascel['vars']['curtype'] = 0;
while ($Fascel['vars']['row'] = mysql_fetch_assoc($Fascel['vars']['sql'])) {
	if ($Fascel['vars']['curtype'] != $Fascel['vars']['row']['type']) {

		if ($Fascel['vars']['curtype'] != 0) {
			echo '
				</ul>
			</div>';
		}

		echo "\n".'<div id="fascel_changelog_type_'.$Fascel['vars']['row']['type'].'">';

		$Fascel['vars']['curtype'] = $Fascel['vars']['row']['type'];
		if ($Fascel['vars']['row']['type'] == 1) {
			echo "\n\t".'<h2>Added:</h2>';
		}
		if ($Fascel['vars']['row']['type'] == 2) {
			echo "\n\t".'<h2>Changed:</h2>';
		}
		if ($Fascel['vars']['row']['type'] == 3) {
			echo "\n\t".'<h2>Fixed:</h2>';
		}
		echo "\n\t".'<ul>';

	}
	echo "\n\t\t".'<li>'.$Fascel['vars']['row']['change'].'</li>';

}
echo '
	</ul>
</div>';

// Footer
if ($Fascel['config']['footer'] !== false) {
	echo '<div id="fascel_footer">'.$Fascel['config']['footer'].'</div>';
}

?>
