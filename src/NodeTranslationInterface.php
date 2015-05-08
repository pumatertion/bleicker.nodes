<?php
namespace Bleicker\Nodes;

use Bleicker\Translation\TranslationInterface;

/**
 * Class NodeTranslation
 *
 * @package Bleicker\Nodes
 */
interface NodeTranslationInterface extends TranslationInterface {

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