<?php

namespace Bleicker\Nodes;

/**
 * Class PageNodeTrait
 *
 * @package Bleicker\Nodes
 */
trait PageNodeTrait {

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
