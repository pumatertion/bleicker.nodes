<?php

namespace Bleicker\Nodes;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class AbstractContentNode
 *
 * @package Bleicker\Nodes
 */
abstract class AbstractContentNode implements ContentNodeInterface {

	use NodeTrait;

	/**
	 * @var integer
	 */
	protected $id;

	/**
	 * @var integer
	 */
	protected $sorting;

	/**
	 * @var ContentNodeInterface
	 */
	protected $parent;

	/**
	 * @var Collection
	 */
	protected $children;

	/**
	 * @var PageNodeInterface
	 */
	protected $page;

	public function __construct() {
		$this->children = new ArrayCollection();
		$this->sorting = 0;
	}

	/**
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return ContentNodeInterface
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * @return PageNodeInterface
	 */
	public function getPage() {
		return $this->page;
	}

	/**
	 * @return Collection
	 */
	public function getChildren() {
		return $this->children;
	}

	/**
	 * @param ContentNodeInterface $child
	 * @return $this
	 */
	public function addChild(ContentNodeInterface $child) {
		$this->getChildren()->add($child);
		static::generateSorting($this->getChildren());
		return $this;
	}

	/**
	 * @param ContentNodeInterface $child
	 * @param ContentNodeInterface $after
	 * @return $this
	 */
	public function addChildAfter(ContentNodeInterface $child, ContentNodeInterface $after) {
		$child->setSorting($after->getSorting() + 1);
		$this->getChildren()->add($child);
		static::reorderBySorting($this->getChildren());
		return $this;
	}

	/**
	 * @param ContentNodeInterface $child
	 * @param ContentNodeInterface $after
	 * @return $this
	 */
	public function addChildBefore(ContentNodeInterface $child, ContentNodeInterface $after) {
		$child->setSorting($after->getSorting() - 1);
		$this->getChildren()->add($child);
		static::reorderBySorting($this->getChildren());
		return $this;
	}

	/**
	 * @param ContentNodeInterface $child
	 * @return $this
	 */
	public function removeChild(ContentNodeInterface $child) {
		$this->getChildren()->removeElement($child);
		static::generateSorting($this->getChildren());
		return $this;
	}

	/**
	 * @return $this
	 */
	public function clearChildren() {
		$this->getChildren()->clear();
		return $this;
	}

	/**
	 * @param integer $sorting
	 * @return $this
	 */
	public function setSorting($sorting = NULL) {
		$this->sorting = $sorting;
		return $this;
	}

	/**
	 * @return integer
	 */
	public function getSorting() {
		return $this->sorting;
	}
}
