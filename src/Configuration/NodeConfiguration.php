<?php

namespace Bleicker\Nodes\Configuration;

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
	 * @var array
	 */
	protected $allowedChildren;

	/**
	 * @var Collection
	 */
	protected $forbiddenChildren;

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
		$this->allowedChildren = new ArrayCollection();
		$this->forbiddenChildren = new ArrayCollection();
	}

	/**
	 * @param string $className
	 * @param string $label
	 * @param string $description
	 * @param string $group
	 * @return NodeConfigurationInterface
	 */
	public static function register($className, $label, $description, $group) {
		/** @var NodeTypeConfigurationsInterface $configurations */
		$configurations = ObjectManager::get(NodeTypeConfigurationsInterface::class, function () {
			$nodeTypeConfigurations = new NodeTypeConfigurations();
			ObjectManager::add(NodeTypeConfigurationsInterface::class, $nodeTypeConfigurations, TRUE);
			return $nodeTypeConfigurations;
		});

		/** @var NodeConfigurationInterface $configuration */
		$reflection = new \ReflectionClass(static::class);
		$configuration = $reflection->newInstanceArgs(func_get_args());
		$configurations->add($configuration->getClassName(), $configuration);
		return $configuration;
	}

	/**
	 * @return string
	 */
	public function getClassName() {
		return $this->className;
	}

	/**
	 * @return string
	 */
	public function getGroup() {
		return $this->group;
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $classOrInterfaceName
	 * @return boolean
	 */
	public function allowsChild($classOrInterfaceName) {
		$isWhiteListed = (boolean)$this->allowedChildren->filter(function ($allowedClassOrInterfaceName) use ($classOrInterfaceName) {
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

		$isBlackListed = (boolean)$this->forbiddenChildren->filter(function ($allowedClassOrInterfaceName) use ($classOrInterfaceName) {
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

	/**
	 * @param string $classOrInterfaceName
	 * @return $this
	 */
	public function allowChild($classOrInterfaceName) {
		if($this->forbiddenChildren->offsetExists($classOrInterfaceName)){
			$this->forbiddenChildren->offsetUnset($classOrInterfaceName);
		}
		if (!$this->allowedChildren->offsetExists($classOrInterfaceName)) {
			$this->allowedChildren->offsetSet($classOrInterfaceName, $classOrInterfaceName);
		}
		return $this;
	}

	/**
	 * @param string $classOrInterfaceName
	 * @return $this
	 */
	public function forbidChild($classOrInterfaceName) {
		if ($this->allowedChildren->offsetExists($classOrInterfaceName)) {
			$this->allowedChildren->offsetUnset($classOrInterfaceName);
		}
		if(!$this->forbiddenChildren->offsetExists($classOrInterfaceName)){
			$this->forbiddenChildren->offsetSet($classOrInterfaceName, $classOrInterfaceName);
		}
		return $this;
	}
}
