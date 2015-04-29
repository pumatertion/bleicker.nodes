<?php

namespace Tests\Bleicker\Nodes\Unit\Fixtures;

use Bleicker\Nodes\AbstractPageNode;

/**
 * Class Page
 *
 * @package Tests\Bleicker\Nodes\Functional\Fixtures
 */
class Page extends AbstractPageNode {

	/**
	 * @param string $title
	 */
	public function __construct($title = 'foo') {
		parent::__construct($title);
	}
}
