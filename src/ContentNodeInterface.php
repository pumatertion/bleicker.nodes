<?php

namespace Bleicker\Nodes;

/**
 * Interface ContentNodeInterface
 *
 * @package Bleicker\Nodes
 */
interface ContentNodeInterface extends NodeInterface {

	/**
	 * @return PageNodeInterface
	 */
	public function getPage();

	/**
	 * @return ContentNodeInterface
	 */
	public function getParent();

}
