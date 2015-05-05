<?php

namespace Bleicker\Nodes;

/**
 * Interface SiteNodeInterface
 *
 * @package Bleicker\Nodes
 */
interface SiteNodeInterface extends NodeInterface {

	/**
	 * @param string $title
	 * @return $this
	 */
	public function setTitle($title);

	/**
	 * @return string
	 */
	public function getTitle();
}
