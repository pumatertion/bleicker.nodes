<?php

namespace Bleicker\Nodes\Configuration;

use Bleicker\Container\AbstractContainer;
use Bleicker\Nodes\Configuration\Exception\NotRegisteredException;

/**
 * Class NodeTypeConfigurations
 *
 * @package Bleicker\Nodes\Configuration
 */
class NodeTypeConfigurations extends AbstractContainer implements NodeTypeConfigurationsInterface {

	/**
	 * @var array
	 */
	protected static $storage = [];

	/**
	 * @param string $alias
	 * @return NodeConfigurationInterface
	 */
	public static function get($alias) {
		return parent::get($alias);
	}

	/**
	 * @param string $alias
	 * @param NodeConfigurationInterface $data
	 * @return static
	 */
	public static function add($alias, $data) {
		return parent::add($alias, $data);
	}

	/**
	 * @param string $alias
	 * @param string $check
	 * @return boolean
	 * @throws NotRegisteredException
	 */
	public static function allowsChild($alias, $check) {
		if (!static::has($alias)) {
			throw new NotRegisteredException('"' . $alias . '" is not registerd', 1430843799);
		}
		$configuration = static::get($alias);
		$allowedChildrenMatchingResults = $configuration->getAllowedChildren()->filter(function ($allowedClassOrInterfaceName) use ($check) {
			if ($allowedClassOrInterfaceName === $check) {
				return TRUE;
			}
			$checkParents = class_parents($check, TRUE);
			if (in_array($allowedClassOrInterfaceName, $checkParents)) {
				return TRUE;
			}
			$checkImplementations = class_implements($check, TRUE);
			if (in_array($allowedClassOrInterfaceName, $checkImplementations)) {
				return TRUE;
			}
		});
		return (boolean)$allowedChildrenMatchingResults->count();
	}
}
