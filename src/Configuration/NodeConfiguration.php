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
	 * @var string
	 */
	protected $alias;

	/**
	 * @var Collection
	 */
	protected $allowedChildren;

	/**
	 * @var Collection
	 */
	protected $forbiddenChildren;

	/**
	 * @param string $className
	 * @param string $alias
	 * @param string $label
	 * @param string $description
	 * @param string $group
	 * @param array $allowedChildren
	 * @param array $forbiddenChildren
	 */
	public function __construct($className, $alias, $label, $description, $group, array $allowedChildren = [], array $forbiddenChildren = []) {
		$this->className = $className;
		$this->alias = $alias;
		$this->label = $label;
		$this->description = $description;
		$this->group = $group;
		$this->allowedChildren = new ArrayCollection($allowedChildren);
		$this->forbiddenChildren = new ArrayCollection($forbiddenChildren);
	}

	/**
	 * @param string $className
	 * @param string $alias
	 * @param string $label
	 * @param string $description
	 * @param string $group
	 * @param array $allowedChildren
	 * @param array $forbiddenChildren
	 * @return void
	 */
	public static function register($className, $alias, $label, $description, $group, array $allowedChildren = [], array $forbiddenChildren = []) {
		/** @var NodeTypeConfigurationsInterface $configurations */
		$configurations = ObjectManager::get(NodeTypeConfigurationsInterface::class, function(){
			$nodeTypeConfigurations = new NodeTypeConfigurations();
			ObjectManager::add(NodeTypeConfigurationsInterface::class, $nodeTypeConfigurations, TRUE);
			return $nodeTypeConfigurations;
		});

		/** @var self $configuration */
		$reflection = new \ReflectionClass(static::class);
		$configuration = $reflection->newInstanceArgs(func_get_args());
		$configurations->add($configuration->getAlias(), $configuration);
	}

	/**
	 * @return Collection
	 */
	public function getAllowedChildren() {
		return $this->allowedChildren;
	}

	/**
	 * @return Collection
	 */
	public function getForbiddenChildren() {
		return $this->forbiddenChildren;
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
	 */
	public function setAlias($alias = NULL) {
		$this->alias = $alias;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAlias() {
		return $this->alias;
	}

	/**
	 * @param string $classOrInterfaceName
	 * @return boolean
	 */
	public function allowsChild($classOrInterfaceName) {
		$isWhiteListed = (boolean)$this->getAllowedChildren()->filter(function ($allowedClassOrInterfaceName) use ($classOrInterfaceName) {
			if ($allowedClassOrInterfaceName === $classOrInterfaceName) {
				return TRUE;
			}
			$checkParents = class_parents($classOrInterfaceName, TRUE);
			if (in_array($allowedClassOrInterfaceName, $checkParents)) {
				return TRUE;
			}
			$checkImplementations = class_implements($classOrInterfaceName, TRUE);
			if (in_array($allowedClassOrInterfaceName, $checkImplementations)) {
				return TRUE;
			}
			return FALSE;
		})->count();

		$isBlackListed = (boolean)$this->getForbiddenChildren()->filter(function ($allowedClassOrInterfaceName) use ($classOrInterfaceName) {
			if ($allowedClassOrInterfaceName === $classOrInterfaceName) {
				return TRUE;
			}
			$checkParents = class_parents($classOrInterfaceName, TRUE);
			if (in_array($allowedClassOrInterfaceName, $checkParents)) {
				return TRUE;
			}
			$checkImplementations = class_implements($classOrInterfaceName, TRUE);
			if (in_array($allowedClassOrInterfaceName, $checkImplementations)) {
				return TRUE;
			}
			return FALSE;
		})->count();

		return $isWhiteListed === TRUE && $isBlackListed === FALSE;
	}
}
