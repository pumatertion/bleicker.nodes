<?php

namespace Tests\Bleicker\Nodes\Functional\Fixtures;

use Bleicker\Nodes\AbstractContentNode;
use Doctrine\Common\Collections\Collection;

/**
 * Class ColumnNode
 *
 * @package Tests\Bleicker\Nodes\Functional\Fixtures
 */
class ColumnNode extends AbstractContentNode {

	/**
	 * @return Collection
	 */
	public function getChildren() {
		return $this->children;
	}
}
