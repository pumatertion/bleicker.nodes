<?php

namespace Bleicker\Nodes;

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
	 * @return boolean
	 */
	public function getHiddenInMenu();

	/**
	 * @param boolean $hiddenInMenu
	 * @return $this
	 */
	public function setHiddenInMenu($hiddenInMenu);
}
