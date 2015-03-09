<?php

/**
 * Testing the script view.
 *
 * PHP version 5
 *
 * @category  Testing
 * @package   Browserupdate
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2014-2015 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Browserupdate_XH
 */

require_once './classes/Presentation.php';

/**
 * Testing the script view.
 *
 * @category Testing
 * @package  Browserupdate
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Feedview_XH
 */
class ScriptViewTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests that the script is written to $bjs.
     *
     * @return void
     *
     * @global string (X)HTML fragment to insert at the bottom of the body element.
     */
    public function testEmitScript()
    {
        global $bjs;

        $bjs = '';
        $subject = new Browserupdate_Controller();
        $subject->dispatch();
        $this->assertTag(
            array(
                'tag' => 'script',
                'attributes' => array('type' => 'text/javascript'),
                'content' => 'var $buoop = {"reminder":0,"l":false,"test":false};'
            ),
            $bjs
        );
    }

    /**
     * Tests that the script is written to $bjs configured with the CMS language.
     *
     * @return void
     *
     * @global string (X)HTML fragment to insert at the bottom of the body element.
     * @global array  The configuration of the plugins.
     */
    public function testEmitScriptWithCMSLanguage()
    {
        global $bjs, $plugin_cf, $sl;

        $bjs = '';
        $plugin_cf['browserupdate'] = array(
            'version_explorer' => '',
            'version_firefox' => '',
            'version_opera' => '',
            'version_safari' => '',
            'version_chrome' => '',
            'reminder' => '42',
            'cms_language' => 'true',
            'test' => ''
        );
        $sl = 'en';
        $subject = new Browserupdate_Controller();
        $subject->dispatch();
        $this->assertTag(
            array(
                'tag' => 'script',
                'attributes' => array('type' => 'text/javascript'),
                'content' => 'var $buoop = {"reminder":42,"l":"en","test":false};'
            ),
            $bjs
        );
    }

    /**
     * Tests that the script is written to $bjs with a custom reminder time.
     *
     * @return void
     *
     * @global string (X)HTML fragment to insert at the bottom of the body element.
     * @global array  The configuration of the plugins.
     */
    public function testEmitScriptWithCustomReminder()
    {
        global $bjs, $plugin_cf;

        $bjs = '';
        $plugin_cf['browserupdate'] = array(
            'version_explorer' => '',
            'version_firefox' => '',
            'version_opera' => '',
            'version_safari' => '',
            'version_chrome' => '',
            'reminder' => '42',
            'cms_language' => '',
            'test' => ''
        );
        $subject = new Browserupdate_Controller();
        $subject->dispatch();
        $this->assertTag(
            array(
                'tag' => 'script',
                'attributes' => array('type' => 'text/javascript'),
                'content' => 'var $buoop = {"reminder":42,"l":false,"test":false};'
            ),
            $bjs
        );
    }

    /**
     * Tests that the script is written to $bjs with test mode enabled.
     *
     * @return void
     *
     * @global string (X)HTML fragment to insert at the bottom of the body element.
     * @global array  The configuration of the plugins.
     */
    public function testEmitScriptInTestMode()
    {
        global $bjs, $plugin_cf;

        $bjs = '';
        $plugin_cf['browserupdate'] = array(
            'version_explorer' => '',
            'version_firefox' => '',
            'version_opera' => '',
            'version_safari' => '',
            'version_chrome' => '',
            'reminder' => '24',
            'cms_language' => '',
            'test' => 'true'
        );
        $subject = new Browserupdate_Controller();
        $subject->dispatch();
        $this->assertTag(
            array(
                'tag' => 'script',
                'attributes' => array('type' => 'text/javascript'),
                'content' => 'var $buoop = {"reminder":24,"l":false,"test":true};'
            ),
            $bjs
        );
    }

    /**
     * Tests that the script is written to $bjs with test mode disabled when
     * not logged in.
     *
     * @return void
     *
     * @global string (X)HTML fragment to insert at the bottom of the body element.
     * @global array  The configuration of the plugins.
     */
    public function testEmitScriptNotInTestModeInFrontEnd()
    {
        global $bjs, $plugin_cf;

        $this->_defineConstant('XH_ADM', false);
        $bjs = '';
        $plugin_cf['browserupdate'] = array(
            'version_explorer' => '',
            'version_firefox' => '',
            'version_opera' => '',
            'version_safari' => '',
            'version_chrome' => '',
            'reminder' => '24',
            'cms_language' => '',
            'test' => 'true'
        );
        $subject = new Browserupdate_Controller();
        $subject->dispatch();
        $this->assertTag(
            array(
                'tag' => 'script',
                'attributes' => array('type' => 'text/javascript'),
                'content' => 'var $buoop = {"reminder":24,"l":false,"test":false};'
            ),
            $bjs
        );
    }

    /**
     * Tests that the script is written to $bjs with custom browser versions.
     *
     * @return void
     *
     * @global string (X)HTML fragment to insert at the bottom of the body element.
     * @global array  The configuration of the plugins.
     */
    public function testEmitScriptWithCustomVersions()
    {
        global $bjs, $plugin_cf;

        $bjs = '';
        $plugin_cf['browserupdate'] = array(
            'version_explorer' => '9',
            'version_firefox' => '15',
            'version_opera' => '12.1',
            'version_safari' => '5.1',
            'version_chrome' => '',
            'reminder' => '42',
            'cms_language' => '',
            'test' => ''
        );
        $subject = new Browserupdate_Controller();
        $subject->dispatch();
        $this->assertTag(
            array(
                'tag' => 'script',
                'attributes' => array('type' => 'text/javascript'),
                'content' => 'var $buoop = {"reminder":42,"l":false,'
                    . '"test":false,"vs":{"i":"9","f":"15","o":"12.1","s":"5.1"}};'
            ),
            $bjs
        );
    }

    /**
     * Tests that the script is written to $hjs.
     *
     * @return void
     *
     * @global string (X)HTML fragment to insert in the head element.
     */
    public function testEmitScriptToHjs()
    {
        global $hjs;

        $hjs = '';
        $subject = new Browserupdate_Controller();
        $subject->dispatch();
        $this->assertTag(
            array(
                'tag' => 'script',
                'attributes' => array('type' => 'text/javascript')
            ),
            $hjs
        );
    }

    /**
     * (Re)defines a constant.
     *
     * @param string $name  A name.
     * @param string $value A value.
     *
     * @return void
     */
    private function _defineConstant($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        } else {
            runkit_constant_redefine($name, $value);
        }
    }
}

?>
