<?php

/**
 * The controllers.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Browserupdate
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2014 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   SVN: $Id$
 * @link      http://3-magi.net/?CMSimple_XH/Browserupdate_XH
 */

/**
 * The controllers.
 *
 * @category CMSimple_XH
 * @package  Browserupdate
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Feedview_XH
 */
class Browserupdate_Controller
{
    /**
     * Dispatches according to the request.
     *
     * @return void
     *
     * @global string Whether the plugin's administration is requested.
     */
    public function dispatch()
    {
        global $browserupdate;

        $this->_emitScript();
        if (XH_ADM && isset($browserupdate) && $browserupdate == 'true') {
            $this->_handleAdministration();
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
    private function _emitScript()
    {
        global $hjs, $bjs;

        if (isset($bjs)) {
            $bjs .= $this->_renderScript();
        } else {
            $hjs .= $this->_renderScript();
        }
    }

    /**
     * Returns the script element.
     *
     * @return string (X)HTML.
     */
    private function _renderScript()
    {
        $config = $this->_getConfig();
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
    private function _getConfig()
    {
        global $sl, $plugin_cf;

        $pcf = $plugin_cf['browserupdate'];
        $config = array(
            'reminder' => (int) $pcf['reminder'],
            'l' => $pcf['cms_language'] ? $sl : false,
            'test' => XH_ADM && (bool) $pcf['test']
        );
        $versions = $this->_getBrowserVersions();
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
    private function _getBrowserVersions()
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
     * Handles the plugin administration.
     *
     * @return void
     *
     * @global string The value of the <var>admin</var> GP parameter.
     * @global string The value of the <var>action</var> GP parameter.
     * @global string (X)HTML fragment to insert in the contents area.
     */
    private function _handleAdministration()
    {
        global $admin, $action, $o;

        $o .= print_plugin_admin('off');
        switch ($admin) {
        case '':
            $o .= $this->_renderInfo();
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
    private function _renderInfo()
    {
        return '<h1>Browserupdate</h1>'
            . $this->_renderIcon()
            . '<p>Version: ' . BROWSERUPDATE_VERSION . '</p>'
            . $this->_renderCopyright() . $this->_renderLicense();
    }

    /**
     * Renders the plugin icon.
     *
     * @return (X)HTML.
     *
     * @global array The paths of system files and folders.
     * @global array The localization of the plugins.
     */
    private function _renderIcon()
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
    private function _renderCopyright()
    {
        return <<<EOT
<p>Copyright &copy; 2014
    <a href="http://3-magi.net/" target="_blank">Christoph M. Becker</a>
</p>
EOT;
    }

    /**
     * Renders the license info.
     *
     * @return (X)HTML.
     */
    private function _renderLicense()
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
