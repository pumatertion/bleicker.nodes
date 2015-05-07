<?php

namespace Bleicker\Nodes;

use Bleicker\Translation\Translation;

/**
 * Class NodeTranslation
 *
 * @package Bleicker\Nodes
 */
class NodeTranslation extends Translation implements NodeTranslationInterface {

	/**
	 * @var integer
	 */
	protected $id;

	/**
	 * @var NodeInterface
	 */
	protected $node;

	public function __construct($propertyName, Locale $locale, $value = NULL) {
		parent::__construct($propertyName, $locale, $value);
	}

	/**
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param NodeInterface $node
	 * @return $this
	 */
	public function setNode($node) {
		$this->node = $node;
		return $this;
	}

	/**
	 * @return NodeInterface
	 */
	public function getNode() {
		return $this->node;
	}
}
