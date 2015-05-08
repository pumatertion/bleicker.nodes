<?php

namespace Bleicker\Nodes;

use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

/**
 * Class NodeService
 *
 * @package Bleicker\Nodes
 */
class NodeService implements NodeServiceInterface {

	/**
	 * @var EntityManagerInterface
	 */
	protected $entityManager;

	public function __construct() {
		$this->entityManager = ObjectManager::get(EntityManagerInterface::class);
	}

	/**
	 * @return Collection
	 */
	public function findSites() {
		$expr = Criteria::expr();
		$criteria = Criteria::create();
		$criteria->andWhere(
			$expr->andX(
				$expr->eq('nodeTypeAbstraction', AbstractSiteNode::class),
				$expr->isNull('parent')
			)
		)->orderBy(['sorting' => Criteria::ASC]);
		return $this->entityManager->getRepository(AbstractNode::class)->matching($criteria);
	}

	/**
	 * @param NodeInterface $node
	 * @param NodeTranslationInterface $translation
	 * @return $this
	 * @api
	 */
	public function addTranslation(NodeInterface $node, NodeTranslationInterface $translation) {
		$node->removeTranslation($translation)->addTranslation($translation);
		$this->persist($node);
		return $this;
	}

	/**
	 * @param NodeInterface $node
	 * @param NodeTranslationInterface $translation
	 * @return $this
	 * @api
	 */
	public function removeTranslastion(NodeInterface $node, NodeTranslationInterface $translation) {
		$node->removeTranslation($translation);
		$this->entityManager->remove($translation);
		$this->entityManager->flush();
		return $this;
	}

	/**
	 * @param NodeInterface $node
	 * @return $this
	 * @api
	 */
	public function add(NodeInterface $node) {
		$this->addLast($node);
		return $this;
	}

	/**
	 * @param NodeInterface $node
	 * @return $this
	 * @api
	 */
	public function remove(NodeInterface $node) {
		$this->entityManager->remove($node);
		$this->entityManager->flush();
	}

	/**
	 * @param NodeInterface $node
	 * @return $this
	 * @api
	 */
	public function update(NodeInterface $node) {
		$this->persist($node);
	}

	/**
	 * @param NodeInterface $node
	 * @param NodeInterface $parent
	 * @return $this
	 * @api
	 */
	public function addFirstChild(NodeInterface $node, NodeInterface $parent) {
		$node->setParent($parent);
		$this->persist($node, $parent);
		$this->addFirst($node);
		return $this;
	}

	/**
	 * @param NodeInterface $node
	 * @param NodeInterface $parent
	 * @return $this
	 * @api
	 */
	public function addLastChild(NodeInterface $node, NodeInterface $parent) {
		$node->setParent($parent);
		$this->persist($node, $parent);
		$this->addLast($node);
		return $this;
	}

	/**
	 * @param NodeInterface $node
	 * @param NodeInterface $parent
	 * @return $this
	 * @api
	 */
	public function addChild(NodeInterface $node, NodeInterface $parent) {
		$this->addLastChild($node, $parent);
		return $this;
	}

	/**
	 * @param NodeInterface $node
	 * @return $this
	 * @api
	 */
	public function addLast(NodeInterface $node) {
		/** @var NodeInterface $lastLevelNode */
		$lastLevelNode = $this->findNodesOnSameLevel($node)->last();
		if ($lastLevelNode) {
			$this->addAfter($node, $lastLevelNode);
			return $this;
		}
		$this->persist($node);
		return $this;
	}

	/**
	 * @param NodeInterface $node
	 * @return $this
	 * @api
	 */
	public function addFirst(NodeInterface $node) {
		/** @var NodeInterface $lastLevelNode */
		$firstLevelNode = $this->findNodesOnSameLevel($node)->first();
		if ($firstLevelNode) {
			$this->addBefore($node, $firstLevelNode);
			return $this;
		}
		$this->persist($node);
		return $this;
	}

	/**
	 * @param mixed $id
	 * @return NodeInterface
	 * @api
	 */
	public function get($id) {
		return $this->entityManager->find(AbstractNode::class, $id);
	}

	/**
	 * Returns the first found page node in root line.
	 *
	 * @param NodeInterface $node
	 * @return PageNodeInterface
	 * @api
	 */
	public function locatePage(NodeInterface $node) {
		if ($node instanceof PageNodeInterface) {
			return $node;
		}

		$parentNode = $node->getParent();

		if ($parentNode === NULL) {
			return NULL;
		}

		if ($parentNode instanceof PageNodeInterface) {
			return $parentNode;
		}

		return $this->locatePage($parentNode);
	}

	/**
	 * Returns the first found site node in root line.
	 *
	 * @param NodeInterface $node
	 * @return SiteNodeInterface
	 * @api
	 */
	public function locateSite(NodeInterface $node) {
		if ($node instanceof SiteNodeInterface) {
			return $node;
		}

		$parentNode = $node->getParent();

		if ($parentNode === NULL) {
			return NULL;
		}

		if ($parentNode instanceof SiteNodeInterface) {
			return $parentNode;
		}

		return $this->locateSite($parentNode);
	}

	/**
	 * Returns the first found page node in root line.
	 *
	 * @param NodeInterface $node
	 * @return Collection
	 * @api
	 */
	public function getContent(NodeInterface $node) {
		$criteria = Criteria::create()->where(Criteria::expr()->eq('nodeTypeAbstraction', AbstractContentNode::class))->orderBy(['sorting' => Criteria::ASC]);
		return $this->getChildrenByCriteria($node, $criteria);
	}

	/**
	 * Returns the first found page node in root line.
	 *
	 * @param NodeInterface $node
	 * @return Collection
	 * @api
	 */
	public function getPages(NodeInterface $node) {
		$criteria = Criteria::create()->where(Criteria::expr()->eq('nodeTypeAbstraction', AbstractPageNode::class))->orderBy(['sorting' => Criteria::ASC]);
		return $this->getChildrenByCriteria($node, $criteria);
	}

	/**
	 * @param NodeInterface $node
	 * @param Criteria $criteria
	 * @return Collection
	 * @api
	 */
	public function getChildrenByCriteria(NodeInterface $node, Criteria $criteria) {
		return $node->getChildren()->matching($criteria);
	}

	/**
	 * @param NodeInterface $node
	 * @return Collection
	 * @api
	 */
	public function getChildren(NodeInterface $node) {
		return $node->getChildren();
	}

	/**
	 * @param NodeInterface $node
	 * @return NodeInterface
	 * @api
	 */
	public function locateRoot(NodeInterface $node) {
		$parent = $this->getParent($node);
		if ($parent instanceof NodeInterface) {
			return $this->locateRoot($parent);
		}
		return $node;
	}

	/**
	 * @param NodeInterface $node
	 * @return NodeInterface
	 * @api
	 */
	public function getParent(NodeInterface $node) {
		return $node->getParent();
	}

	/**
	 * @param NodeInterface $node
	 * @param NodeInterface $after
	 * @return $this
	 * @api
	 */
	public function addAfter(NodeInterface $node, NodeInterface $after) {
		$node->setParent($after->getParent());
		$node->setSorting($after->getSorting() + 1);
		$this->persist($node, $after);
		$this->calculateSorting($node);
		return $this;
	}

	/**
	 * @param NodeInterface $node
	 * @param NodeInterface $before
	 * @return $this
	 * @api
	 */
	public function addBefore(NodeInterface $node, NodeInterface $before) {
		$node->setParent($before->getParent());
		$node->setSorting($before->getSorting() - 1);
		$this->persist($node, $before);
		$this->calculateSorting($node);
		return $this;
	}

	/**
	 * @param NodeInterface $node
	 * @return Collection
	 * @api
	 */
	public function findNodesOnSameLevel(NodeInterface $node) {
		$criteria = Criteria::create()->where(Criteria::expr()->eq('parent', $node->getParent()))->orderBy(['sorting' => Criteria::ASC]);
		$levelNodes = $this->entityManager->getRepository(AbstractNode::class)->matching($criteria);
		return $this->getSortedCollection($levelNodes);
	}

	/**
	 * @param NodeInterface $node
	 * @return $this
	 */
	protected function persist(NodeInterface $node) {
		$entities = func_get_args();
		foreach ($entities as $entity) {
			$this->entityManager->persist($entity);
		}
		$this->entityManager->flush();
	}

	/**
	 * @param NodeInterface $node
	 * @return $this
	 */
	protected function calculateSorting(NodeInterface $node) {
		$this->generateSorting($this->getSortedCollection($this->findNodesOnSameLevel($node)));
		return $this;
	}

	/**
	 * @param Collection $collection
	 * @return Collection
	 */
	protected function generateSorting(Collection $collection) {
		$multiplier = 1;
		/** @var NodeInterface $item */
		foreach ($collection as $item) {
			$item->setSorting($multiplier * NodeInterface::SORTING_DIFF);
			$this->persist($item);
			$multiplier++;
		}
		return $collection;
	}

	/**
	 * @param Collection $collection
	 * @return Collection
	 */
	protected function getSortedCollection(Collection $collection) {
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
		return $this->generateSorting($collection);
	}
}
