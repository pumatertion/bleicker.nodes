<?php
namespace Tests\Bleicker\Nodes;

use Bleicker\Nodes\AbstractNode;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManagerInterface;
use Tests\Bleicker\Nodes\Functional\Fixtures\Content;
use Tests\Bleicker\Nodes\Functional\Fixtures\Page;

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

		/** @var EntityManagerInterface $em */
		$em = ObjectManager::get(EntityManagerInterface::class);

		$connection = $em->getConnection();
		$dbPlatform = $connection->getDatabasePlatform();
		$connection->beginTransaction();

		try {
			$cmd = $em->getClassMetadata(AbstractNode::class);
			$connection->query('SET FOREIGN_KEY_CHECKS=0');
			$q = $dbPlatform->getTruncateTableSql($cmd->getTableName());
			$connection->executeUpdate($q);
			$connection->query('SET FOREIGN_KEY_CHECKS=1');

			$cmd = $em->getClassMetadata(Page::class);
			$connection->query('SET FOREIGN_KEY_CHECKS=0');
			$q = $dbPlatform->getTruncateTableSql($cmd->getTableName());
			$connection->executeUpdate($q);
			$connection->query('SET FOREIGN_KEY_CHECKS=1');

			$cmd = $em->getClassMetadata(Content::class);
			$connection->query('SET FOREIGN_KEY_CHECKS=0');
			$q = $dbPlatform->getTruncateTableSql($cmd->getTableName());
			$connection->executeUpdate($q);
			$connection->query('SET FOREIGN_KEY_CHECKS=1');

			$connection->commit();
		} catch (\Exception $e) {
			$connection->rollback();
			throw $e;
		}

		$this->entityManager = $em;
	}
}
