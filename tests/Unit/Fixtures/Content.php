<?php

namespace Tests\Bleicker\Nodes\Unit\Fixtures;

use Bleicker\Nodes\AbstractContentNode;

/**
 * Class Content
 *
 * @package Tests\Bleicker\Nodes\Unit\Fixtures
 */
class Content extends AbstractContentNode {

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @param string $title
	 */
	public function __construct($title = NULL) {
		parent::__construct();
		$this->title = $title;
	}
}
