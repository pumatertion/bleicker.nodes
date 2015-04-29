<?php

namespace Tests\Bleicker\Nodes\Functional;

use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManagerInterface;
use Tests\Bleicker\Nodes\Functional\Fixtures\Content;
use Tests\Bleicker\Nodes\Functional\Fixtures\Page;
use Tests\Bleicker\Nodes\FunctionalTestCase;

/**
 * Class ChildrenTests
 *
 * @package Tests\Bleicker\Nodes\Functional
 */
class ChildrenTests extends FunctionalTestCase {

	/**
	 * @var EntityManagerInterface
	 */
	protected $entityManager;

	protected function setUp() {
		parent::setUp();
		$this->entityManager = ObjectManager::get(EntityManagerInterface::class);
	}

	/**
	 * @test
	 */
	public function createChildTest() {
		$node = new Page();
		$node1 = new Content();
		$node2 = new Content();
		$node3 = new Content();

		$node->addChild($node1)->addChild($node2)->addChild($node3);

		$this->entityManager->persist($node);
		$this->entityManager->flush();

		$nodeId = $node->getId();

		$this->entityManager->clear();

		/** @var Page $node */
		$node = $this->entityManager->find(Page::class, $nodeId);

		$this->assertEquals(3, $node->getChildren()->count());

		/** @var Content $_1 */
		$_1 = $node->getChildren()->first();

		/** @var Content $_2 */
		$_2 = $node->getChildren()->next();

		/** @var Content $_3 */
		$_3 = $node->getChildren()->next();

		$this->assertEquals(0, $_1->getSorting(), 'Sorting is 0');
		$this->assertEquals(10, $_2->getSorting(), 'Sorting is 10');
		$this->assertEquals(20, $_3->getSorting(), 'Sorting is 20');
	}

	/**
	 * @test
	 */
	public function addChildAfterTest() {
		$node = new Page();
		$node1 = new Content();
		$node2 = new Content();
		$node3 = new Content();

		$node->addChild($node1)->addChild($node2)->addChildAfter($node3, $node1);

		$this->entityManager->persist($node);
		$this->entityManager->flush();

		$nodeId = $node->getId();

		$this->entityManager->clear();

		/** @var Page $node */
		$node = $this->entityManager->find(Page::class, $nodeId);

		$this->assertEquals(3, $node->getChildren()->count());

		/** @var Content $_1 */
		$_1 = $node->getChildren()->first();

		/** @var Content $_2 */
		$_2 = $node->getChildren()->next();

		/** @var Content $_3 */
		$_3 = $node->getChildren()->next();

		$this->assertEquals(0, $_1->getSorting(), 'Sorting is 0');
		$this->assertEquals(10, $_2->getSorting(), 'Sorting is 10');
		$this->assertEquals(20, $_3->getSorting(), 'Sorting is 20');
	}

	/**
	 * @test
	 */
	public function addChildBeforeTest() {
		$node = new Page();
		$node1 = new Content();
		$node2 = new Content();
		$node3 = new Content();

		$node->addChild($node1)->addChild($node2)->addChildBefore($node3, $node1);

		$this->entityManager->persist($node);
		$this->entityManager->flush();

		$nodeId = $node->getId();

		$this->entityManager->clear();

		/** @var Page $node */
		$node = $this->entityManager->find(Page::class, $nodeId);

		$this->assertEquals(3, $node->getChildren()->count());

		/** @var Content $_1 */
		$_1 = $node->getChildren()->first();

		/** @var Content $_2 */
		$_2 = $node->getChildren()->next();

		/** @var Content $_3 */
		$_3 = $node->getChildren()->next();

		$this->assertEquals(0, $_1->getSorting(), 'Sorting is 0');
		$this->assertEquals(10, $_2->getSorting(), 'Sorting is 10');
		$this->assertEquals(20, $_3->getSorting(), 'Sorting is 20');
	}

	/**
	 * @test
	 */
	public function removeChildTest() {
		$node = new Page();
		$node1 = new Content();
		$node2 = new Content();
		$node3 = new Content();

		$node->addChild($node1)->addChild($node2)->addChild($node3);

		$this->entityManager->persist($node);
		$this->entityManager->flush();

		$nodeId = $node->getId();

		$this->entityManager->clear();

		/** @var Page $node */
		$node = $this->entityManager->find(Page::class, $nodeId);

		$this->assertEquals(3, $node->getChildren()->count());

		/** @var Content $node */
		$node2 = $node->getChildren()->next();

		$node->removeChild($node2);
		$this->entityManager->persist($node);
		$this->entityManager->flush();
		$this->entityManager->clear();

		/** @var Page $node */
		$node = $this->entityManager->find(Page::class, $nodeId);

		$this->assertEquals(2, $node->getChildren()->count());

		/** @var Content $_1 */
		$_1 = $node->getChildren()->first();

		/** @var Content $_2 */
		$_2 = $node->getChildren()->next();

		$this->assertEquals(0, $_1->getSorting(), 'Sorting is 0');
		$this->assertEquals(10, $_2->getSorting(), 'Sorting is 10');

	}

	/**
	 * @test
	 */
	public function clearChildrenTest() {
		$node = new Page();
		$node1 = new Content();
		$node2 = new Content();
		$node3 = new Content();

		$node->addChild($node1)->addChild($node2)->addChild($node3);

		$this->entityManager->persist($node);
		$this->entityManager->flush();

		$nodeId = $node->getId();

		$this->entityManager->clear();

		/** @var Page $node */
		$node = $this->entityManager->find(Page::class, $nodeId);

		$this->assertEquals(3, $node->getChildren()->count());

		$node->clearChildren();
		$this->entityManager->persist($node);
		$this->entityManager->flush();
		$this->entityManager->clear();

		/** @var Page $node */
		$node = $this->entityManager->find(Page::class, $nodeId);

		$this->assertEquals(0, $node->getChildren()->count());
	}

	/**
	 * @test
	 */
	public function removeChildDoesNotRemoveParentFromPersistence() {
		$node = new Page();
		$node1 = new Content();
		$node2 = new Content();
		$node3 = new Content();

		$node->addChild($node1)->addChild($node2)->addChild($node3);

		$this->entityManager->persist($node);
		$this->entityManager->flush();

		$nodeId = $node->getId();

		$this->entityManager->clear();

		/** @var Page $node */
		$node = $this->entityManager->find(Page::class, $nodeId);

		$this->assertEquals(3, $node->getChildren()->count());

		/** @var Content $node1 */
		$node1 = $node->getChildren()->first();

		$this->entityManager->remove($node1);
		$this->entityManager->flush();
		$this->entityManager->clear();

		/** @var Page $node */
		$node = $this->entityManager->find(Page::class, $nodeId);

		$this->assertInstanceOf(Page::class, $node);
		$this->assertEquals(2, $node->getChildren()->count());
	}

	/**
	 * @test
	 */
	public function removeParentRemovesAlsoChildren() {
		$node = new Page();
		$node1 = new Content();
		$node2 = new Content();
		$node3 = new Content();

		$node->addChild($node1)->addChild($node2);
		$node2->addChild($node3);

		$this->entityManager->persist($node);
		$this->entityManager->flush();

		$nodeId = $node->getId();
		$node1Id = $node1->getId();
		$node2Id = $node2->getId();
		$node3Id = $node3->getId();

		$this->entityManager->clear();

		/** @var Page $node */
		$node = $this->entityManager->find(Page::class, $node->getId());

		$this->entityManager->remove($node);
		$this->entityManager->flush();
		$this->entityManager->clear();

		/** @var Page $node */
		$node = $this->entityManager->find(Page::class, $nodeId);

		/** @var Content $node */
		$node1 = $this->entityManager->find(Page::class, $node1Id);

		/** @var Content $node */
		$node2 = $this->entityManager->find(Page::class, $node2Id);

		/** @var Content $node */
		$node3 = $this->entityManager->find(Page::class, $node3Id);

		$this->assertNull($node);
		$this->assertNull($node1);
		$this->assertNull($node2);
		$this->assertNull($node3);
	}
}
