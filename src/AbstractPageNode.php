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
	 * @var boolean
	 */
	protected $hiddenInMenu;

	/**
	 * @return string
	 */
	public final function getNodeTypeAbstraction() {
		return self::class;
	}
}
