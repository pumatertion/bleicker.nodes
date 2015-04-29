<?php

namespace Bleicker\Nodes;

use Doctrine\Common\Collections\Collection;

/**
 * Class NodeTrait
 *
 * @package Bleicker\Nodes
 */
trait NodeTrait {

	/**
	 * @param Collection $collection
	 * @return void
	 */
	protected static function generateSorting(Collection $collection) {
		$multiplier = 0;
		/** @var NodeInterface $item */
		foreach ($collection as $item) {
			$item->setSorting($multiplier * NodeInterface::SORTING_DIFF);
			$multiplier++;
		}
	}

	/**
	 * @param Collection $collection
	 * @return void
	 */
	protected static function reorderBySorting(Collection $collection) {
		$arrayCopy = $collection->toArray();
		$collection->clear();
		usort($arrayCopy, function (NodeInterface $a, NodeInterface $b) {
			if ($a->getSorting() === $b->getSorting()) {
				return 0;
			}
			return ($a->getSorting() < $b->getSorting()) ? -1 : 1;
		});
		/** @var NodeInterface $node */
		foreach ($arrayCopy as $node) {
			$collection->add($node);
		}
		static::generateSorting($collection);
	}
}
