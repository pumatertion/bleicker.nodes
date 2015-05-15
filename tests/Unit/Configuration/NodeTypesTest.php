<?php

namespace Tests\Bleicker\Nodes\Unit\Configuration;

use Bleicker\Nodes\AbstractContentNode;
use Bleicker\Nodes\AbstractNode;
use Bleicker\Nodes\AbstractPageNode;
use Bleicker\Nodes\Configuration\NodeConfiguration;
use Bleicker\Nodes\Configuration\NodeConfigurationInterface;
use Bleicker\Nodes\Configuration\NodeTypeConfigurations;
use Bleicker\Nodes\Configuration\NodeTypeConfigurationsInterface;
use Bleicker\Nodes\ContentNodeInterface;
use Bleicker\Nodes\NodeInterface;
use Bleicker\ObjectManager\ObjectManager;
use Tests\Bleicker\Nodes\Unit\Fixtures\BlacklistedContent;
use Tests\Bleicker\Nodes\Unit\Fixtures\Content;
use Tests\Bleicker\Nodes\Unit\Fixtures\Page;
use Tests\Bleicker\Nodes\Unit\Fixtures\Site;
use Tests\Bleicker\Nodes\UnitTestCase;

/**
 * Class NodeTypesTest
 *
 * @package Tests\Bleicker\Nodes\Unit\Configuration
 */
class NodeTypesTest extends UnitTestCase {

	protected function setUp() {
		parent::setUp();
		ObjectManager::add(NodeTypeConfigurationsInterface::class, NodeTypeConfigurations::class);
	}

	protected function tearDown() {
		parent::tearDown();
		ObjectManager::prune();
		NodeTypeConfigurations::prune();
	}

	/**
	 * @test
	 * @expectedException \Bleicker\Container\Exception\AliasAlreadyExistsException
	 */
	public function doubletteAliasTest() {
		Content::register('Example Content', 'Description', NodeConfiguration::CONTENT_GROUP);
		Content::register('Example Content', 'Description', NodeConfiguration::CONTENT_GROUP);
	}

	/**
	 * @test
	 */
	public function addTest() {
		$configuration = Site::register('Site', 'Description', NodeConfiguration::SITE_GROUP);
		$this->assertInstanceOf(NodeConfiguration::class, $configuration);
		$this->assertEquals(Site::class, $configuration->getClassName());
		$this->assertEquals('Site', $configuration->getLabel());
		$this->assertEquals('Description', $configuration->getDescription());
		$this->assertEquals(NodeConfiguration::SITE_GROUP, $configuration->getGroup());
	}

	/**
	 * @test
	 */
	public function interfaceChildTypeNotAllowedTest() {
		$configuration = Site::register('Site', 'Description', NodeConfiguration::SITE_GROUP);
		$this->assertFalse($configuration->allowsChild(NodeConfigurationInterface::class));
	}

	/**
	 * @test
	 */
	public function classChildTypeNotAllowedTest() {
		$configuration = Site::register('Site', 'Description', NodeConfiguration::SITE_GROUP);
		$this->assertFalse($configuration->allowsChild(Page::class));
	}

	/**
	 * @test
	 */
	public function classChildTypeDenyAbstractTest() {
		$configuration = Site::register('Site', 'Description', NodeConfiguration::SITE_GROUP);
		$configuration->allowChild(AbstractContentNode::class);
		$this->assertFalse($configuration->allowsChild(Site::class));
		$this->assertFalse($configuration->allowsChild(Page::class));
		$this->assertTrue($configuration->allowsChild(Content::class));
	}

	/**
	 * @test
	 */
	public function classChildTypeDenyInterfaceTest() {
		$configuration = Site::register('Site', 'Description', NodeConfiguration::SITE_GROUP);
		$configuration->allowChild(ContentNodeInterface::class);
		$this->assertFalse($configuration->allowsChild(Site::class));
		$this->assertFalse($configuration->allowsChild(Page::class));
		$this->assertTrue($configuration->allowsChild(Content::class));
	}

	/**
	 * @test
	 */
	public function classChildTypeDenyAbstractNodeTest() {
		$configuration = Site::register('Site', 'Description', NodeConfiguration::SITE_GROUP);
		$configuration->allowChild(AbstractNode::class);
		$this->assertTrue($configuration->allowsChild(Site::class));
		$this->assertTrue($configuration->allowsChild(Page::class));
		$this->assertTrue($configuration->allowsChild(Content::class));
	}

	/**
	 * @test
	 */
	public function classChildTypeDenyNodeInterfaceTest() {
		$configuration = Site::register('Site', 'Description', NodeConfiguration::SITE_GROUP);
		$configuration->allowChild(NodeInterface::class);
		$this->assertTrue($configuration->allowsChild(Site::class));
		$this->assertTrue($configuration->allowsChild(Page::class));
		$this->assertTrue($configuration->allowsChild(Content::class));
	}

	/**
	 * @test
	 */
	public function classChildTypeDenyConcreteTest() {
		$configuration = Site::register('Site', 'Description', NodeConfiguration::SITE_GROUP);
		$configuration
			->allowChild(Page::class)
			->allowChild(Content::class)
			->allowChild(AbstractContentNode::class)
			->allowChild(AbstractPageNode::class);
		$this->assertFalse($configuration->allowsChild(Site::class));
		$this->assertTrue($configuration->allowsChild(Page::class));
		$this->assertTrue($configuration->allowsChild(Content::class));
	}

	/**
	 * @test
	 */
	public function classChildTypeDenyBlacklistTest() {
		$configuration = Site::register('Site', 'Description', NodeConfiguration::SITE_GROUP);
		$configuration
			->allowChild(AbstractContentNode::class)
			->allowChild(AbstractPageNode::class)
			->allowChild(BlacklistedContent::class)
			->allowChild(Page::class)
			->forbidChild(BlacklistedContent::class)
			->forbidChild(Page::class);
		$this->assertFalse($configuration->allowsChild(Site::class));
		$this->assertTrue($configuration->allowsChild(Content::class));
		$this->assertFalse($configuration->allowsChild(Page::class));
		$this->assertFalse($configuration->allowsChild(BlacklistedContent::class));
	}
}
