<?php
namespace Bleicker\Nodes\Configuration;

use Bleicker\Nodes\Configuration\NodeConfigurationInterface;

/**
 * Class NodeTypeConfigurations
 *
 * @package Bleicker\Nodes\Configuration
 */
interface NodeTypeConfigurationsInterface {

	/**
	 * @return array
	 */
	public static function storage();

	/**
	 * @param string $alias
	 * @return boolean
	 */
	public static function has($alias);

	/**
	 * @param string $alias
	 * @param NodeConfigurationInterface $data
	 * @return static
	 */
	public static function add($alias, $data);

	/**
	 * @return static
	 */
	public static function prune();

	/**
	 * @param string $alias
	 * @return NodeConfigurationInterface
	 */
	public static function get($alias);

	/**
	 * @param string $alias
	 * @return static
	 */
	public static function remove($alias);
}