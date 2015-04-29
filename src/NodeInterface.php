<?php
namespace Bleicker\Nodes;

use Doctrine\Common\Collections\Collection;

/**
 * Interface NodeInterface
 *
 * @package Bleicker\Nodes
 */
interface NodeInterface {

	const SORTING_DIFF = 10;

	/**
	 * @param integer $sorting
	 * @return $this
	 */
	public function setSorting($sorting);

	/**
	 * @return integer
	 */
	public function getSorting();

	/**
	 * @return integer
	 */
	public function getId();

	/**
	 * @return Collection
	 */
	public function getChildren();
}