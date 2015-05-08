<?php

namespace Tests\Bleicker\Nodes\Functional;

use Tests\Bleicker\Nodes\Functional\Fixtures\Content;
use Tests\Bleicker\Nodes\FunctionalTestCase;

/**
 * Class TranslationTest
 *
 * @package Tests\Bleicker\Nodes\Functional
 */
class TranslationTest extends FunctionalTestCase {

	/**
	 * @test
	 */
	public function createTranslation(){
		$node = new Content();
	}

}
