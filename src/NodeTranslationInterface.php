<?php
namespace Bleicker\Nodes;

/**
 * Class NodeTranslation
 *
 * @package Bleicker\Nodes
 */
interface NodeTranslationInterface {

	/**
	 * @return NodeInterface
	 */
	public function getNode();

	/**
	 * @param NodeInterface $node
	 * @return $this
	 */
	public function setNode($node);

	/**
	 * @return integer
	 */
	public function getId();
}