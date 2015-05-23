<?php

namespace Bleicker\Nodes;

/**
 * Class AbstractSiteNode
 *
 * @package Bleicker\Nodes
 */
abstract class AbstractPluginNode extends AbstractContentNode implements PluginNodeInterface {

	use PluginNodeTrait;
}
