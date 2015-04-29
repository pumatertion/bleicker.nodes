<?php

namespace Bleicker\Nodes;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class AbstractPageNode
 *
 * @package Bleicker\Nodes
 */
abstract class AbstractPageNode implements PageNodeInterface {

	use NodeTrait;

	/**
	 * @var integer
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var integer
	 */
	protected $sorting;

	/**
	 * @var PageNodeInterface
	 */
	protected $parent;

	/**
	 * @var Collection
	 */
	protected $children;

	/**
	 * @var Collection
	 */
	protected $content;

	/**
	 * @param string $title
	 */
	public function __construct($title) {
		$this->title = $title;
		$this->children = new ArrayCollection();
		$this->content = new ArrayCollection();
		$this->sorting = 0;
	}

	/**
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

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

	/**
	 * @return PageNodeInterface
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * @return Collection
	 */
	public function getChildren() {
		return $this->children;
	}

	/**
	 * @param PageNodeInterface $child
	 * @return $this
	 */
	public function addChild(PageNodeInterface $child) {
		$this->getChildren()->add($child);
		static::generateSorting($this->getChildren());
		return $this;
	}

	/**
	 * @param PageNodeInterface $child
	 * @param PageNodeInterface $after
	 * @return $this
	 */
	public function addChildAfter(PageNodeInterface $child, PageNodeInterface $after) {
		$child->setSorting($after->getSorting() + 1);
		$this->getChildren()->add($child);
		static::reorderBySorting($this->getChildren());
		return $this;
	}

	/**
	 * @param PageNodeInterface $child
	 * @param PageNodeInterface $after
	 * @return $this
	 */
	public function addChildBefore(PageNodeInterface $child, PageNodeInterface $after) {
		$child->setSorting($after->getSorting() - 1);
		$this->getChildren()->add($child);
		static::reorderBySorting($this->getChildren());
		return $this;
	}

	/**
	 * @param PageNodeInterface $child
	 * @return $this
	 */
	public function removeChild(PageNodeInterface $child) {
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
	 * @return Collection
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param integer $sorting
	 * @return $this
	 */
	public function setSorting($sorting) {
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
