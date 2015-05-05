<?php

namespace Bleicker\Nodes;

/**
 * Class SiteNodeTrait
 *
 * @package Bleicker\Nodes
 */
trait SiteNodeTrait {

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
