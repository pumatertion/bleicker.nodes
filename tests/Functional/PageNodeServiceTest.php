<?php

namespace Tests\Bleicker\Nodes\Functional;

use Bleicker\Nodes\AbstractPageNode;
use Bleicker\Nodes\Service\PageNodeService;
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
}
