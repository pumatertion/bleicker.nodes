<?php

namespace Bleicker\Nodes;

use Bleicker\Translation\TranslateInterface;
use Bleicker\Translation\TranslateTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class AbstractNode
 *
 * @package Bleicker\Nodes
 */
abstract class AbstractNode implements NodeInterface, TranslateInterface {

	use TranslateTrait;

	/**
	 * @var integer
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $nodeType;

	/**
	 * @var string
	 */
	protected $nodeTypeAbstraction;

	/**
	 * @var integer
	 */
	protected $sorting;

	/**
	 * @var NodeInterface
	 */
	protected $parent;

	/**
	 * @var Collection
	 */
	protected $children;

	/**
	 * @var Collection
	 */
	protected $translations;

	public function __construct() {
		$this->children = new ArrayCollection();
		$this->translations = new ArrayCollection();
		$this->nodeType = $this->getNodeType();
		$this->nodeTypeAbstraction = $this->getNodeTypeAbstraction();
		$this->sorting = static::SORTING_DIFF;
	}

	/**
	 * @return string
	 */
	public function getNodeType() {
		return static::class;
	}

	/**
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param NodeInterface $parent
	 * @return $this
	 */
	public function setParent(NodeInterface $parent = NULL) {
		if($parent === NULL && $this->getParent() !== NULL){
			$this->getParent()->removeChild($this);
		}
		if ($parent !== NULL) {
			$this->parent = $parent;
			$this->parent->addChild($this);
		}
	}

	/**
	 * @return NodeInterface
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
	 * @param NodeInterface $child
	 * @return $this
	 */
	public function addChild(NodeInterface $child) {
		if($child->getParent() !== $this){
			$child->setParent($this);
		}
		if(!$this->getChildren()->contains($child)){
			$this->getChildren()->add($child);
		}
		return $this;
	}

	/**
	 * @param NodeInterface $child
	 * @return $this
	 */
	public function removeChild(NodeInterface $child) {
		$child->setParent();
		if($this->getChildren()->contains($child)){
			$this->getChildren()->remove($child);
		}
		return $this;
	}

	/**
	 * @return $this
	 */
	public function clearChildren() {
		$this->getChildren()->map(function (NodeInterface $child) {
			$child->setParent();
		});
		$this->getChildren()->clear();
		return $this;
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
