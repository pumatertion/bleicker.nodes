<?php

namespace Bleicker\Nodes\Service;

use Bleicker\Nodes\AbstractPageNode;
use Bleicker\Nodes\PageNodeInterface as NodeInterface;

/**
 * Class PageNodeService
 *
 * @package Bleicker\Nodes\Service
 */
class PageNodeService extends AbstractNodeService implements PageNodeServiceInterface {

	/**
	 * @return string
	 */
	public function getType() {
		return AbstractPageNode::class;
	}

	/**
	 * @param NodeInterface $node
	 * @param NodeInterface $into
	 * @return $this
	 */
	public function into(NodeInterface $node, NodeInterface $into) {
		/** @var NodeInterface $lastChild */
		$lastChild = $into->getChildren()->last();
		$node->setParent($into)->setSorting($lastChild === FALSE ? 0 : $lastChild->getSorting() + NodeInterface::SORTING_DIFF);
		$into->getChildren()->add($node);
		$this->entityManager->persist($into);
		return $this;
	}

	/**
	 * @param NodeInterface $node
	 * @param NodeInterface $after
	 * @return $this
	 */
	public function after(NodeInterface $node, NodeInterface $after) {
		$node->setSorting($after->getSorting() + 1);
		$node->setParent($after->getParent());
		$node->getParent()->getChildren()->add($node);

		$children = [];

		/** @var NodeInterface $child */
		foreach ($node->getParent()->getChildren() as $child) {
			$children[$child->getSorting()] = $child;
		}

		ksort($children);

		$index = 0;

		/** @var NodeInterface $child */
		foreach ($children as $child) {
			$node->getParent()->getChildren()->removeElement($child);
			$child->setSorting($index * NodeInterface::SORTING_DIFF);
			$node->getParent()->getChildren()->add($child);
			$index++;
		}

		unset($children);

		$this->entityManager->persist($node->getParent());
		return $this;
	}

	/**
	 * @param NodeInterface $node
	 * @param NodeInterface $after
	 * @return $this
	 */
	public function before(NodeInterface $node, NodeInterface $after) {
		$node->setSorting($after->getSorting() - 1);
		$node->setParent($after->getParent());
		$node->getParent()->getChildren()->add($node);

		$children = [];

		/** @var NodeInterface $child */
		foreach ($node->getParent()->getChildren() as $child) {
			$children[$child->getSorting()] = $child;
		}

		ksort($children);

		$index = 0;

		/** @var NodeInterface $child */
		foreach ($children as $child) {
			$node->getParent()->getChildren()->removeElement($child);
			$child->setSorting($index * NodeInterface::SORTING_DIFF);
			$node->getParent()->getChildren()->add($child);
			$index++;
		}

		unset($children);

		$this->entityManager->persist($node->getParent());
		return $this;
	}
}
