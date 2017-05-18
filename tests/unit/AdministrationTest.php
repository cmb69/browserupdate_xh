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

use PHPUnit_Framework_TestCase;
use PHPUnit_Extensions_MockFunction;

class AdministrationTest extends PHPUnit_Framework_TestCase
{
    public function testStylesheet()
    {
        global $browserupdate, $admin, $action;

        $this->defineConstant('XH_ADM', true);
        $browserupdate = 'true';
        $admin = 'plugin_stylesheet';
        $action = 'plugin_text';
        $subject = new Controller();
        new PHPUnit_Extensions_MockFunction('XH_registerStandardPluginMenuItems', $subject);
        $printPluginAdmin = new PHPUnit_Extensions_MockFunction('print_plugin_admin', $subject);
        $printPluginAdmin->expects($this->once())->with('off');
        $pluginAdminCommon = new PHPUnit_Extensions_MockFunction('plugin_admin_common', $subject);
        $pluginAdminCommon->expects($this->once())
            ->with($action, $admin, 'browserupdate');
        $subject->dispatch();
    }

    protected function defineConstant($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        } else {
            runkit_constant_redefine($name, $value);
        }
    }
}
