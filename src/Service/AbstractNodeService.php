<?php

namespace Bleicker\Nodes\Service;

use Bleicker\Nodes\NodeInterface;
use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManagerInterface;

/**
 * Class AbstractNodeService
 *
 * @package Bleicker\Nodes\Service
 */
abstract class AbstractNodeService {

	/**
	 * @var EntityManagerInterface
	 */
	protected $entityManager;

	public function __construct() {
		$this->entityManager = ObjectManager::get(EntityManagerInterface::class);
	}

	/**
	 * @param NodeInterface $node
	 * @return $this
	 */
	public function addNode(NodeInterface $node) {
		$this->entityManager->persist($node);
		return $this;
	}

	/**
	 * @param mixed $id
	 * @return NodeInterface|NULL
	 */
	public function getNode($id) {
		return $this->entityManager->find($id, static::getType());
	}

	/**
	 * @return string
	 */
	abstract public function getType();
}
