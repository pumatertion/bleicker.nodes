<?php

namespace Bleicker\Nodes;

use Doctrine\Common\Collections\Collection;

/**
 * Interface PageNodeInterface
 *
 * @package Bleicker\Nodes
 */
interface PageNodeInterface extends NodeInterface {

	/**
	 * @param string $title
	 * @return $this
	 */
	public function setTitle($title);

	/**
	 * @return string
	 */
	public function getTitle();

	/**
	 * @return PageNodeInterface
	 */
	public function getParent();

	/**
	 * @return Collection
	 */
	public function getContent();

}
