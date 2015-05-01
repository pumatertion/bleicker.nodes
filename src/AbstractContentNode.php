<?php

namespace Bleicker\Nodes;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class AbstractContentNode
 *
 * @package Bleicker\Nodes
 */
abstract class AbstractContentNode extends AbstractNode implements ContentNodeInterface {

	/**
	 * @return string
	 */
	public function getNodeTypeAbstraction() {
		return self::class;
	}

}
