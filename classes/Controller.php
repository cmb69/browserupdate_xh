<?php

/**
 * The controllers.
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

namespace Browserupdate;

/**
 * The controllers.
 *
 * @category CMSimple_XH
 * @package  Browserupdate
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Feedview_XH
 */
class Controller
{
    /**
     * Dispatches according to the request.
     *
     * @return void
     */
    public function dispatch()
    {
        $this->emitScript();
        if (defined('XH_ADM') && XH_ADM) {
            if (function_exists('XH_registerStandardPluginMenuItems')) {
                XH_registerStandardPluginMenuItems(false);
            }
            if ($this->isAdministrationRequested()) {
                $this->handleAdministration();
            }
        }
    }

    /**
     * Writes the script element to $hjs resp. $bjs.
     *
     * @return void
     *
     * @global string (X)HTML fragment to insert in the head element.
     * @global string (X)HTML fragment to insert at the bottom of the body element.
     */
    protected function emitScript()
    {
        global $hjs, $bjs;

        if (isset($bjs)) {
            $bjs .= $this->renderScript();
        } else {
            $hjs .= $this->renderScript();
        }
    }

    /**
     * Returns the script element.
     *
     * @return string (X)HTML.
     */
    protected function renderScript()
    {
        $config = $this->getConfig();
        return <<<EOT
<script type="text/javascript">/* <![CDATA[ */
var \$buoop = $config;
\$buoop.ol = window.onload;
window.onload = function () {
    try {
        if (\$buoop.ol) {
            \$buoop.ol();
        }
    } catch (e) {};
    var e = document.createElement("script");
    e.setAttribute("type", "text/javascript");
    e.setAttribute("src", "//browser-update.org/update.js");
    document.body.appendChild(e);
}
/* ]]> */</script>
EOT;
    }

    /**
     * Returns the JSON encoded script configuration.
     *
     * @return string
     *
     * @global string The current language.
     * @global array  The configuration of the plugins.     *
     *
     * @todo json_encode vs. XH_encodeJson
     */
    protected function getConfig()
    {
        global $sl, $plugin_cf;

        $pcf = $plugin_cf['browserupdate'];
        $config = array(
            'reminder' => (int) $pcf['reminder'],
            'l' => $pcf['cms_language'] ? $sl : false,
            'test' => defined('XH_ADM') && XH_ADM && (bool) $pcf['test']
        );
        $versions = $this->getBrowserVersions();
        if ($versions) {
            $config['vs'] = $versions;
        }
        return json_encode($config);
    }

    /**
     * Returns a map of out-dated browser versions.
     *
     * @return array
     *
     * @global array The configuration of the plugins.
     */
    protected function getBrowserVersions()
    {
        global $plugin_cf;

        $versions = array();
        $browsers = array(
            'i' => 'explorer', 'f' => 'firefox', 'o' => 'opera', 's' => 'safari',
            'c' => 'chrome'
        );
        foreach ($browsers as $abbrev => $name) {
            $version = $plugin_cf['browserupdate']['version_' . $name];
            if ($version) {
                $versions[$abbrev] = $version;
            }
        }
        return $versions;
    }

    /**
     * Returns whether the plugin administration is requested.
     *
     * @return bool
     *
     * @global string Whether the plugin's administration is requested.
     */
    protected function isAdministrationRequested()
    {
        global $browserupdate;

        return function_exists('XH_wantsPluginAdministration')
            && XH_wantsPluginAdministration('browserupdate')
            || isset($browserupdate) && $browserupdate == 'true';
    }

    /**
     * Handles the plugin administration.
     *
     * @return void
     *
     * @global string The value of the <var>admin</var> GP parameter.
     * @global string The value of the <var>action</var> GP parameter.
     * @global string (X)HTML fragment to insert in the contents area.
     */
    protected function handleAdministration()
    {
        global $admin, $action, $o;

        $o .= print_plugin_admin('off');
        switch ($admin) {
        case '':
            $o .= $this->renderInfo();
            break;
        default:
            $o .= plugin_admin_common($action, $admin, 'browserupdate');
        }
    }

    /**
     * Returns the plugin info view.
     *
     * @return string (X)HTML.
     */
    protected function renderInfo()
    {
        return '<h1>Browserupdate</h1>'
            . $this->renderIcon()
            . '<p>Version: ' . BROWSERUPDATE_VERSION . '</p>'
            . $this->renderCopyright() . $this->renderLicense();
    }

    /**
     * Renders the plugin icon.
     *
     * @return (X)HTML.
     *
     * @global array The paths of system files and folders.
     * @global array The localization of the plugins.
     */
    protected function renderIcon()
    {
        global $pth, $plugin_tx;

        return tag(
            'img src="' . $pth['folder']['plugins']
            . 'browserupdate/browserupdate.png" class="browserupdate_icon"'
            . ' alt="' . $plugin_tx['browserupdate']['alt_icon'] . '"'
        );
    }

    /**
     * Renders the copyright info.
     *
     * @return (X)HTML.
     */
    protected function renderCopyright()
    {
        return <<<EOT
<p>Copyright &copy; 2014-2017
    <a href="http://3-magi.net/" target="_blank">Christoph M. Becker</a>
</p>
EOT;
    }

    /**
     * Renders the license info.
     *
     * @return (X)HTML.
     */
    protected function renderLicense()
    {
        return <<<EOT
<p class="browserupdate_license">This program is free software: you can
redistribute it and/or modify it under the terms of the GNU General Public
License as published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.</p>
<p class="browserupdate_license">This program is distributed in the hope that it
will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHAN&shy;TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General
Public License for more details.</p>
<p class="browserupdate_license">You should have received a copy of the GNU
General Public License along with this program. If not, see <a
href="http://www.gnu.org/licenses/" target="_blank">http://www.gnu.org/licenses/</a>.
</p>
EOT;
    }
}

?>
