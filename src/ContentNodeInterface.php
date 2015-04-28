<?php

namespace Bleicker\Nodes;

/**
 * Interface ContentNodeInterface
 *
 * @package Bleicker\Nodes
 */
interface ContentNodeInterface extends NodeInterface {

	/**
	 * @return ContentNodeInterface
	 */
	public function getParent();

	/**
	 * @param ContentNodeInterface $node
	 * @return $this
	 */
	public function setParent(ContentNodeInterface $node);

}
