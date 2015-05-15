<?php
namespace Tests\Bleicker\Nodes;

use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

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

	protected function tearDown() {
		parent::tearDown();
		$this->destroyDB();
	}

	protected function destroyDB() {
		include_once __DIR__ . '/Functional/Configuration/Registry.php';
		include_once __DIR__ . '/Functional/Configuration/Persistence.php';

		$this->entityManager = ObjectManager::get(EntityManagerInterface::class);

		/** @var EntityManagerInterface $entityManager */
		$entityManager = $this->entityManager;

		$tool = new SchemaTool($entityManager);
		$tool->dropSchema($entityManager->getMetadataFactory()->getAllMetadata());
	}

	protected function initDB() {
		include_once __DIR__ . '/Functional/Configuration/Registry.php';
		include_once __DIR__ . '/Functional/Configuration/Persistence.php';

		$this->entityManager = ObjectManager::get(EntityManagerInterface::class);

		/** @var EntityManagerInterface $entityManager */
		$entityManager = $this->entityManager;

		$tool = new SchemaTool($entityManager);
		$tool->dropSchema($entityManager->getMetadataFactory()->getAllMetadata());
		$tool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());
	}
}
