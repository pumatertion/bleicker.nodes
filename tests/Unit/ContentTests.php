<?php

namespace Tests\Bleicker\Nodes\Unit;

use Tests\Bleicker\Nodes\Unit\Fixtures\Content;
use Tests\Bleicker\Nodes\UnitTestCase;

/**
 * Class ContentTests
 *
 * @package Tests\Bleicker\Nodes\Unit
 */
class ContentTests extends UnitTestCase {

	/**
	 * @test
	 */
	public function countChildrenAfterAddingTest() {
		$node = new Content();
		$child1 = new Content();
		$node->addChild($child1);
		$this->assertEquals(1, $node->getChildren()->count());
	}

	/**
	 * @test
	 */
	public function addedChildsHavingParentAfterAddingTest() {
		$node = new Content();
		$child1 = new Content();
		$node->addChild($child1);
		$this->assertInstanceOf(Content::class, $child1->getParent());
	}

	/**
	 * @test
	 */
	public function sortingDefaultAfterAddingTest() {
		$node = new Content();
		$child1 = new Content();
		$node->addChild($child1);
		$this->assertEquals(0, $child1->getSorting());
	}

	/**
	 * @test
	 */
	public function sortingDefaultAfterAddingMultipleTest() {
		$node = new Content();
		$child1 = new Content();
		$child2 = new Content();
		$node->addChild($child1)->addChild($child2);
		$this->assertEquals(0, $child1->getSorting());
		$this->assertEquals(10, $child2->getSorting());
	}

	/**
	 * @test
	 */
	public function sortingDefaultAfterAddingMultipleAndRemovingOneTest() {
		$node = new Content();
		$child1 = new Content();
		$child2 = new Content();
		$child3 = new Content();
		$child4 = new Content();
		$node->addChild($child1)->addChild($child2)->addChild($child3)->addChild($child4)->removeChild($child2);
		$this->assertEquals(0, $child1->getSorting());
		$this->assertEquals(10, $child3->getSorting());
		$this->assertEquals(20, $child4->getSorting());
	}

	/**
	 * @test
	 */
	public function addChildAfter() {
		$node = new Content();
		$child1 = new Content();
		$child2 = new Content();
		$child3 = new Content();
		$child4 = new Content();
		$node->addChild($child1)->addChild($child2)->addChild($child3)->addChildAfter($child4, $child1);

		$this->assertEquals(0, $child1->getSorting());
		$this->assertEquals(10, $child4->getSorting());
		$this->assertEquals(20, $child2->getSorting());
		$this->assertEquals(30, $child3->getSorting());

		$this->assertEquals($child1, $node->getChildren()->first());
		$this->assertEquals($child4, $node->getChildren()->next());
		$this->assertEquals($child2, $node->getChildren()->next());
		$this->assertEquals($child3, $node->getChildren()->next());
	}

	/**
	 * @test
	 */
	public function addChildBefore() {
		$node = new Content();
		$child1 = new Content();
		$child2 = new Content();
		$child3 = new Content();
		$child4 = new Content();
		$node->addChild($child1)->addChild($child2)->addChild($child3)->addChildBefore($child4, $child1);

		$this->assertEquals(0, $child4->getSorting());
		$this->assertEquals(10, $child1->getSorting());
		$this->assertEquals(20, $child2->getSorting());
		$this->assertEquals(30, $child3->getSorting());

		$this->assertEquals($child4, $node->getChildren()->first());
		$this->assertEquals($child1, $node->getChildren()->next());
		$this->assertEquals($child2, $node->getChildren()->next());
		$this->assertEquals($child3, $node->getChildren()->next());
	}

	/**
	 * @test
	 */
	public function clearTest() {
		$node = new Content();
		$child1 = new Content();
		$child2 = new Content();
		$child3 = new Content();
		$child4 = new Content();
		$node->addChild($child1)->addChild($child2)->addChild($child3)->addChild($child4)->clearChildren();
		$this->assertEquals(0, $node->getChildren()->count());
	}
}
