<?php
/**
 * Class to test group's model
 *
 * @author Team USVN <contact@usvn.info>
 * @link http://www.usvn.info
 * @license http://www.cecill.info/licences/Licence_CeCILL_V2-en.txt CeCILL V2
 * @copyright Copyright 2007, Team USVN
 * @since 0.5
 * @package Db
 * @subpackage Table
 *
 * This software has been written at EPITECH <http://www.epitech.net>
 * EPITECH, European Institute of Technology, Paris - FRANCE -
 * This project has been realised as part of
 * end of studies project.
 *
 * $Id$
 */

// Call USVN_Auth_Adapter_DbTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
	define("PHPUnit_MAIN_METHOD", "USVN_AuthzTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'library/USVN/autoload.php';

/**
 * Test class for USVN_Auth_Adapter_Db.
 * Generated by PHPUnit_Util_Skeleton on 2007-03-25 at 09:51:30.
 */
class USVN_AuthzTest extends USVN_Test_DB {

	private $_user;
	private $_start = "# This is an auto generated file! Edit at your own risk!\n# You can edit this \"/\" section. Settings will be kept.\n#\n[/]\n* = \n\n#\n# Don't edit anything below! All manual changes will be overwritten. \n#\n\n[groups]\n";

	public static function main() {
		require_once "PHPUnit/TextUI/TestRunner.php";

		$suite  = new PHPUnit_Framework_TestSuite("USVN_AuthzTest");
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	protected function setUp() {
		parent::setUp();

		$table = new USVN_Db_Table_Users();
		$this->_user = $table->fetchNew();
		$this->_user->setFromArray(array('users_login' 	=> 'test',
																'users_password' 	=> 'password',
																'users_firstname' 	=> 'firstname',
																'users_lastname' 	=> 'lastname',
																'users_email' 		=> 'email@email.fr'));
		$this->_user->save();
	}

	public function testNoGroup()
	{
		$table = new USVN_Db_Table_Groups();
		$table->delete(1);

		$file = file_get_contents(Zend_Registry::get('config')->subversion->authz);
		$this->assertEquals($this->_start, $file);
	}

	public function testEmptyGroup()
	{
		$table = new USVN_Db_Table_Groups();
		$table->delete(1);
		$group = $table->fetchNew();
		$group->name = "toto";
		$group->save();

		$file = file_get_contents(Zend_Registry::get('config')->subversion->authz);
		$this->assertEquals($this->_start . "toto = \n", $file);
	}

	public function testOneUsersInOneGroup()
	{
		$table = new USVN_Db_Table_Groups();
		$table->delete(1);
		$group = $table->fetchNew();
		/* @var $group USVN_Db_Table_Row_Group */
		$group->name = "toto";
		$group->save();

		list($user1) = $this->_generateUsers(1);
		/* @var $user1 USVN_Db_Table_Row_User */

		$group->addUser($user1);

		$file = file_get_contents(Zend_Registry::get('config')->subversion->authz);
		$this->assertEquals($this->_start . "toto = user1\n", $file);
	}

	public function testTwoUsersInOneGroup()
	{
		$table = new USVN_Db_Table_Groups();
		$table->delete(1);
		$group = $table->fetchNew();
		/* @var $group USVN_Db_Table_Row_Group */
		$group->name = "toto";
		$group->save();

		list($user1, $user2) = $this->_generateUsers(2);
		/* @var $user1 USVN_Db_Table_Row_User */
		/* @var $user2 USVN_Db_Table_Row_User */

		$group->addUser($user1);
		$group->addUser($user2);

		$file = file_get_contents(Zend_Registry::get('config')->subversion->authz);
		$this->assertEquals($this->_start . "toto = user1, user2\n", $file);
	}

	public function testThreeUsersInOneGroup()
	{
		$table = new USVN_Db_Table_Groups();
		$table->delete(1);
		$group = $table->fetchNew();
		/* @var $group USVN_Db_Table_Row_Group */
		$group->name = "toto";
		$group->save();

		list($user1, $user2, $user3) = $this->_generateUsers(3);
		/* @var $user1 USVN_Db_Table_Row_User */
		/* @var $user2 USVN_Db_Table_Row_User */
		/* @var $user3 USVN_Db_Table_Row_User */

		$group->addUser($user1);
		$group->addUser($user2);
		$group->addUser($user3);

		$file = file_get_contents(Zend_Registry::get('config')->subversion->authz);
		$this->assertEquals($this->_start . "toto = user1, user2, user3\n", $file);
	}

	public function testOneUsersInTwoGroups()
	{
		list($group1, $group2) = $this->_generateGroups(2);
		/* @var $group1 USVN_Db_Table_Row_Group */
		/* @var $group2 USVN_Db_Table_Row_Group */

		list($user1) = $this->_generateUsers(2);
		/* @var $user1 USVN_Db_Table_Row_User */

		$group1->addUser($user1);
		$group2->addUser($user1);

		$file = file_get_contents(Zend_Registry::get('config')->subversion->authz);
		$this->assertEquals($this->_start . "group1 = user1\ngroup2 = user1\n", $file);
	}

	public function testTwoUsersInTwoGroups()
	{
		list($group1, $group2) = $this->_generateGroups(2);
		/* @var $group1 USVN_Db_Table_Row_Group */
		/* @var $group2 USVN_Db_Table_Row_Group */

		list($user1, $user2) = $this->_generateUsers(2);
		/* @var $user1 USVN_Db_Table_Row_User */
		/* @var $user2 USVN_Db_Table_Row_User */

		$group1->addUser($user1);
		$group2->addUser($user2);

		$file = file_get_contents(Zend_Registry::get('config')->subversion->authz);
		$this->assertEquals($this->_start . "group1 = user1\ngroup2 = user2\n", $file);
	}

	public function testThreeUsersInTwoGroups()
	{
		list($group1, $group2) = $this->_generateGroups(2);
		/* @var $group1 USVN_Db_Table_Row_Group */
		/* @var $group2 USVN_Db_Table_Row_Group */

		list($user1, $user2, $user3) = $this->_generateUsers(3);
		/* @var $user1 USVN_Db_Table_Row_User */
		/* @var $user2 USVN_Db_Table_Row_User */
		/* @var $user3 USVN_Db_Table_Row_User */

		$group1->addUser($user1);
		$group1->addUser($user2);
		$group1->addUser($user3);
		$group2->addUser($user1);
		$group2->addUser($user2);

		$file = file_get_contents(Zend_Registry::get('config')->subversion->authz);
		$this->assertEquals($this->_start . "group1 = user1, user2, user3\ngroup2 = user1, user2\n", $file);
	}

	public function testThreeUsersInTwoGroupsAndOneProject()
	{
		list($group1, $group2) = $this->_generateGroups(2);
		/* @var $group1 USVN_Db_Table_Row_Group */
		/* @var $group2 USVN_Db_Table_Row_Group */

		list($user1, $user2, $user3) = $this->_generateUsers(3);
		/* @var $user1 USVN_Db_Table_Row_User */
		/* @var $user2 USVN_Db_Table_Row_User */
		/* @var $user3 USVN_Db_Table_Row_User */

		$group1->addUser($user1);
		$group1->addUser($user2);
		$group1->addUser($user3);
		$group2->addUser($user1);
		$group2->addUser($user2);

		$project1 = USVN_Project::createProject(array('projects_name'  => "project1"), "test", true, false, false, false);
		$file = file_get_contents(Zend_Registry::get('config')->subversion->authz);
		$this->assertEquals($this->_start . "group1 = user1, user2, user3\ngroup2 = user1, user2\nproject1 = \n\n\n# Project project1\n[project1:/]\n@project1 = rw\n\n", $file);
	}

	public function testThreeUsersInTwoGroupsAndOneProjectWithPermission()
	{
		list($group1, $group2) = $this->_generateGroups(2);
		/* @var $group1 USVN_Db_Table_Row_Group */
		/* @var $group2 USVN_Db_Table_Row_Group */

		list($user1, $user2, $user3) = $this->_generateUsers(3);
		/* @var $user1 USVN_Db_Table_Row_User */
		/* @var $user2 USVN_Db_Table_Row_User */
		/* @var $user3 USVN_Db_Table_Row_User */

		$group1->addUser($user1);
		$group1->addUser($user2);
		$group1->addUser($user3);
		$group2->addUser($user1);
		$group2->addUser($user2);

		list($project1) = $this->_generateProjects(1);
		/* @var $project1 USVN_Db_Table_Row_Project */

		$table = new USVN_Db_Table_FilesRights();
		for ($i = 1; $i <= 3; $i++) {
			${"files_rights" . $i} = $table->fetchNew();
			${"files_rights" . $i}->projects_id = $project1->id;
			${"files_rights" . $i}->path = "/directory$i/";
			${"files_rights" . $i}->save();
		}

		$file = file_get_contents(Zend_Registry::get('config')->subversion->authz);
		$this->assertEquals($this->_start . "group1 = user1, user2, user3\ngroup2 = user1, user2\nproject1 = \n\n\n# Project project1\n[project1:/]\n@project1 = rw\n\n[project1:/directory1]\n\n[project1:/directory2]\n\n[project1:/directory3]\n\n", $file);
	}

	public function testThreeUsersInTwoGroupsAndOneProjectWithPermissionAndGroupPermissions()
	{
		list($group1, $group2, $group3) = $this->_generateGroups(3);
		/* @var $group1 USVN_Db_Table_Row_Group */
		/* @var $group2 USVN_Db_Table_Row_Group */

		list($user1, $user2, $user3) = $this->_generateUsers(3);
		/* @var $user1 USVN_Db_Table_Row_User */
		/* @var $user2 USVN_Db_Table_Row_User */
		/* @var $user3 USVN_Db_Table_Row_User */

		$group1->addUser($user1);
		$group1->addUser($user2);
		$group1->addUser($user3);
		$group2->addUser($user1);
		$group2->addUser($user2);
		$group3->addUser($user1);
		$group3->addUser($user3);

		list($project1, $project2, $project3) = $this->_generateProjects(3);
		/* @var $project1 USVN_Db_Table_Row_Project */
		/* @var $project2 USVN_Db_Table_Row_Project */
		/* @var $project3 USVN_Db_Table_Row_Project */

		$table = new USVN_Db_Table_FilesRights();
		for ($i = 1; $i <= 5; $i++) {
			for ($j = 1; $j <= 3; $j++) {
				${"files_rights" . $i . $j} = $table->fetchNew();
				${"files_rights" . $i . $j}->projects_id = ${"project" . $j}->id;
				${"files_rights" . $i . $j}->path = "/directory$i";
				${"files_rights" . $i . $j}->save();
				for ($k = 1; $k <= 3; $k++) {
					if ($k == $j) {
						$tmp = new USVN_Db_Table_GroupsToFilesRights();
						$array = array();
						$array["groups_id"] = ${"group" . $k}->id;
						$array["files_rights_is_readable"] = true;
						$array["files_rights_is_writable"] = true;
						$array["files_rights_id"] = ${"files_rights" . $i . $j}->id;
						$tmp = $tmp->createRow($array);
						$tmp->save();
					} else {
						if ($k & 1 && $i & 1) {
							$tmp = new USVN_Db_Table_GroupsToFilesRights();
							$array = array();
							$array["groups_id"] = ${"group" . $k}->id;
							$array["files_rights_is_readable"] = true;
							$array["files_rights_is_writable"] = false;
							$array["files_rights_id"] = ${"files_rights" . $i . $j}->id;
							$tmp = $tmp->createRow($array);
							$tmp->save();
						} else {
							$tmp = new USVN_Db_Table_GroupsToFilesRights();
							$array = array();
							$array["groups_id"] = ${"group" . $k}->id;
							$array["files_rights_is_readable"] = false;
							$array["files_rights_is_writable"] = false;
							$array["files_rights_id"] = ${"files_rights" . $i . $j}->id;
							$tmp = $tmp->createRow($array);
							$tmp->save();
						}
					}
				}
			}
		}

		$file = file_get_contents(Zend_Registry::get('config')->subversion->authz);
		$this->assertEquals($this->_start . "group1 = user1, user2, user3\ngroup2 = user1, user2\ngroup3 = user1, user3\nproject1 = \nproject2 = \nproject3 = \n\n\n# Project project1\n[project1:/]\n@project1 = rw\n\n[project1:/directory1]\n@group1 = rw\n@group2 = \n@group3 = r\n\n[project1:/directory2]\n@group1 = rw\n@group2 = \n@group3 = \n\n[project1:/directory3]\n@group1 = rw\n@group2 = \n@group3 = r\n\n[project1:/directory4]\n@group1 = rw\n@group2 = \n@group3 = \n\n[project1:/directory5]\n@group1 = rw\n@group2 = \n@group3 = r\n\n\n\n# Project project2\n[project2:/]\n@project2 = rw\n\n[project2:/directory1]\n@group1 = r\n@group2 = rw\n@group3 = r\n\n[project2:/directory2]\n@group1 = \n@group2 = rw\n@group3 = \n\n[project2:/directory3]\n@group1 = r\n@group2 = rw\n@group3 = r\n\n[project2:/directory4]\n@group1 = \n@group2 = rw\n@group3 = \n\n[project2:/directory5]\n@group1 = r\n@group2 = rw\n@group3 = r\n\n\n\n# Project project3\n[project3:/]\n@project3 = rw\n\n[project3:/directory1]\n@group1 = r\n@group2 = \n@group3 = rw\n\n[project3:/directory2]\n@group1 = \n@group2 = \n@group3 = rw\n\n[project3:/directory3]\n@group1 = r\n@group2 = \n@group3 = rw\n\n[project3:/directory4]\n@group1 = \n@group2 = \n@group3 = rw\n\n[project3:/directory5]\n@group1 = r\n@group2 = \n@group3 = rw\n\n", $file);
	}

	public function testKeepUserModificationsInAuthzFile()
	{
		$header = "# This is an auto generated file! Edit at your own risk!\n# You can edit this \"/\" section. Settings will be kept.\n#\n[/]\n* = r\n\n#\n# Don't edit anything below! All manual changes will be overwritten. \n#\n\n[groups]\n";
		file_put_contents(Zend_Registry::get('config')->subversion->authz, $header);
		$table = new USVN_Db_Table_Groups();
		$table->delete(1);
		$group = $table->fetchNew();
		$group->name = "toto";
		$group->save();
		$file = file_get_contents(Zend_Registry::get('config')->subversion->authz);
		$this->assertContains($header, $file);
	}

	/**
	 * Genere un tableau d'utilisateur
	 *
	 * @param int $n
	 */
	function _generateUsers($n)
	{
		$table = new USVN_Db_Table_Users();
		$ret = array();
		for ($i = 1; $i <= $n; $i++) {
			$ret[$i - 1] = $table->fetchNew();
			$ret[$i - 1]->login = "user{$i}";
			$ret[$i - 1]->password = "user{$i}user{$i}";
			$ret[$i - 1]->save();
		}
		return $ret;
	}

	/**
	 * Genere un tableau de groupe
	 *
	 * @param int $n
	 */
	function _generateGroups($n)
	{
		$table = new USVN_Db_Table_Groups();
		$ret = array();
		for ($i = 1; $i <= $n; $i++) {
			$ret[$i - 1] = $table->fetchNew();
			$ret[$i - 1]->name = "group{$i}";
			$ret[$i - 1]->save();
		}
		return $ret;
	}

	/**
	 * Genere un tableau de projet
	 *
	 * @param int $n
	 */
	function _generateProjects($n)
	{
		$table = new USVN_Db_Table_Projects();
		$ret = array();
		for ($i = 1; $i <= $n; $i++) {
			$ret[$i - 1] = USVN_Project::createProject(array('projects_name'  => "project{$i}"), "test", true, false, false, false);
		}
		return $ret;
	}

}

// Call USVN_Auth_Adapter_DbTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "USVN_AuthzTest::main") {
	USVN_AuthzTest::main();
}
?>
