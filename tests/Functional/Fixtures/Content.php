<?php

namespace Tests\Bleicker\Nodes\Functional\Fixtures;

use Bleicker\Nodes\AbstractContentNode;

/**
 * Class Content
 *
 * @package Tests\Bleicker\Nodes\Functional\Fixtures
 */
class Content extends AbstractContentNode {

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @param string $title
	 */
	public function __construct($title = 'content') {
		parent::__construct();
		$this->title = $title;
	}
}
