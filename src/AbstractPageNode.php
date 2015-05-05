<?php

namespace Bleicker\Nodes;

/**
 * Class AbstractPageNode
 *
 * @package Bleicker\Nodes
 */
abstract class AbstractPageNode extends AbstractNode implements PageNodeInterface {

	use PageNodeTrait;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @return string
	 */
	public function getNodeTypeAbstraction() {
		return self::class;
	}
}
