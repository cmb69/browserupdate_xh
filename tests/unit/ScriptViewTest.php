<?php

/**
 * Testing the script view.
 *
 * PHP version 5
 *
 * @category  Testing
 * @package   Browserupdate
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2014 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   SVN: $Id$
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
                'attributes' => array('type' => 'text/javascript')
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
}

?>
