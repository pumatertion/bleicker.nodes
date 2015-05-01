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
	 * @param NodeInterface $parent
	 * @return $this
	 */
	public function setParent(NodeInterface $parent = NULL);

	/**
	 * @return NodeInterface
	 */
	public function getParent();

	/**
	 * @return Collection
	 */
	public function getChildren();

	/**
	 * @param NodeInterface $child
	 * @return $this
	 */
	public function addChild(NodeInterface $child);

	/**
	 * @param NodeInterface $child
	 * @param NodeInterface $after
	 * @return $this
	 */
	public function addChildAfter(NodeInterface $child, NodeInterface $after);

	/**
	 * @param NodeInterface $child
	 * @param NodeInterface $after
	 * @return $this
	 */
	public function addChildBefore(NodeInterface $child, NodeInterface $after);

	/**
	 * @param NodeInterface $child
	 * @return $this
	 */
	public function removeChild(NodeInterface $child);

	/**
	 * @return $this
	 */
	public function clearChildren();

	/**
	 * @return string
	 */
	public function getNodeType();

	/**
	 * @return string
	 */
	public function getNodeTypeAbstraction();
}