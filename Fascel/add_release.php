<?php

require_once 'includes.php';

if (isset($_POST['submit'])) {

	// actual code

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
		<div><textarea name="added" placeholder="one per line"></textarea></div>
	</div>

	<div id="fascel_add_release_changed">
		<div>Changed:</div>
		<div><textarea name="changed" placeholder="one per line"></textarea></div>
	</div>

	<div id="fascel_add_release_fixed">
		<div>Fixed:</div>
		<div><textarea name="Fixed" placeholder="one per line"></textarea></div>
	</div>

	<input type="submit" name="submit" value="Add" />

</form>

<?php

?>