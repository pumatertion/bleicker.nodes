<?php

namespace Tests\Bleicker\Nodes\Functional;

use Bleicker\Nodes\AbstractContentNode;
use Bleicker\Nodes\Service\ContentNodeService;
use Tests\Bleicker\Nodes\Functional\Fixtures\HeadlineNode;
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
}
