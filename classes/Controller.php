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
        global $pth;

        $view = new View('info');
        $view->logo = "{$pth['folder']['plugins']}browserupdate/browserupdate.png";
        $view->version = BROWSERUPDATE_VERSION;
        return (string) $view;
    }
}
