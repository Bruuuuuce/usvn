<?php
/**
 * Tools for template
 *
 * @author Team USVN <contact@usvn.info>
 * @link http://www.usvn.info
 * @license http://www.cecill.info/licences/Licence_CeCILL_V2-en.txt CeCILL V2
 * @copyright Copyright 2007, Team USVN
 * @since 0.5
 * @package usvn
 *
 * This software has been written at EPITECH <http://www.epitech.net>
 * EPITECH, European Institute of Technology, Paris - FRANCE -
 * This project has been realised as part of
 * end of studies project.
 *
 * $Id: TemplateTest.php 207 2007-03-22 01:56:49Z dolean_j $
 */

// Call USVN_TemplateTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "USVN_TemplateTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'library/USVN/autoload.php';

/**
 * Test class for USVN_Template.
 * Generated by PHPUnit_Util_Skeleton on 2007-03-10 at 16:05:57.
 */
class USVN_TemplateTest extends PHPUnit_Framework_TestCase {
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("USVN_TemplateTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp() {
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown() {
    }

    public function test_getTemplate()
    {
		USVN_Template::initTemplate('default', 'www/medias');
		$this->assertEquals('default', USVN_Template::getTemplate());
	}

    public function test_getLocaleDirectory()
    {
		USVN_Template::initTemplate('default', 'www/medias');
		$this->assertEquals('www/medias', USVN_Template::getLocaleDirectory());
	}

	public function test_listTemplate()
	{
		$list = USVN_Template::listTemplate();
		$this->assertTrue(in_array('default', $list));
		$this->assertFalse(in_array('.', $list));
		$this->assertFalse(in_array('..', $list));
		$this->assertFalse(in_array('.svn', $list));
		$this->assertFalse(in_array('.htaccess', $list));
	}

	public function test_isValidTemplateDirectory()
	{
		$this->assertTrue(USVN_Template::isValidTemplateDirectory('www/medias/default'));
		$this->assertFalse(USVN_Template::isValidTemplateDirectory('www/medias/.svn'));
		$this->assertFalse(USVN_Template::isValidTemplateDirectory('.htaccess'));
	}
}

// Call USVN_TemplateTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "USVN_TemplateTest::main") {
    USVN_TemplateTest::main();
}
?>
