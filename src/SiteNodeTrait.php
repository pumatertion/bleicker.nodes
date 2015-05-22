<?php

namespace Bleicker\Nodes;

/**
 * Class SiteNodeTrait
 *
 * @package Bleicker\Nodes
 */
trait SiteNodeTrait {

	/**
	 * @param string $title
	 * @return $this
	 */
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $domain
	 * @return $this
	 */
	public function setDomain($domain) {
		$this->domain = $domain;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDomain() {
		return $this->domain;
	}

	/**
	 * @return boolean
	 */
	public function getHiddenInMenu() {
		return $this->hiddenInMenu;
	}

	/**
	 * @param boolean $hiddenInMenu
	 * @return $this
	 */
	public function setHiddenInMenu($hiddenInMenu) {
		$this->hiddenInMenu = $hiddenInMenu;
		return $this;
	}
}
