<?php

namespace Bleicker\Nodes\Service;

use Bleicker\Nodes\AbstractContentNode;

/**
 * Class ContentNodeService
 *
 * @package Bleicker\Nodes\Service
 */
class ContentNodeService extends AbstractNodeService {

	/**
	 * @return string
	 */
	public function getType() {
		return AbstractContentNode::class;
	}
}
