<?php

namespace Bleicker\Nodes;

/**
 * Class AbstractSiteNode
 *
 * @package Bleicker\Nodes
 */
abstract class AbstractSiteNode extends AbstractNode implements SiteNodeInterface {

	use SiteNodeTrait;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $domain;

	/**
	 * @return string
	 */
	public final function getNodeTypeAbstraction() {
		return self::class;
	}
}
