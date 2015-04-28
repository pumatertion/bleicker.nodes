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

	/**
	 * @param AbstractPageNode $node
	 * @param AbstractPageNode $into
	 * @return $this
	 */
	public function into(AbstractPageNode $node, AbstractPageNode $into = NULL){
		$this->entityManager->persist($node->setParent($into));
		$this->entityManager->flush();
		return $this;
	}
}
