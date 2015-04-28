<?php

namespace Bleicker\Nodes;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class AbstractContentNode
 *
 * @package Bleicker\Nodes
 */
class AbstractContentNode implements NodeInterface {

	/**
	 * @var integer
	 */
	protected $id;

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @var integer
	 */
	protected $sorting;

	/**
	 * @var AbstractPageNode
	 */
	protected $parent;

	/**
	 * @var Collection
	 */
	protected $children;

	/**
	 * @param string $title
	 */
	public function __construct($title) {
		$this->title = $title;
		$this->children = new ArrayCollection();
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

	/**
	 * @param \Bleicker\Nodes\AbstractPageNode $parent
	 * @return $this
	 */
	public function setParent($parent = NULL) {
		$this->parent = $parent;
		return $this;
	}

	/**
	 * @return \Bleicker\Nodes\AbstractPageNode
	 */
	public function getParent() {
		return $this->parent;
	}
}
