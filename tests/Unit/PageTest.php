<?php

namespace Tests\Bleicker\Nodes\Unit;

use Tests\Bleicker\Nodes\Unit\Fixtures\Page;
use Tests\Bleicker\Nodes\UnitTestCase;

/**
 * Class PageTest
 *
 * @package Tests\Bleicker\Nodes\Unit
 */
class PageTest extends UnitTestCase {

	/**
	 * @test
	 */
	public function countChildrenAfterAddingTest() {
		$node = new Page();
		$child1 = new Page();
		$node->addChild($child1);
		$this->assertEquals(1, $node->getChildren()->count());
	}

	/**
	 * @test
	 */
	public function sortingDefaultAfterAddingTest() {
		$node = new Page();
		$child1 = new Page();
		$node->addChild($child1);
		$this->assertEquals(0, $child1->getSorting());
	}

	/**
	 * @test
	 */
	public function sortingDefaultAfterAddingMultipleTest() {
		$node = new Page();
		$child1 = new Page();
		$child2 = new Page();
		$node->addChild($child1)->addChild($child2);
		$this->assertEquals(0, $child1->getSorting());
		$this->assertEquals(10, $child2->getSorting());
	}

	/**
	 * @test
	 */
	public function sortingDefaultAfterAddingMultipleAndRemovingOneTest() {
		$node = new Page();
		$child1 = new Page();
		$child2 = new Page();
		$child3 = new Page();
		$child4 = new Page();
		$node->addChild($child1)->addChild($child2)->addChild($child3)->addChild($child4)->removeChild($child2);
		$this->assertEquals(0, $child1->getSorting());
		$this->assertEquals(10, $child3->getSorting());
		$this->assertEquals(20, $child4->getSorting());
	}

	/**
	 * @test
	 */
	public function addChildAfter() {
		$node = new Page();
		$child1 = new Page();
		$child2 = new Page();
		$child3 = new Page();
		$child4 = new Page();
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
		$node = new Page();
		$child1 = new Page();
		$child2 = new Page();
		$child3 = new Page();
		$child4 = new Page();
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
		$node = new Page();
		$child1 = new Page();
		$child2 = new Page();
		$child3 = new Page();
		$child4 = new Page();
		$node->addChild($child1)->addChild($child2)->addChild($child3)->addChild($child4)->clearChildren();
		$this->assertEquals(0, $node->getChildren()->count());
	}
}