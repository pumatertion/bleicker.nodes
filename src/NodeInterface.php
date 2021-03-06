<?php
namespace Bleicker\Nodes;

use Bleicker\Nodes\Configuration\NodeConfigurationInterface;
use Bleicker\Translation\TranslateInterface;
use Doctrine\Common\Collections\Collection;

/**
 * Interface NodeInterface
 *
 * @package Bleicker\Nodes
 */
interface NodeInterface extends TranslateInterface {

	const SORTING_DIFF = 10;

	/**
	 * @param integer $sorting
	 * @return $this
	 */
	public function setSorting($sorting);

	/**
	 * @return integer
	 */
	public function getSorting();

	/**
	 * @return integer
	 */
	public function getId();

	/**
	 * @param NodeInterface $parent
	 * @return $this
	 */
	public function setParent(NodeInterface $parent = NULL);

	/**
	 * @return NodeInterface
	 */
	public function getParent();

	/**
	 * @return Collection
	 */
	public function getChildren();

	/**
	 * @param NodeInterface $child
	 * @return $this
	 */
	public function addChild(NodeInterface $child);

	/**
	 * @param NodeInterface $child
	 * @return $this
	 */
	public function removeChild(NodeInterface $child);

	/**
	 * @return string
	 */
	public function getNodeType();

	/**
	 * @return string
	 */
	public function getNodeTypeAbstraction();

	/**
	 * @return boolean
	 */
	public function getHidden();

	/**
	 * @param boolean $hidden
	 * @return $this
	 */
	public function setHidden($hidden);

	/**
	 * @param boolean $hiddenIfAuthenticated
	 * @return $this
	 */
	public function setHiddenIfAuthenticated($hiddenIfAuthenticated);

	/**
	 * @return boolean
	 */
	public function getHiddenIfAuthenticated();

	/**
	 * @param string $label
	 * @param string $description
	 * @param string $group
	 * @return NodeConfigurationInterface
	 */
	public static function register($label, $description, $group);
}