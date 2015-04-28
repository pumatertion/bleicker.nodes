<?php
namespace Bleicker\Nodes;

/**
 * Class Node
 *
 * @package Bleicker\Nodes
 */
interface NodeInterface {

	const SORTING_DIFF = 10;

	/**
	 * @return int
	 */
	public function getId();
}