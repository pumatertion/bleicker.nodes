<?php

namespace Bleicker\Nodes;

/**
 * Interface ContentNodeInterface
 *
 * @package Bleicker\Nodes
 */
interface ContentNodeInterface extends NodeInterface {

	/**
	 * @param PageNodeInterface $node
	 * @return mixed
	 */
	public function setPage(PageNodeInterface $node = NULL);

	/**
	 * @return PageNodeInterface
	 */
	public function getPage();
	
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
