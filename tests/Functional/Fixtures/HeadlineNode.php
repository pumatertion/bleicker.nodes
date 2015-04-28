<?php

namespace Tests\Bleicker\Nodes\Functional\Fixtures;
use Bleicker\Nodes\AbstractContentNode;

/**
 * Class HeadlineNode
 *
 * @package Tests\Bleicker\Nodes\Functional\Fixtures
 */
class HeadlineNode extends AbstractContentNode {

	/**
	 * @var string
	 */
	protected $header;

	/**
	 * @param string $header
	 * @return $this
	 */
	public function setHeader($header = NULL) {
		$this->header = $header;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getHeader() {
		return $this->header;
	}



}
