<?php

function parse_date_str($str) {
	if (preg_match('/^\d+$/', $str)) {
		return $str;
	}
	if (preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $str, $regs)) {
		return mktime(0, 0, 0, $regs[2], $regs[1], $regs[3]);
	}
	if (preg_match('/^(\d{2})-(\d{2})-(\d{4}) (\d{2}):(\d{2})$/', $str, $regs)) {
		return mktime($regs[4], $regs[5], 0, $regs[2], $regs[1], $regs[3]);
	}
	return false;
}

require_once 'includes.php';
require_once 'auth.php';

if ($Fascel['vars']['admin']) {

	if (isset($_POST['submit'])) {

		//echo '<pre>';print_r($_POST);echo '</pre>';

		// Check if version already exists.
		$Fascel['vars']['sql'] = query("SELECT `id` FROM `".$Fascel['vars']['t_re']."` WHERE `version` = '".sqlesc($_POST['version'])."' LIMIT 1");
		if (mysql_num_rows($Fascel['vars']['sql']) == 0) {
			$Fascel['vars']['sql'] = query("SELECT `id`, `version` FROM `".$Fascel['vars']['t_re']."` ORDER BY `id` DESC LIMIT 1");
			if (mysql_num_rows($Fascel['vars']['sql']) == 0) {
				$Fascel['vars']['id'] = 1;
			} else {
				$Fascel['vars']['row'] = mysql_fetch_assoc($Fascel['vars']['sql']);
				$Fascel['vars']['id']  = $Fascel['vars']['row']['id'] + 1;
			}

			if ($Fascel['vars']['datetime'] = parse_date_str($_POST['datetime'])) {
				query("INSERT INTO `".$Fascel['vars']['t_re']."` (`id`, `version`, `codename`, `ts`) VALUES (".$Fascel['vars']['id'].", '".sqlesc($_POST['version'])."', '".sqlesc($_POST['codename'])."', ".$Fascel['vars']['datetime'].")");
			}
			print 'result: '.$Fascel['vars']['datetime'].'<br />';
		} else {
			$Fascel['vars']['row'] = mysql_fetch_assoc($Fascel['vars']['sql']);
			$Fascel['vars']['id']  = $Fascel['vars']['row']['id'];
		}

		// Insert the changes.
		foreach ($_POST['changes'] as $Fascel['vars']['key'] => $Fascel['vars']['changes']) {
			if (!empty($Fascel['vars']['changes'])) {
				$Fascel['vars']['changes'] = explode("\n", preg_replace('/(\r\n|\r|\n)/', "\n", $Fascel['vars']['changes']));
				foreach ($Fascel['vars']['changes'] as $Fascel['vars']['change']) {
					query("INSERT INTO `".$Fascel['vars']['t_ch']."` (`id`, `type`, `change`) VALUES (".$Fascel['vars']['id'].", ".$Fascel['vars']['key'].", '".sqlesc($Fascel['vars']['change'])."')");
				}
			}
	 	}

		?>
			<div id="fascel_release_added_msg">Release has been added.</div>
		<?php
	}


	if ($Fascel['config']['jQuery'] && $Fascel['config']['jQueryUI'] && $Fascel['config']['jQueryTimepickerAddon']) {
	?>

	<script language="javascript">
	$(function() {
		$('#fascel_add_release_datetime input').datetimepicker({'dateFormat': 'dd-mm-yy'});
	});
	</script>

	<?php
	} elseif ($Fascel['config']['jQuery'] && $Fascel['config']['jQueryUI']) {
	?>

	<script language="javascript">
	$(function() {
		$('#fascel_add_release_datetime input').datepicker({'dateFormat': 'dd-mm-yy'});
	});
	</script>

	<?php } ?>

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

} else {
	?>
		<div id="fascel_not_admin_msg">You are not logged in!</div>
	<?php
}

?>
