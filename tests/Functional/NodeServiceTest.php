<?php

namespace Tests\Bleicker\Nodes\Functional;

use Bleicker\Nodes\Configuration\NodeConfiguration;
use Bleicker\Nodes\Configuration\NodeTypeConfigurations;
use Bleicker\Nodes\Configuration\NodeTypeConfigurationsInterface;
use Bleicker\Nodes\NodeInterface;
use Bleicker\Nodes\NodeService;
use Bleicker\Nodes\NodeTranslation;
use Bleicker\ObjectManager\ObjectManager;
use Tests\Bleicker\Nodes\Functional\Fixtures\Content;
use Tests\Bleicker\Nodes\Functional\Fixtures\Page;
use Tests\Bleicker\Nodes\Functional\Fixtures\Site;
use Tests\Bleicker\Nodes\FunctionalTestCase;

/**
 * Class NodeServiceTest
 *
 * @package Tests\Bleicker\Nodes\Functional
 */
class NodeServiceTest extends FunctionalTestCase {

	/**
	 * @var NodeService
	 */
	protected $nodeService;

	protected function setUp() {
		parent::setUp();
		$this->nodeService = new NodeService();
		ObjectManager::register(NodeTypeConfigurationsInterface::class, NodeTypeConfigurations::class);
		NodeTypeConfigurations::prune();
	}

	protected function tearDown() {
		parent::tearDown();
		ObjectManager::unregister(NodeTypeConfigurationsInterface::class);
		NodeTypeConfigurations::prune();
	}

	/**
	 * @test
	 * @expectedException \Bleicker\Nodes\Exception\InvalidNodeException
	 */
	public function addNodeIntoItselfTest() {
		$node = new Content();
		$this->nodeService->add($node)->addChild($node, $node);
	}

	/**
	 * @test
	 * @expectedException \Bleicker\Nodes\Exception\InvalidNodeException
	 */
	public function addNodeBeforeThrowsExceptionTest() {
		$node1 = new Content();
		$node2 = new Content();
		$node3 = new Content();
		$this->nodeService->add($node1)->addChild($node2, $node1)->addChild($node3, $node2)->addBefore($node2, $node3);
	}

	/**
	 * @test
	 * @expectedException \Bleicker\Nodes\Exception\InvalidNodeException
	 */
	public function addNodeAfterThrowsExceptionTest() {
		$node1 = new Content();
		$node2 = new Content();
		$node3 = new Content();
		$this->nodeService->add($node1)->addChild($node2, $node1)->addChild($node3, $node2)->addAfter($node2, $node3);
	}

	/**
	 * @test
	 */
	public function getTest() {
		$content = new Content();
		$persisted = $this->nodeService->add($content)->get($content->getId());
		$this->assertEquals($content->getId(), $persisted->getId());
	}

	/**
	 * @test
	 */
	public function findSitesTest() {
		$site1 = new Site('site1');
		$site2 = new Site('site2');
		$page = new Page('page');
		$content = new Content('content');

		$this->nodeService->add($site1)->add($site2)->addChild($content, $site1)->addChild($page, $site2);
		$this->assertEquals(2, $this->nodeService->findSites()->count());
	}

	/**
	 * @test
	 */
	public function addTranslationTest() {
		$translation = new NodeTranslation('German', 'de', 'DE');
		$node = new Content();
		$this->nodeService->addTranslation($node, $translation, 'title');
		$this->assertEquals(1, $node->getTranslations()->count());
		$this->assertNotNull($translation->getId());
	}

	/**
	 * @test
	 */
	public function removeTranslationTest() {
		$translation = new NodeTranslation('German', 'de', 'DE');
		$node = new Content();
		$this->nodeService->addTranslation($node, $translation, 'title')->removeTranslastion($node, $translation);

		$this->assertEquals(0, $node->getTranslations()->count());
		$this->assertNull($translation->getId());
	}

	/**
	 * @test
	 */
	public function addTest() {
		$node = new Content('c1');
		$this->nodeService->add($node);
		$this->assertNotNull($node->getId());
	}

	/**
	 * @test
	 */
	public function removeTest() {
		$node = new Content('c1');
		$this->nodeService->add($node)->remove($node);
		$this->assertNull($node->getId());
	}

	/**
	 * @test
	 */
	public function updateTest() {
		$node = new Page('Old title');
		$this->nodeService->add($node)->update($node->setTitle('New title'));
		$this->assertEquals('New title', $node->getTitle());
	}

	/**
	 * @test
	 */
	public function removeClearsChildrenTest() {
		$node1 = new Content('c1');
		$node2 = new Content('c2');
		$node3 = new Content('c3');

		$this->nodeService->add($node1)->addChild($node2, $node1)->addChild($node3, $node1);

		$this->assertEquals(2, $this->nodeService->getChildren($node1)->count());

		$this->nodeService->remove($node2);

		$this->assertEquals(1, $this->nodeService->getChildren($node1)->count());
	}

	/**
	 * @test
	 */
	public function removeTreeTest() {
		$node1 = new Content('c1');
		$node2 = new Content('c2');
		$node3 = new Content('c3');

		$this->nodeService->add($node1)->addChild($node2, $node1)->addChild($node3, $node2);

		$this->assertEquals(1, $this->nodeService->getChildren($node1)->count());
		$this->assertEquals(1, $this->nodeService->getChildren($node2)->count());

		$this->nodeService->remove($node2);

		$this->assertEquals(0, $this->nodeService->getChildren($node1)->count());
		$this->assertNull($node2->getId());
		$this->assertNull($node3->getId());
	}

	/**
	 * @test
	 */
	public function addFirstChild() {
		$node1 = new Content('c1');
		$node2 = new Content('c2');
		$node3 = new Content('c3');

		$this->nodeService->addFirstChild($node2, $node1)->addFirstChild($node3, $node1);

		$this->assertEquals(2, $this->nodeService->getChildren($node1)->count());

		$this->assertGreaterThan($node3->getSorting(), $node2->getSorting());

		$this->assertEquals(0, $node1->getSorting() % NodeInterface::SORTING_DIFF);
		$this->assertEquals(0, $node2->getSorting() % NodeInterface::SORTING_DIFF);
		$this->assertEquals(0, $node3->getSorting() % NodeInterface::SORTING_DIFF);
	}

	/**
	 * @test
	 */
	public function addLastChild() {
		$node1 = new Content('c1');
		$node2 = new Content('c2');
		$node3 = new Content('c3');

		$this->nodeService->addLastChild($node2, $node1)->addLastChild($node3, $node1);

		$this->assertEquals(2, $this->nodeService->getChildren($node1)->count());

		$this->assertGreaterThan($node2->getSorting(), $node3->getSorting());

		$this->assertEquals(0, $node1->getSorting() % NodeInterface::SORTING_DIFF);
		$this->assertEquals(0, $node2->getSorting() % NodeInterface::SORTING_DIFF);
		$this->assertEquals(0, $node3->getSorting() % NodeInterface::SORTING_DIFF);
	}

	/**
	 * @test
	 */
	public function addChild() {
		$node1 = new Content('c1');
		$node2 = new Content('c2');
		$node3 = new Content('c3');

		$this->nodeService->addChild($node2, $node1)->addChild($node3, $node1);

		$this->assertEquals(2, $this->nodeService->getChildren($node1)->count());

		$this->assertGreaterThan($node2->getSorting(), $node3->getSorting());

		$this->assertEquals(0, $node1->getSorting() % NodeInterface::SORTING_DIFF);
		$this->assertEquals(0, $node2->getSorting() % NodeInterface::SORTING_DIFF);
		$this->assertEquals(0, $node3->getSorting() % NodeInterface::SORTING_DIFF);
	}

	/**
	 * @test
	 */
	public function addLastTest() {
		$node1 = new Content('c1');
		$node2 = new Content('c2');
		$node3 = new Content('c3');

		$this->nodeService->add($node1)->addLast($node2)->addLast($node3);

		$this->assertGreaterThan($node1->getSorting(), $node2->getSorting());

		$this->assertEquals(0, $node1->getSorting() % NodeInterface::SORTING_DIFF);
		$this->assertEquals(0, $node2->getSorting() % NodeInterface::SORTING_DIFF);
	}

	/**
	 * @test
	 */
	public function addFirstTest() {
		$node1 = new Content('c1');
		$node2 = new Content('c2');
		$node3 = new Content('c3');

		$this->nodeService->addFirst($node1)->addFirst($node2)->addFirst($node3);

		$this->assertGreaterThan($node2->getSorting(), $node1->getSorting());

		$this->assertEquals(0, $node1->getSorting() % NodeInterface::SORTING_DIFF);
		$this->assertEquals(0, $node2->getSorting() % NodeInterface::SORTING_DIFF);
	}

	/**
	 * @test
	 */
	public function locatePageTest() {
		$content1 = new Content('c1');
		$content2 = new Content('c2');
		$content3 = new Content('c3');
		$content4 = new Content('c4');
		$lostContent = new Content('I am lost');

		$page1 = new Page('p1');
		$page2 = new Page('p2');
		$page3 = new Page('p3');

		$this->nodeService->addChild($page2, $page1)->addChild($page3, $page2)->addChild($content1, $page1)
			->addChild($content2, $page2)->addChild($content3, $page3)
			->addChild($content4, $content3);

		$this->assertNull($this->nodeService->locatePage($lostContent));

		$this->assertEquals('p1', $this->nodeService->locatePage($page1)->getTitle());
		$this->assertEquals('p2', $this->nodeService->locatePage($page2)->getTitle());
		$this->assertEquals('p3', $this->nodeService->locatePage($page3)->getTitle());

		$this->assertEquals('p1', $this->nodeService->locatePage($content1)->getTitle());

		$this->assertEquals('p2', $this->nodeService->locatePage($content2)->getTitle());

		$this->assertEquals('p3', $this->nodeService->locatePage($content3)->getTitle());
		$this->assertEquals('p3', $this->nodeService->locatePage($content4)->getTitle());
	}

	/**
	 * @test
	 */
	public function getContentTest() {
		$content1 = new Content('c1');
		$content2 = new Content('c2');
		$content3 = new Content('c3');
		$content4 = new Content('c4');

		$page1 = new Page('p1');
		$page2 = new Page('p2');

		$this->nodeService
			->addChild($page2, $page1)
			->addChild($content1, $page1)->addChild($content2, $page1)->addChild($content3, $page1)->addChild($content4, $page1);

		$this->assertEquals(4, $this->nodeService->getContent($page1)->count());
		$this->assertEquals(0, $this->nodeService->getContent($page2)->count());
	}

	/**
	 * @test
	 */
	public function getPagesTest() {
		$content1 = new Content('c1');
		$content2 = new Content('c2');
		$content3 = new Content('c3');
		$content4 = new Content('c4');

		$page1 = new Page('p1');
		$page2 = new Page('p2');
		$page3 = new Page('p3');
		$page4 = new Page('p4');
		$page5 = new Page('p5');

		$this->nodeService->addChild($page2, $page1)->addChild($page3, $page1)->addChild($page5, $page3)->addChild($page4, $page1)->addChild($content1, $page1)->addChild($content2, $page1)->addChild($content3, $page1)->addChild($content4, $page1);

		$this->assertEquals(3, $this->nodeService->getPages($page1)->count());
		$this->assertEquals(0, $this->nodeService->getPages($page2)->count());
		$this->assertEquals(1, $this->nodeService->getPages($page3)->count());
		$this->assertEquals(0, $this->nodeService->getPages($page4)->count());
		$this->assertEquals(0, $this->nodeService->getPages($page5)->count());
	}

	/**
	 * @test
	 */
	public function addAfterTest() {
		$node1 = new Content();
		$node2 = new Content();
		$node3 = new Content();

		$this->nodeService->add($node1)->addAfter($node2, $node1)->addAfter($node3, $node1);

		$this->assertGreaterThan($node1->getSorting(), $node2->getSorting(), 'node1 after node2');
		$this->assertGreaterThan($node3->getSorting(), $node2->getSorting(), 'node3 after node2');

		$this->assertEquals(0, $node1->getSorting() % NodeInterface::SORTING_DIFF);
		$this->assertEquals(0, $node3->getSorting() % NodeInterface::SORTING_DIFF);
		$this->assertEquals(0, $node2->getSorting() % NodeInterface::SORTING_DIFF);
	}

	/**
	 * @test
	 */
	public function addBeforeTest() {
		$node1 = new Content();
		$node2 = new Content();
		$node3 = new Content();

		$this->nodeService->add($node1)->addBefore($node2, $node1)->addBefore($node3, $node1);

		$this->assertGreaterThan($node2->getSorting(), $node1->getSorting(), 'node2 before node1');
		$this->assertGreaterThan($node2->getSorting(), $node3->getSorting(), 'node3 before node2');

		$this->assertEquals(0, $node1->getSorting() % NodeInterface::SORTING_DIFF);
		$this->assertEquals(0, $node3->getSorting() % NodeInterface::SORTING_DIFF);
		$this->assertEquals(0, $node2->getSorting() % NodeInterface::SORTING_DIFF);
	}
}