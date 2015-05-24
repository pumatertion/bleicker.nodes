<?php

namespace Bleicker\Nodes;

use Bleicker\Nodes\Configuration\NodeConfiguration;
use Bleicker\Nodes\Exception\InvalidChildException;
use Bleicker\Nodes\Exception\InvalidParentException;
use Bleicker\Translation\TranslateTrait;
use Doctrine\Common\Collections\Collection;
use Bleicker\Nodes\Configuration\NodeConfigurationInterface;

/**
 * Class NodeTrait
 *
 * @package Bleicker\Nodes
 */
trait NodeTrait {

	use TranslateTrait;

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
	 * @throws InvalidParentException
	 */
	public function setParent(NodeInterface $parent = NULL) {
		if ($parent === $this) {
			throw new InvalidParentException('You can not move a node into itself', 1430826889);
		}
		if ($parent === NULL && $this->getParent() !== NULL) {
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
	 * @throws InvalidChildException
	 */
	public function addChild(NodeInterface $child) {
		if ($child === $this) {
			throw new InvalidChildException('You can not move a node into itself', 1430826890);
		}
		if ($child->getParent() !== $this) {
			$child->setParent($this);
		}
		if (!$this->getChildren()->contains($child)) {
			$this->getChildren()->add($child);
		}
		return $this;
	}

	/**
	 * @param NodeInterface $child
	 * @return $this
	 * @throws InvalidChildException
	 */
	public function removeChild(NodeInterface $child) {
		if ($child === $this) {
			throw new InvalidChildException('You can not remove a node from itself', 1430826891);
		}
		$child->setParent();
		if ($this->getChildren()->contains($child)) {
			$this->getChildren()->remove($child);
		}
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
	 * @return boolean
	 */
	public function getHidden() {
		return $this->hidden;
	}

	/**
	 * @param boolean $hidden
	 * @return $this
	 */
	public function setHidden($hidden) {
		$this->hidden = $hidden;
		return $this;
	}

	/**
	 * @param boolean $hiddenIfAuthenticated
	 * @return $this
	 */
	public function setHiddenIfAuthenticated($hiddenIfAuthenticated) {
		$this->hiddenIfAuthenticated = $hiddenIfAuthenticated;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getHiddenIfAuthenticated() {
		return $this->hiddenIfAuthenticated;
	}

	/**
	 * @param string $label
	 * @param string $description
	 * @param string $group
	 * @return NodeConfigurationInterface
	 */
	public static function register($label, $description, $group) {
		$arguments = ['className' => static::class];
		$arguments = array_merge($arguments, func_get_args());
		return call_user_func_array(NodeConfiguration::class . '::register', $arguments);
	}
}
