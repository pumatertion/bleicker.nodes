<?php
namespace Tests\Bleicker\Nodes;

/**
 * Class BaseTestCase
 *
 * @package Tests\Bleicker\Nodes
 */
abstract class BaseTestCase extends \PHPUnit_Framework_TestCase {

	protected function setUp() {
		putenv('CONTEXT=testing');
	}
}
