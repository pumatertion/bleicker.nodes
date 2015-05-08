<?php
namespace Bleicker\Nodes;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

/**
 * Class NodeService
 *
 * @package Bleicker\Nodes
 */
interface NodeServiceInterface {

	/**
	 * @return Collection
	 */
	public function findSites();

	/**
	 * @param NodeInterface $node
	 * @param NodeInterface $after
	 * @return $this
	 * @api
	 */
	public function addAfter(NodeInterface $node, NodeInterface $after);

	/**
	 * @param NodeInterface $node
	 * @param NodeTranslationInterface $translation
	 * @return $this
	 * @api
	 */
	public function addTranslation(NodeInterface $node, NodeTranslationInterface $translation);

	/**
	 * @param NodeInterface $node
	 * @param NodeTranslationInterface $translation
	 * @return $this
	 * @api
	 */
	public function removeTranslastion(NodeInterface $node, NodeTranslationInterface $translation);

	/**
	 * @param NodeInterface $node
	 * @param NodeInterface $parent
	 * @return $this
	 * @api
	 */
	public function addFirstChild(NodeInterface $node, NodeInterface $parent);

	/**
	 * @param NodeInterface $node
	 * @return Collection
	 * @api
	 */
	public function getChildren(NodeInterface $node);

	/**
	 * Returns the first found page node in root line.
	 *
	 * @param NodeInterface $node
	 * @return Collection
	 * @api
	 */
	public function getPages(NodeInterface $node);

	/**
	 * @param NodeInterface $node
	 * @param NodeInterface $parent
	 * @return $this
	 * @api
	 */
	public function addChild(NodeInterface $node, NodeInterface $parent);

	/**
	 * Returns the first found page node in root line.
	 *
	 * @param NodeInterface $node
	 * @return Collection
	 * @api
	 */
	public function getContent(NodeInterface $node);

	/**
	 * @param NodeInterface $node
	 * @param NodeInterface $before
	 * @return $this
	 * @api
	 */
	public function addBefore(NodeInterface $node, NodeInterface $before);

	/**
	 * @param NodeInterface $node
	 * @return NodeInterface
	 * @api
	 */
	public function getParent(NodeInterface $node);

	/**
	 * @param NodeInterface $node
	 * @return Collection
	 * @api
	 */
	public function findNodesOnSameLevel(NodeInterface $node);

	/**
	 * @param NodeInterface $node
	 * @return NodeInterface
	 * @api
	 */
	public function locateRoot(NodeInterface $node);

	/**
	 * @param mixed $id
	 * @return NodeInterface
	 * @api
	 */
	public function get($id);

	/**
	 * @param NodeInterface $node
	 * @param Criteria $criteria
	 * @return Collection
	 * @api
	 */
	public function getChildrenByCriteria(NodeInterface $node, Criteria $criteria);

	/**
	 * @param NodeInterface $node
	 * @param NodeInterface $parent
	 * @return $this
	 * @api
	 */
	public function addLastChild(NodeInterface $node, NodeInterface $parent);

	/**
	 * @param NodeInterface $node
	 * @return $this
	 * @api
	 */
	public function add(NodeInterface $node);

	/**
	 * @param NodeInterface $node
	 * @return $this
	 * @api
	 */
	public function addFirst(NodeInterface $node);

	/**
	 * Returns the first found page node in root line.
	 *
	 * @param NodeInterface $node
	 * @return PageNodeInterface
	 * @api
	 */
	public function locatePage(NodeInterface $node);

	/**
	 * Returns the first found site node in root line.
	 *
	 * @param NodeInterface $node
	 * @return SiteNodeInterface
	 * @api
	 */
	public function locateSite(NodeInterface $node);

	/**
	 * @param NodeInterface $node
	 * @return $this
	 * @api
	 */
	public function addLast(NodeInterface $node);

	/**
	 * @param NodeInterface $node
	 * @return $this
	 * @api
	 */
	public function remove(NodeInterface $node);

	/**
	 * @param NodeInterface $node
	 * @return $this
	 * @api
	 */
	public function update(NodeInterface $node);
}