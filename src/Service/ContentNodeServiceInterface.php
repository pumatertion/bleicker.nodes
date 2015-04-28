<?php
namespace Bleicker\Nodes\Service;

use Bleicker\Nodes\ContentNodeInterface as NodeInterface;

/**
 * Class ContentNodeService
 *
 * @package Bleicker\Nodes\Service
 */
interface ContentNodeServiceInterface {

	/**
	 * @return string
	 */
	public function getType();

	/**
	 * @param NodeInterface $node
	 * @param NodeInterface $into
	 * @return $this
	 */
	public function into(NodeInterface $node, NodeInterface $into);

	/**
	 * @param NodeInterface $node
	 * @param NodeInterface $after
	 * @return $this
	 */
	public function before(NodeInterface $node, NodeInterface $after);

	/**
	 * @param NodeInterface $node
	 * @param NodeInterface $after
	 * @return $this
	 */
	public function after(NodeInterface $node, NodeInterface $after);
}