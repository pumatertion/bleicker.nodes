<?php

namespace Tests\Bleicker\Nodes\Unit\Fixtures;

use Bleicker\Nodes\AbstractContentNode;

/**
 * Class BlacklistedContent
 *
 * @package Tests\Bleicker\Nodes\Unit\Fixtures
 */
class BlacklistedContent extends AbstractContentNode {

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
