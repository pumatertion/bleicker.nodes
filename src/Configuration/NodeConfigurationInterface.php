<?php
namespace Bleicker\Nodes\Configuration;

use Doctrine\Common\Collections\Collection;

/**
 * Interface NodeConfigurationInterface
 *
 * @package Bleicker\Nodes\Configuration
 */
interface NodeConfigurationInterface {

	const SITE_GROUP = 'Sites', PAGE_GROUP = 'Pages', CONTENT_GROUP = 'Content', UNDEFINED_GROUP = 'Others';

	/**
	 * @param string $description
	 * @return $this
	 */
	public function setDescription($description);

	/**
	 * @return string
	 */
	public function getDescription();

	/**
	 * @return Collection
	 */
	public function getAllowedChildren();

	/**
	 * @param string $className
	 * @return $this
	 */
	public function setClassName($className);

	/**
	 * @return string
	 */
	public function getClassName();

	/**
	 * @param string $group
	 * @return $this
	 */
	public function setGroup($group);

	/**
	 * @return string
	 */
	public function getGroup();

	/**
	 * @param string $label
	 * @return $this
	 */
	public function setLabel($label);

	/**
	 * @return string
	 */
	public function getLabel();

	/**
	 * @param string $alias
	 * @return $this
	 */
	public function register($alias);
}