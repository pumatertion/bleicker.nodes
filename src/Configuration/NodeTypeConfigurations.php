<?php

namespace Bleicker\Nodes\Configuration;

use Bleicker\Container\AbstractContainer;

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
}
