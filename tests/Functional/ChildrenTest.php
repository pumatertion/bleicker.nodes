<?php

namespace Tests\Bleicker\Nodes\Functional;

use Bleicker\Nodes\AbstractPageNode;
use Bleicker\Nodes\NodeTranslation;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManagerInterface;
use Doctrine\Common\Collections\Criteria;
use Tests\Bleicker\Nodes\Functional\Fixtures\Content;
use Tests\Bleicker\Nodes\Functional\Fixtures\Page;
use Tests\Bleicker\Nodes\FunctionalTestCase;

/**
 * Class ChildrenTest
 *
 * @package Tests\Bleicker\Nodes\Functional
 */
class ChildrenTest extends FunctionalTestCase {

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
	public function rootTest() {
		$node = new Page();
		$node1 = new Content();
		$node2 = new Content();
		$node3 = new Content();

		$node->addChild($node1->addChild($node2->addChild($node3)));

		$this->entityManager->persist($node);
		$this->entityManager->flush();

		$nodeId = $node3->getId();
		$rootId = $node->getId();

		$this->entityManager->clear();

		/** @var Content $node */
		$node = $this->entityManager->find(Content::class, $nodeId);

		$this->assertEquals($rootId, $node->getRoot()->getId());
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

	/**
	 * @test
	 */
	public function translationTest() {

		$node = new Page('Default Title');
		$german = new NodeTranslation('German title', 'de', 'DE');
		$french = new NodeTranslation('French title', 'fr', 'FR');
		$node->addTranslation($german->setNode($node), 'title')->addTranslation($french->setNode($node), 'title');

		$this->entityManager->persist($node);
		$this->entityManager->flush();

		$nodeId = $node->getId();

		$this->entityManager->clear();

		/** @var Page $node */
		$node = $this->entityManager->find(Page::class, $nodeId);

		/** @var NodeTranslation $german */
		$german = $node->filterTranslationsFor('title', 'de', 'DE')->first();

		/** @var NodeTranslation $german */
		$french = $node->filterTranslationsFor('title', 'fr', 'FR')->first();

		$this->assertInstanceOf(NodeTranslation::class, $german);
		$this->assertInstanceOf(NodeTranslation::class, $french);

		$this->assertEquals('German title', $german->getValue());
		$this->assertEquals('French title', $french->getValue());
	}

	/**
	 * @test
	 */
	public function criteriaTest() {

		$page = new Page();
		$page1 = new Page();
		$page2 = new Page();
		$page3 = new Page();

		$node = new Content();
		$node1 = new Content();
		$node2 = new Content();
		$node3 = new Content();

		$page->addChild($page1)->addChild($page2)->addChild($page3)->addChild($node)->addChild($node1)->addChild($node2)->addChild($node3);

		$this->entityManager->persist($page);
		$this->entityManager->flush();

		$pageId = $page->getId();

		$this->entityManager->clear();

		/** @var Page $page */
		$page = $this->entityManager->find(Page::class, $pageId);

		$criteria = Criteria::create()->where(Criteria::expr()->eq('nodeTypeAbstraction', AbstractPageNode::class));
		$pagesOnly = $page->getChildrenByCriteria($criteria);

		$this->assertEquals(3, $pagesOnly->count());
	}
}
