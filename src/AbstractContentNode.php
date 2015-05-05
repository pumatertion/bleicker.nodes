<?php

namespace Bleicker\Nodes;

/**
 * Class AbstractContentNode
 *
 * @package Bleicker\Nodes
 */
abstract class AbstractContentNode extends AbstractNode implements ContentNodeInterface {

	use ContentNodeTrait;

	/**
	 * @return string
	 */
	public final function getNodeTypeAbstraction() {
		return self::class;
	}
}
