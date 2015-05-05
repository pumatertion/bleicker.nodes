<?php

namespace Bleicker\Nodes\Configuration;

use Bleicker\Nodes\Configuration\Exception\AlreadyRegisteredException;
use Bleicker\ObjectManager\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class NodeConfiguration
 *
 * @package Bleicker\Nodes\Configuration
 */
class NodeConfiguration implements NodeConfigurationInterface {

	/**
	 * @var string
	 */
	protected $label;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var string
	 */
	protected $group = self::UNDEFINED_GROUP;

	/**
	 * @var string
	 */
	protected $className;

	/**
	 * @var Collection
	 */
	protected $allowedChildren;

	/**
	 * @param string $className
	 * @param string $label
	 * @param string $description
	 * @param string $group
	 */
	public function __construct($className, $label, $description, $group) {
		$this->className = $className;
		$this->label = $label;
		$this->description = $description;
		$this->group = $group;
		$this->allowedChildren = new ArrayCollection(array_slice(func_get_args(), 4));
	}

	/**
	 * @param string $className
	 * @param string $label
	 * @param string $description
	 * @param string $group
	 * @param string $allowedChild
	 * @return self
	 */
	public static function instance($className, $label, $description, $group, $allowedChild = NULL){
		$reflection = new \ReflectionClass(static::class);
		return $reflection->newInstanceArgs(func_get_args());
	}

	/**
	 * @return Collection
	 */
	public function getAllowedChildren() {
		return $this->allowedChildren;
	}

	/**
	 * @param string $className
	 * @return $this
	 */
	public function setClassName($className) {
		$this->className = $className;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getClassName() {
		return $this->className;
	}

	/**
	 * @param string $group
	 * @return $this
	 */
	public function setGroup($group) {
		$this->group = $group;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getGroup() {
		return $this->group;
	}

	/**
	 * @param string $label
	 * @return $this
	 */
	public function setLabel($label) {
		$this->label = $label;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $alias
	 * @return $this
	 * @throws AlreadyRegisteredException
	 */
	public function register($alias) {
		/** @var NodeTypeConfigurationsInterface $configuration */
		$configuration = ObjectManager::get(NodeTypeConfigurationsInterface::class);
		if ($configuration->has($alias)) {
			throw new AlreadyRegisteredException('The alias is already registered', 1430837412);
		}
		$configuration->add($alias, $this);
	}
}
