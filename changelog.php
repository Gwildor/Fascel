<?php

require_once 'Fascel/includes.php';


// See which versions the user wants to view.
$errors = false;
$version1 = '';
$version2 = '';
if (isset($_POST['submt'])) {

	if (empty($_POST['version_1']) || !is_numeric($_POST['version1'])) {
		$errors = true;
		?><div id="fascel_changelog_empty_version_1">Please select a version to view the changelog of.</div><?php
	}
	if (empty($_POST['version_2']) || !is_numeric($_POST['version2'])) {
		$errors = true;
		?><div id="fascel_changelog_empty_version_1">Please select a version to compare the version against.</div><?php
	}

	if (!$errors) {

	}

}

// Set default versions.
if (empty($version1) || empty($version2)) {
	$first_set  = false;
	$second_set = false;
	$sql  = query("SELECT * FROM `Fascel_releases` ORDER BY `ts` DESC, `id` DESC");
	while ($row = mysql_fetch_assoc($sql)) {
		if (empty($version1)) {
			$version1 = $row['id'];
		}
		if (empty($version2) && $first_set && !$second_set) {
			$version2 = $row['id'];
		}
		if ($version1 == $row['id']) { // $version1 can be set as well when handling the $_POST data, so seperate if.
			$first_set = true;
		}
		if ($version2 == $row['id']) {
			$second_set = true;
		}
	}
}

// Still not done well? Fine, let's just use same versions.
if (empty($version2)) {
	$version2 = $version1;
}

/*
 * Select timeframe. Perhaps in the future this should be changed into
 * using id's or something like that, for futures where no release date
 * is known.
 */
$sql = query("SELECT `ts` FROM `Fascel_releases` WHERE `id` = '".sqlesc($version1)."' LIMIT 1");
$row = mysql_fetch_assoc($sql);
$ts1 = $row['ts'];

if ($version1 == $version2) {
	$ts2 = $ts1;
	$gt  = '>=';
} else {
	$gt  = '>';
	$sql = query("SELECT `ts` FROM `Fascel_releases` WHERE `id` = '".sqlesc($version2)."' LIMIT 1");
	$row = mysql_fetch_assoc($sql);
	$ts2 = $row['ts'];
}

if ($ts2 > $ts1) { // User accidentally the whole thing → switch versions.
	$ts_temp = $ts1;
	$ts1 = $ts2;
	$ts2 = $ts_temp;
	$vers_temp = $version1;
	$version1 = $version2;
	$version2 = $vers_temp;
}

?>

<form id="fascel_changelog_form" method="post">

	<div id="fascel_changelog_versions">
		View changes of version
		<select name="version_1">
			<?php

			// Assemble HTML for first dropdown.
			$sql = query("SELECT * FROM `Fascel_releases` ORDER BY `ts` DESC, `id` DESC");
			while ($row = mysql_fetch_assoc($sql)) {
				echo "\n".'			<option value="'.$row['id'].'"';
				if ($row['id'] == $version1) {
					echo ' selected="selected"';
				}
				echo '>'.$row['version'];
				if (!empty($row['codename'])) {
					echo ' "'.$row['codename'].'"';
				}
				echo '</option>';
			}

			?>
		</select>
		since the release of version
		<select name="version_2">
			<?php

			// Assemble HTML for second dropdown.
			$sql = query("SELECT * FROM `Fascel_releases` ORDER BY `ts` DESC, `id` DESC");
			while ($row = mysql_fetch_assoc($sql)) {
				echo "\n".'			<option value="'.$row['id'].'"';
				if ($row['id'] == $version2) {
					echo ' selected="selected"';
				}
				echo '>'.$row['version'];
				if (!empty($row['codename'])) {
					echo ' "'.$row['codename'].'"';
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
$sql = query("SELECT `Fascel_changes`.`type` as `type`, `Fascel_changes`.`change` as `change` FROM `Fascel_releases`, `Fascel_changes` WHERE `Fascel_releases`.`id` = `Fascel_changes`.`id` AND `Fascel_releases`.`ts` <= ".$ts1." AND `Fascel_releases`.`ts` ".$gt." ".$ts2." ORDER BY `Fascel_changes`.`type` ASC, `Fascel_releases`.`ts` DESC, `Fascel_releases`.`id` DESC");

$curtype = 0;
while ($row = mysql_fetch_assoc($sql)) {
	if ($curtype != $row['type']) {
		if ($curtype != 0) {
			echo '
				</ul>
			</div>';
		}
		$curtype = $row['type'];
		if ($row['type'] == 1) {
			echo '<h2>Added:</h2>';
		}
		if ($row['type'] == 2) {
			echo '<h2>Changed:</h2>';
		}
		if ($row['type'] == 3) {
			echo '<h2>Fixed:</h2>';
		}
		echo '
			<div id="fascel_changelog_type_'.$row['type'].'">
				<ul>';
	}
	echo '<li>'.$row['change'].'</li>';

}
echo '
	</ul>
</div>';

?>
