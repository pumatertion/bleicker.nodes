<?php

namespace Bleicker\Nodes;

/**
 * Class AbstractContentNode
 *
 * @package Bleicker\Nodes
 */
class AbstractContentNode implements NodeInterface {

	/**
	 * @var integer
	 */
	protected $id;

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

}
