<?php

namespace Tests\Bleicker\Nodes\Functional;

use Bleicker\Nodes\AbstractContentNode;
use Bleicker\Nodes\Service\ContentNodeService;
use Tests\Bleicker\Nodes\Functional\Fixtures\ColumnNode;
use Tests\Bleicker\Nodes\Functional\Fixtures\HeadlineNode;
use Tests\Bleicker\Nodes\Functional\Fixtures\MultiColumnNode;
use Tests\Bleicker\Nodes\FunctionalTestCase;

/**
 * Class ContentNodeServiceTest
 *
 * @package Tests\Bleicker\Nodes\Functional
 */
class ContentNodeServiceTest extends FunctionalTestCase {

	/**
	 * @var ContentNodeService
	 */
	protected $nodeService;

	protected function setUp() {
		parent::setUp();
		$this->nodeService = new ContentNodeService();
	}

	/**
	 * @test
	 */
	public function typeHandlingTest() {
		$this->assertEquals(AbstractContentNode::class, $this->nodeService->getType(), 'Service handles correct type');
	}

	/**
	 * @test
	 */
	public function addNodeTest() {
		$node = new HeadlineNode('header');
		$this->nodeService->add($node)->flush();
	}

	/**
	 * @test
	 */
	public function removeNodeTest() {
		$node = new HeadlineNode('foo');
		$this->nodeService->add($node)->flush();
		$id = $node->getId();
		$this->nodeService->remove($node);
		$this->assertFalse($this->nodeService->flush()->has($id));
	}

	/**
	 * @test
	 */
	public function hasNodeTest() {
		$node = new HeadlineNode('foo');
		$this->nodeService->add($node)->flush();
		$this->assertTrue($this->nodeService->has($node->getId()));
	}

	/**
	 * @test
	 */
	public function hasHeaderTest() {
		$node = new HeadlineNode('foo');
		/** @var HeadlineNode $addedNode */
		$addedNode = $this->nodeService->add($node)->flush()->get($node->getId());
		$this->assertEquals('foo', $addedNode->getHeader());
	}

	/**
	 * @test
	 */
	public function moveIntoTest() {
		$referenceNode = new ColumnNode();
		$beforeNode = new HeadlineNode('before');
		$betweenNode = new HeadlineNode('between');
		$afterNode = new HeadlineNode('after');

		$this->nodeService->into($beforeNode, $referenceNode)->into($betweenNode, $referenceNode)->into($afterNode, $referenceNode)->flush();

		$referenceNodeId = $referenceNode->getId();
		$this->nodeService->clear();

		/** @var ColumnNode $persistedReferenceNode */
		$persistedReferenceNode = $this->nodeService->get($referenceNodeId);
		$this->assertEquals(3, $persistedReferenceNode->getChildren()->count());

		/** @var HeadlineNode $beforeNode */
		$beforeNode = $persistedReferenceNode->getChildren()->first();

		/** @var HeadlineNode $betweenNode */
		$betweenNode = $persistedReferenceNode->getChildren()->next();

		/** @var HeadlineNode $afterNode */
		$afterNode = $persistedReferenceNode->getChildren()->next();

		$this->assertEquals('before', $beforeNode->getHeader(), 'Is before');
		$this->assertEquals('between', $betweenNode->getHeader(), 'Is between');
		$this->assertEquals('after', $afterNode->getHeader(), 'Is after');

		$this->assertEquals(0, $beforeNode->getSorting(), 'Sorting is 0');
		$this->assertEquals(10, $betweenNode->getSorting(), 'Sorting is 10');
		$this->assertEquals(20, $afterNode->getSorting(), 'Sorting is 20');
	}

	/**
	 * @test
	 */
	public function moveAfterTest() {
		$referenceNode = new ColumnNode();
		$beforeNode = new HeadlineNode('before');
		$betweenNode = new HeadlineNode('between');
		$afterNode = new HeadlineNode('after');

		$this->nodeService->into($beforeNode, $referenceNode)->into($afterNode, $referenceNode)->add($betweenNode)->flush()->after($betweenNode, $beforeNode)->flush();

		$referenceNodeId = $referenceNode->getId();
		$this->nodeService->clear();

		/** @var ColumnNode $persistedReferenceNode */
		$persistedReferenceNode = $this->nodeService->get($referenceNodeId);
		$this->assertEquals(3, $persistedReferenceNode->getChildren()->count());

		/** @var HeadlineNode $beforeNode */
		$beforeNode = $persistedReferenceNode->getChildren()->first();

		/** @var HeadlineNode $betweenNode */
		$betweenNode = $persistedReferenceNode->getChildren()->next();

		/** @var HeadlineNode $afterNode */
		$afterNode = $persistedReferenceNode->getChildren()->next();

		$this->assertEquals('before', $beforeNode->getHeader(), 'Is before');
		$this->assertEquals('between', $betweenNode->getHeader(), 'Is between');
		$this->assertEquals('after', $afterNode->getHeader(), 'Is after');

		$this->assertEquals(0, $beforeNode->getSorting(), 'Sorting is 0');
		$this->assertEquals(10, $betweenNode->getSorting(), 'Sorting is 10');
		$this->assertEquals(20, $afterNode->getSorting(), 'Sorting is 20');
	}

	/**
	 * @test
	 */
	public function moveBeforeTest() {
		$referenceNode = new ColumnNode();
		$beforeNode = new HeadlineNode('before');
		$betweenNode = new HeadlineNode('between');
		$afterNode = new HeadlineNode('after');

		$this->nodeService->into($beforeNode, $referenceNode)->into($afterNode, $referenceNode)->add($betweenNode)->flush()->before($betweenNode, $afterNode)->flush();

		$referenceNodeId = $referenceNode->getId();
		$this->nodeService->clear();

		/** @var ColumnNode $persistedReferenceNode */
		$persistedReferenceNode = $this->nodeService->get($referenceNodeId);
		$this->assertEquals(3, $persistedReferenceNode->getChildren()->count());

		/** @var HeadlineNode $beforeNode */
		$beforeNode = $persistedReferenceNode->getChildren()->first();

		/** @var HeadlineNode $betweenNode */
		$betweenNode = $persistedReferenceNode->getChildren()->next();

		/** @var HeadlineNode $afterNode */
		$afterNode = $persistedReferenceNode->getChildren()->next();

		$this->assertEquals('before', $beforeNode->getHeader(), 'Is before');
		$this->assertEquals('between', $betweenNode->getHeader(), 'Is between');
		$this->assertEquals('after', $afterNode->getHeader(), 'Is after');

		$this->assertEquals(0, $beforeNode->getSorting(), 'Sorting is 0');
		$this->assertEquals(10, $betweenNode->getSorting(), 'Sorting is 10');
		$this->assertEquals(20, $afterNode->getSorting(), 'Sorting is 20');
	}

	/**
	 * @test
	 */
	public function deleteParentNodeAlsoDeletesReferencingNodeTest() {
		$level1 = new MultiColumnNode();
		$level2 = new ColumnNode('Level 2');
		$level3 = new MultiColumnNode();
		$level4 = new ColumnNode('Level 4');

		$this->nodeService->into($level4, $level3)->into($level3, $level2)->into($level2, $level1)->flush();

		$level1Id = $level1->getId();
		$level2Id = $level2->getId();
		$level3Id = $level3->getId();
		$level4Id = $level4->getId();

		$this->nodeService->remove($level1)->flush()->clear();

		$this->assertFalse($this->nodeService->has($level1Id));
		$this->assertFalse($this->nodeService->has($level2Id));
		$this->assertFalse($this->nodeService->has($level3Id));
		$this->assertFalse($this->nodeService->has($level4Id));
	}

	/**
	 * @test
	 */
	public function deleteChildDoesNotDeleteParantsTest() {
		$level1 = new MultiColumnNode();
		$level2 = new ColumnNode('Level 2');
		$level3 = new MultiColumnNode();
		$level4 = new ColumnNode('Level 4');

		$this->nodeService->into($level4, $level3)->into($level3, $level2)->into($level2, $level1)->flush();

		$level1Id = $level1->getId();
		$level2Id = $level2->getId();
		$level3Id = $level3->getId();
		$level4Id = $level4->getId();

		$this->nodeService->remove($level3)->flush()->clear();

		$this->assertTrue($this->nodeService->has($level1Id));
		$this->assertTrue($this->nodeService->has($level2Id));
		$this->assertFalse($this->nodeService->has($level3Id));
		$this->assertFalse($this->nodeService->has($level4Id));
	}
}
