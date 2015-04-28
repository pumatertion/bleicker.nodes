<?php

namespace Bleicker\Nodes;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class AbstractPageNode
 *
 * @package Bleicker\Nodes
 */
class AbstractPageNode implements NodeInterface {

	/**
	 * @var integer
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $title;

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
	public function __construct($title = NULL) {
		$this->title = $title;
		$this->children = new ArrayCollection();
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
	public function setTitle($title = NULL) {
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
	 * @param AbstractPageNode $parent
	 * @return $this
	 */
	public function setParent($parent = NULL) {
		$this->parent = $parent;
		return $this;
	}

	/**
	 * @return AbstractPageNode
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

}
