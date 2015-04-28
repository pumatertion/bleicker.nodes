<?php

namespace Bleicker\Nodes\Service;

use Bleicker\Nodes\AbstractPageNode;

/**
 * Class PageNodeService
 *
 * @package Bleicker\Nodes\Service
 */
class PageNodeService extends AbstractNodeService {

	/**
	 * @return string
	 */
	public function getType() {
		return AbstractPageNode::class;
	}
}
