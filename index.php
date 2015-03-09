<?php

/**
 * main ;)
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Browserupdate
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2014 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Browserupdate_XH
 */

/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

/**
 * The presentation layer.
 */
require_once $pth['folder']['plugin_classes'] . 'Presentation.php';

/**
 * The plugin version.
 */
define('BROWSERUPDATE_VERSION', '@BROWSERUPDATE_VERSION@');

if (!defined('XH_ADM')) {
    /**
     * Whether we're in admin mode.
     */
    define('XH_ADM', $adm);
}

/**
 * The plugin controller.
 */
$_Browserupdate_controller = new Browserupdate_Controller();
$_Browserupdate_controller->dispatch();

?>
