<?php

namespace Bleicker\Nodes;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class AbstractNode
 *
 * @package Bleicker\Nodes
 */
abstract class AbstractNode implements NodeInterface {

	use NodeTrait;

	/**
	 * @var integer
	 */
	protected $id;

	/**
	 * @var boolean
	 */
	protected $hidden;

	/**
	 * @var boolean
	 */
	protected $hiddenIfAuthenticated;

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
		$this->hidden = FALSE;
		$this->hiddenIfAuthenticated = FALSE;
	}
}
