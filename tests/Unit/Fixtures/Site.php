<?php

namespace Tests\Bleicker\Nodes\Unit\Fixtures;

use Bleicker\Nodes\AbstractSiteNode;

/**
 * Class Site
 *
 * @package Tests\Bleicker\Nodes\Unit\Fixtures
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
