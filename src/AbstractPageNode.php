<?php

namespace Bleicker\Nodes;

/**
 * Class AbstractPageNode
 *
 * @package Bleicker\Nodes
 */
abstract class AbstractPageNode extends AbstractNode implements PageNodeInterface {

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @param string $title
	 * @return $this
	 */
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}
}
