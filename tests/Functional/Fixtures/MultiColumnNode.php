<?php

namespace Tests\Bleicker\Nodes\Functional\Fixtures;

use Bleicker\Nodes\AbstractContentNode;
use Doctrine\Common\Collections\Collection;

/**
 * Class MultiColumnNode
 *
 * @package Tests\Bleicker\Nodes\Functional\Fixtures
 */
class MultiColumnNode extends AbstractContentNode {

	/**
	 * @return Collection
	 */
	public function getChildren() {
		return $this->children;
	}
}
