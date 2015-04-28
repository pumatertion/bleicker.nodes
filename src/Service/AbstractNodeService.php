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
	 * @return $this
	 */
	public function flush() {
		$this->entityManager->flush();
		return $this;
	}

	/**
	 * @param NodeInterface $node
	 * @return $this
	 */
	public function clear(NodeInterface $node = NULL) {
		$this->entityManager->clear($node);
		return $this;
	}

	/**
	 * @param NodeInterface $node
	 * @return $this
	 */
	public function add(NodeInterface $node) {
		$this->entityManager->persist($node);
		return $this;
	}

	/**
	 * @param NodeInterface $node
	 * @return $this
	 */
	public function remove(NodeInterface $node) {
		$this->entityManager->remove($node);
		return $this;
	}

	/**
	 * @param mixed $id
	 * @return NodeInterface|NULL
	 */
	public function get($id) {
		return $this->entityManager->find(static::getType(), $id);
	}

	/**
	 * @param mixed $id
	 * @return boolean
	 */
	public function has($id) {
		return $this->get($id) === NULL ? FALSE : TRUE;
	}

	/**
	 * @return string
	 */
	abstract public function getType();
}
