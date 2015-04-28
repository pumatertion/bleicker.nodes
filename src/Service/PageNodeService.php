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
	public function into(AbstractPageNode $node, AbstractPageNode $into) {
		/** @var AbstractPageNode $lastChild */
		$lastChild = $into->getChildren()->last();
		$node->setParent($into)->setSorting($lastChild === FALSE ? 0 : $lastChild->getSorting() + AbstractPageNode::SORTING_DIFF);
		$into->getChildren()->add($node);
		$this->entityManager->persist($into);
		return $this;
	}

	/**
	 * @param AbstractPageNode $node
	 * @param AbstractPageNode $after
	 * @return $this
	 */
	public function after(AbstractPageNode $node, AbstractPageNode $after) {
		$node->setSorting($after->getSorting() + 1);
		$node->setParent($after->getParent());
		$node->getParent()->getChildren()->add($node);

		$children = [];

		/** @var AbstractPageNode $child */
		foreach ($node->getParent()->getChildren() as $child) {
			$children[$child->getSorting()] = $child;
		}

		ksort($children);

		$index = 0;

		/** @var AbstractPageNode $child */
		foreach ($children as $child) {
			$node->getParent()->getChildren()->removeElement($child);
			$child->setSorting($index * AbstractPageNode::SORTING_DIFF);
			$node->getParent()->getChildren()->add($child);
			$index++;
		}

		unset($children);

		$this->entityManager->persist($node->getParent());
		return $this;
	}

	/**
	 * @param AbstractPageNode $node
	 * @param AbstractPageNode $after
	 * @return $this
	 */
	public function before(AbstractPageNode $node, AbstractPageNode $after) {
		$node->setSorting($after->getSorting() -1);
		$node->setParent($after->getParent());
		$node->getParent()->getChildren()->add($node);

		$children = [];

		/** @var AbstractPageNode $child */
		foreach ($node->getParent()->getChildren() as $child) {
			$children[$child->getSorting()] = $child;
		}

		ksort($children);

		$index = 0;

		/** @var AbstractPageNode $child */
		foreach ($children as $child) {
			$node->getParent()->getChildren()->removeElement($child);
			$child->setSorting($index * AbstractPageNode::SORTING_DIFF);
			$node->getParent()->getChildren()->add($child);
			$index++;
		}

		unset($children);

		$this->entityManager->persist($node->getParent());
		return $this;
	}
}
