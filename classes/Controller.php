<?php

/**
 * Copyright 2014-2017 Christoph M. Becker
 *
 * This file is part of Browserupdate_XH.
 *
 * Browserupdate_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Browserupdate_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Browserupdate_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Browserupdate;

class Controller
{
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
     * @return string
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
     * @return string
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
     * @return array
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
     * @return bool
     */
    protected function isAdministrationRequested()
    {
        global $browserupdate;

        return function_exists('XH_wantsPluginAdministration')
            && XH_wantsPluginAdministration('browserupdate')
            || isset($browserupdate) && $browserupdate == 'true';
    }

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
     * @return string
     */
    protected function renderInfo()
    {
        return '<h1>Browserupdate</h1>'
            . $this->renderIcon()
            . '<p>Version: ' . BROWSERUPDATE_VERSION . '</p>'
            . $this->renderCopyright() . $this->renderLicense();
    }

    /**
     * @return string
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
     * @return string
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
     * @return string
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
