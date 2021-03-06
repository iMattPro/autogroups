<?php
/**
*
* Auto Groups extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbb\autogroups\tests\controller;

class admin_controller_base extends \phpbb_database_test_case
{
	/**
	 * Define the extensions to be tested
	 *
	 * @return array vendor/name of extension(s) to test
	 */
	protected static function setup_extensions()
	{
		return array('phpbb/autogroups');
	}

	/** @var \phpbb\autogroups\controller\admin_controller */
	protected $admin_controller;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \PHPUnit\Framework\MockObject\MockObject|\phpbb\log\log */
	protected $log;

	/** @var \PHPUnit\Framework\MockObject\MockObject|\phpbb\autogroups\conditions\manager */
	protected $manager;

	/** @var \PHPUnit\Framework\MockObject\MockObject|\phpbb\request\request */
	protected $request;

	/** @var \PHPUnit\Framework\MockObject\MockObject|\phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	public function getDataSet()
	{
		return $this->createXMLDataSet(__DIR__ . '/fixtures/phpbb.autogroups.xml');
	}

	protected function setUp(): void
	{
		parent::setUp();

		global $user, $phpbb_root_path, $phpEx;

		$cache = new \phpbb_mock_cache();
		$this->db = $this->new_dbal();
		$this->log = $this->getMockBuilder('\phpbb\log\log')
			->disableOriginalConstructor()
			->getMock();
		$this->manager = $this->getMockBuilder('\phpbb\autogroups\conditions\manager')
			->disableOriginalConstructor()
			->getMock();
		$this->request = $this->getMockBuilder('\phpbb\request\request')
			->disableOriginalConstructor()
			->getMock();
		$this->template = $this->getMockBuilder('\phpbb\template\template')
			->getMock();
		$lang_loader = new \phpbb\language\language_file_loader($phpbb_root_path, $phpEx);
		$lang = new \phpbb\language\language($lang_loader);
		$user = new \phpbb\user($lang, '\phpbb\datetime');
		$user->data['user_id'] = 2;
		$user->data['user_form_salt'] = '';
		$this->user = $user;
		$group_helper = $this->getMockBuilder('\phpbb\group\helper')
			->disableOriginalConstructor()
			->getMock();
		$group_helper
			->method('get_name')
			->will(self::returnArgument(0));
		$group_helper
			->method('get_name_string')
			->will(self::returnArgument(2));

		$this->admin_controller = new \phpbb\autogroups\controller\admin_controller(
			$cache,
			$this->db,
			$group_helper,
			$lang,
			$this->log,
			$this->manager,
			$this->request,
			$this->template,
			$this->user,
			'phpbb_autogroups_rules',
			'phpbb_autogroups_types'
		);
	}
}
