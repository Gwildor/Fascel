<?php

require_once 'Fascel/includes.php';


// See which versions the user wants to view.
$errors = false;
$version1 = '';
$version2 = '';
if (isset($_POST['submt'])) {

	if (empty($_POST['version_1'])) {
		$errors = true;
		?><div id="fascel_changelog_empty_version_1">Please select a version to view the changelog of.</div><?php
	}
	if (empty($_POST['version_2'])) {
		$errors = true;
		?><div id="fascel_changelog_empty_version_1">Please select a version to compare the version against.</div><?php
	}

}

// Set default versions.
$first_set  = false;
$second_set = false;
$sql  = query("SELECT * FROM `Fascel_releases` ORDER BY `ts` DESC");
while ($row = mysql_fetch_assoc($sql)) {
	if (empty($version1)) {
		$version1 = $row['id'];
	}
	if (empty($version2) and $first_set and !$second_set) {
		$version2 = $row['id'];
	}
	if ($version1 == $row['id']) { // $version1 can be set as well when handling the $_POST data, so seperate if.
		$first_set = true;
	}
	if ($version2 == $row['id']) {
		$second_set = true;
	}
}

// Still not done well? Fine, let's just use same versions.
if (empty($version2)) {
	$version2 = $version1;
}

?>

<form id="fascel_changelog_form" method="post">

	<div id="fascel_changelog_versions">
		View changes of version
		<select name="version_1">
			<?php

			// Assemble HTML for first dropdown.
			$sql = query("SELECT * FROM `Fascel_releases` ORDER BY `ts` DESC");
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
			$sql = query("SELECT * FROM `Fascel_releases` ORDER BY `ts` DESC");
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



?>
