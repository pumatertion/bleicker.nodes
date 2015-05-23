<?php
namespace Bleicker\Nodes\Configuration;

/**
 * Interface NodeConfigurationInterface
 *
 * @package Bleicker\Nodes\Configuration
 */
interface NodeConfigurationInterface {

	const SITE_GROUP = 'Sites', PAGE_GROUP = 'Pages', CONTENT_GROUP = 'Content', PLUGIN_GROUP = 'Plugins', UNDEFINED_GROUP = 'Others';

	/**
	 * @return string
	 */
	public function getDescription();

	/**
	 * @return string
	 */
	public function getClassName();

	/**
	 * @return string
	 */
	public function getGroup();

	/**
	 * @return string
	 */
	public function getLabel();

	/**
	 * @param string $className
	 * @param string $label
	 * @param string $description
	 * @param string $group
	 * @return NodeConfigurationInterface
	 */
	public static function register($className, $label, $description, $group);

	/**
	 * @param string $classOrInterfaceName
	 * @return boolean
	 */
	public function allowsChild($classOrInterfaceName);

	/**
	 * @param string $classOrInterfaceName
	 * @return $this
	 */
	public function allowChild($classOrInterfaceName);

	/**
	 * @param string $classOrInterfaceName
	 * @return $this
	 */
	public function forbidChild($classOrInterfaceName);
}