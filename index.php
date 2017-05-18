<?php

/**
 * The plugin entry point.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Browserupdate
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2014-2017 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Browserupdate_XH
 */

/**
 * The plugin version.
 */
define('BROWSERUPDATE_VERSION', '@BROWSERUPDATE_VERSION@');

/**
 * The plugin controller.
 */
$_Browserupdate_controller = new Browserupdate_Controller();
$_Browserupdate_controller->dispatch();

?>
