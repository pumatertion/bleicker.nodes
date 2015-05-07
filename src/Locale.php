<?php

namespace Bleicker\Nodes;

use Bleicker\Translation\Locale as LocaleOrigin;

/**
 * Class Locale
 *
 * @package Bleicker\Nodes
 */
class Locale extends LocaleOrigin {

	/**
	 * @var integer
	 */
	protected $id;

	/**
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}
}
