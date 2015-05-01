<?php

namespace Bleicker\Nodes;

use Bleicker\Nodes\Exception\CriteriaFilteringNotSupportedForThisTypeOfCollectionException;
use Bleicker\Translation\TranslateInterface;
use Bleicker\Translation\TranslateTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

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
		$this->sorting = 0;
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
		$this->parent = $parent;
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
	 * @param Criteria $criteria
	 * @return Collection
	 */
	public function getChildrenByCriteria(Criteria $criteria) {
		return $this->getChildren()->matching($criteria);
	}

	/**
	 * @param NodeInterface $child
	 * @return $this
	 */
	public function addChild(NodeInterface $child) {
		$child->setParent($this);
		$this->getChildren()->add($child);
		static::generateSorting($this->getChildren());
		return $this;
	}

	/**
	 * @param NodeInterface $child
	 * @param NodeInterface $after
	 * @return $this
	 */
	public function addChildAfter(NodeInterface $child, NodeInterface $after) {
		$child->setParent($this);
		$child->setSorting($after->getSorting() + 1);
		$this->getChildren()->add($child);
		static::reorderBySorting($this->getChildren());
		return $this;
	}

	/**
	 * @param NodeInterface $child
	 * @param NodeInterface $after
	 * @return $this
	 */
	public function addChildBefore(NodeInterface $child, NodeInterface $after) {
		$child->setParent($this);
		$child->setSorting($after->getSorting() - 1);
		$this->getChildren()->add($child);
		static::reorderBySorting($this->getChildren());
		return $this;
	}

	/**
	 * @param NodeInterface $child
	 * @return $this
	 */
	public function removeChild(NodeInterface $child) {
		$child->setParent();
		$this->getChildren()->removeElement($child);
		static::generateSorting($this->getChildren());
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

	/**
	 * @param Collection $collection
	 * @return void
	 */
	protected static function generateSorting(Collection $collection) {
		$multiplier = 0;
		/** @var NodeInterface $item */
		foreach ($collection as $item) {
			$item->setSorting($multiplier * NodeInterface::SORTING_DIFF);
			$multiplier++;
		}
	}

	/**
	 * @param Collection $collection
	 * @return void
	 */
	protected static function reorderBySorting(Collection $collection) {
		$arrayCopy = $collection->toArray();
		$collection->clear();
		usort($arrayCopy, function (NodeInterface $a, NodeInterface $b) {
			if ($a->getSorting() === $b->getSorting()) {
				return 0;
			}
			return ($a->getSorting() < $b->getSorting()) ? -1 : 1;
		});
		/** @var NodeInterface $node */
		foreach ($arrayCopy as $node) {
			$collection->add($node);
		}
		static::generateSorting($collection);
	}
}
