<?php
namespace Tests\Bleicker\Nodes;

use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManagerInterface;

/**
 * Class FunctionalTestCase
 *
 * @package Tests\Bleicker\Nodes
 */
abstract class FunctionalTestCase extends BaseTestCase {

	/**
	 * @var EntityManagerInterface
	 */
	protected $entityManager;

	protected function setUp() {
		parent::setUp();
		$this->initDB();
	}

	protected function initDB() {
		include_once __DIR__ . '/Functional/Configuration/Secrets.php';
		include_once __DIR__ . '/Functional/Configuration/Persistence.php';
		$this->entityManager = ObjectManager::get(EntityManagerInterface::class);
	}
}
