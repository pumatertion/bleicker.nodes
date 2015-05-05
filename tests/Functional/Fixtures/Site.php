<?php

namespace Tests\Bleicker\Nodes\Functional\Fixtures;

use Bleicker\Nodes\AbstractSiteNode;

/**
 * Class Site
 *
 * @package Tests\Bleicker\Nodes\Functional\Fixtures
 */
class Site extends AbstractSiteNode {

	/**
	 * @param string $title
	 */
	public function __construct($title = 'site') {
		parent::__construct();
		$this->title = $title;
	}
}
