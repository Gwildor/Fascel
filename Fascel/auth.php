<?php

/*
 * Gets included on every page which requires admin access.
 *
 * Checks whether user is allowed to view admin-only pages.
 * Add your own checks here, and;
 * - Either set $Fascel['vars']['admin'] to true or false, or;
 * - Add something which stops the script, such as die() or exit.
 */

$Fascel['vars']['admin'] = true;

?>
