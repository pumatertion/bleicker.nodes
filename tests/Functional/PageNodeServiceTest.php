<?php

namespace Tests\Bleicker\Nodes\Functional;

use Bleicker\Nodes\AbstractPageNode;
use Bleicker\Nodes\Service\PageNodeService;
use Tests\Bleicker\Nodes\Functional\Fixtures\PageNode;
use Tests\Bleicker\Nodes\FunctionalTestCase;

/**
 * Class PageNodeServiceTest
 *
 * @package Tests\Bleicker\Nodes\Functional
 */
class PageNodeServiceTest extends FunctionalTestCase {

	/**
	 * @var PageNodeService
	 */
	protected $nodeService;

	protected function setUp() {
		parent::setUp();
		$this->nodeService = new PageNodeService();
	}

	/**
	 * @test
	 */
	public function typeHandlingTest() {
		$this->assertEquals(AbstractPageNode::class, $this->nodeService->getType(), 'Service handles correct type');
	}

	/**
	 * @test
	 */
	public function addNodeTest() {
		$node = new PageNode('foo');
		$this->nodeService->add($node)->flush();
	}

	/**
	 * @test
	 */
	public function removeNodeTest() {
		$node = new PageNode('foo');
		$this->nodeService->add($node)->flush();
		$id = $node->getId();
		$this->nodeService->remove($node);
		$this->assertFalse($this->nodeService->flush()->has($id));
	}

	/**
	 * @test
	 */
	public function hasNodeTest() {
		$node = new PageNode('foo');
		$this->nodeService->add($node)->flush();
		$this->assertTrue($this->nodeService->has($node->getId()));
	}

	/**
	 * @test
	 */
	public function hasTitleTest() {
		$node = new PageNode('foo');
		/** @var PageNode $addedNode */
		$addedNode = $this->nodeService->add($node)->flush()->get($node->getId());
		$this->assertEquals('foo', $addedNode->getTitle());
		$this->assertEquals('foo', $node->getTitle());
	}

	/**
	 * @test
	 */
	public function moveIntoTest() {
		$referenceNode = new PageNode('reference');
		$beforeNode = new PageNode('before');
		$betweenNode = new PageNode('between');
		$afterNode = new PageNode('after');
		/** @var PageNode $persistedNode */
		$this->nodeService->into($beforeNode, $referenceNode)->into($betweenNode, $referenceNode)->into($afterNode, $referenceNode)->flush();

		$referenceNodeId = $referenceNode->getId();
		$this->nodeService->clear();

		/** @var PageNode $persistedReferenceNode */
		$persistedReferenceNode = $this->nodeService->get($referenceNodeId);
		$this->assertEquals(3, $persistedReferenceNode->getChildren()->count());

		/** @var PageNode $beforeNode */
		$beforeNode = $persistedReferenceNode->getChildren()->first();

		/** @var PageNode $betweenNode */
		$betweenNode = $persistedReferenceNode->getChildren()->next();

		/** @var PageNode $afterNode */
		$afterNode = $persistedReferenceNode->getChildren()->next();

		$this->assertEquals('before', $beforeNode->getTitle(), 'Is before');
		$this->assertEquals('between', $betweenNode->getTitle(), 'Is between');
		$this->assertEquals('after', $afterNode->getTitle(), 'Is after');

		$this->assertEquals(0, $beforeNode->getSorting(), 'Sorting is 0');
		$this->assertEquals(10, $betweenNode->getSorting(), 'Sorting is 10');
		$this->assertEquals(20, $afterNode->getSorting(), 'Sorting is 20');
	}

	/**
	 * @test
	 */
	public function moveAfterTest() {
		$referenceNode = new PageNode('reference');
		$beforeNode = new PageNode('before');
		$afterNode = new PageNode('after');
		$betweenNode = new PageNode('between');

		$this->nodeService->into($beforeNode, $referenceNode)->into($afterNode, $referenceNode)->add($betweenNode)->flush()->after($betweenNode, $beforeNode)->flush();
		$referenceNodeId = $referenceNode->getId();
		$this->nodeService->clear();

		/** @var PageNode $persistedReferenceNode */
		$persistedReferenceNode = $this->nodeService->get($referenceNodeId);
		$this->assertEquals(3, $persistedReferenceNode->getChildren()->count());

		/** @var PageNode $beforeNode */
		$beforeNode = $persistedReferenceNode->getChildren()->first();

		/** @var PageNode $betweenNode */
		$betweenNode = $persistedReferenceNode->getChildren()->next();

		/** @var PageNode $afterNode */
		$afterNode = $persistedReferenceNode->getChildren()->next();

		$this->assertEquals('before', $beforeNode->getTitle(), 'Is before');
		$this->assertEquals('between', $betweenNode->getTitle(), 'Is between');
		$this->assertEquals('after', $afterNode->getTitle(), 'Is after');

		$this->assertEquals(0, $beforeNode->getSorting(), 'Sorting is 0');
		$this->assertEquals(10, $betweenNode->getSorting(), 'Sorting is 10');
		$this->assertEquals(20, $afterNode->getSorting(), 'Sorting is 20');
	}

	/**
	 * @test
	 */
	public function moveBeforeTest() {
		$referenceNode = new PageNode('reference');
		$beforeNode = new PageNode('before');
		$afterNode = new PageNode('after');
		$betweenNode = new PageNode('between');

		$this->nodeService->into($beforeNode, $referenceNode)->into($afterNode, $referenceNode)->add($betweenNode)->flush()->before($betweenNode, $afterNode)->flush();
		$referenceNodeId = $referenceNode->getId();
		$this->nodeService->clear();

		/** @var PageNode $persistedReferenceNode */
		$persistedReferenceNode = $this->nodeService->get($referenceNodeId);
		$this->assertEquals(3, $persistedReferenceNode->getChildren()->count());

		/** @var PageNode $beforeNode */
		$beforeNode = $persistedReferenceNode->getChildren()->first();

		/** @var PageNode $betweenNode */
		$betweenNode = $persistedReferenceNode->getChildren()->next();

		/** @var PageNode $afterNode */
		$afterNode = $persistedReferenceNode->getChildren()->next();

		$this->assertEquals('before', $beforeNode->getTitle(), 'Is before');
		$this->assertEquals('between', $betweenNode->getTitle(), 'Is between');
		$this->assertEquals('after', $afterNode->getTitle(), 'Is after');

		$this->assertEquals(0, $beforeNode->getSorting(), 'Sorting is 0');
		$this->assertEquals(10, $betweenNode->getSorting(), 'Sorting is 10');
		$this->assertEquals(20, $afterNode->getSorting(), 'Sorting is 20');
	}

	/**
	 * @test
	 */
	public function deleteParentNodeAlsoDeletesReferencingNodeTest() {
		$level1 = new PageNode('Level 1');
		$level2 = new PageNode('Level 2');
		$level3 = new PageNode('Level 3');

		$this->nodeService->into($level3, $level2)->into($level2, $level1)->flush();

		$level1Id = $level1->getId();
		$level2Id = $level2->getId();
		$level3Id = $level3->getId();

		$this->nodeService->remove($level1)->flush()->clear();

		$this->assertFalse($this->nodeService->has($level1Id));
		$this->assertFalse($this->nodeService->has($level2Id));
		$this->assertFalse($this->nodeService->has($level3Id));
	}
}
