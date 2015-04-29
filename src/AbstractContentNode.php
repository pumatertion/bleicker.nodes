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
	}

	/**
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param ContentNodeInterface $parent
	 * @return $this
	 */
	public function setParent(ContentNodeInterface $parent) {
		$this->parent = $parent;
		return $this;
	}

	/**
	 * @return ContentNodeInterface
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * @param PageNodeInterface $node
	 * @return $this
	 */
	public function setPage(PageNodeInterface $node = NULL) {
		$this->page = $node;
		return $this;
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
