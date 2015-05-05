<?php

namespace Unit\Nodes;

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
use Tests\Bleicker\Nodes\Unit\Fixtures\Content;
use Tests\Bleicker\Nodes\Unit\Fixtures\Page;
use Tests\Bleicker\Nodes\Unit\Fixtures\Site;
use Tests\Bleicker\Nodes\UnitTestCase;

/**
 * Class NodeTypesTest
 *
 * @package Unit\Nodes
 */
class NodeTypesTest extends UnitTestCase {

	protected function setUp() {
		parent::setUp();
		ObjectManager::register(NodeTypeConfigurationsInterface::class, NodeTypeConfigurations::class);
		NodeTypeConfigurations::prune();
	}

	protected function tearDown() {
		parent::tearDown();
		ObjectManager::prune();
		NodeTypeConfigurations::prune();
	}

	/**
	 * @test
	 * @expectedException \Bleicker\Nodes\Configuration\Exception\AlreadyRegisteredException
	 */
	public function doubletteAliasTest() {
		NodeConfiguration::register(Content::class, 'content', 'Example Content', 'Description', NodeConfiguration::CONTENT_GROUP);
		NodeConfiguration::register(Content::class, 'content', 'Example Content', 'Description', NodeConfiguration::CONTENT_GROUP);
	}

	/**
	 * @test
	 */
	public function addTest() {
		NodeConfiguration::register(Site::class, 'site', 'Site', 'Description', NodeConfiguration::SITE_GROUP, Page::class, Content::class);
		$configuration = NodeTypeConfigurations::get('site');
		$this->assertInstanceOf(NodeConfiguration::class, $configuration);
		$this->assertEquals(Site::class, $configuration->getClassName());
		$this->assertEquals('Site', $configuration->getLabel());
		$this->assertEquals('Description', $configuration->getDescription());
		$this->assertEquals(NodeConfiguration::SITE_GROUP, $configuration->getGroup());
		$this->assertEquals(Page::class, $configuration->getAllowedChildren()->first());
		$this->assertEquals(Content::class, $configuration->getAllowedChildren()->next());
		$this->assertEquals(2, $configuration->getAllowedChildren()->count());
	}

	/**
	 * @test
	 */
	public function interfaceChildTypeNotAllowedTest() {
		NodeConfiguration::register(Site::class, 'site', 'Site', 'Description', NodeConfiguration::SITE_GROUP);
		$this->assertFalse(NodeTypeConfigurations::get('site')->allowsChild(NodeConfigurationInterface::class));
	}

	/**
	 * @test
	 */
	public function classChildTypeNotAllowedTest() {
		NodeConfiguration::register(Site::class, 'site', 'Site', 'Description', NodeConfiguration::SITE_GROUP);
		$this->assertFalse(NodeTypeConfigurations::get('site')->allowsChild(Page::class));
	}

	/**
	 * @test
	 */
	public function classChildTypeDenyAbstractTest() {
		NodeConfiguration::register(Site::class, 'site', 'Site', 'Description', NodeConfiguration::SITE_GROUP, AbstractContentNode::class);
		$this->assertFalse(NodeTypeConfigurations::get('site')->allowsChild(Site::class));
		$this->assertFalse(NodeTypeConfigurations::get('site')->allowsChild(Page::class));
		$this->assertFalse(NodeTypeConfigurations::get('site')->allowsChild(Page::class));
		$this->assertTrue(NodeTypeConfigurations::get('site')->allowsChild(Content::class));
	}

	/**
	 * @test
	 */
	public function classChildTypeDenyInterfaceTest() {
		NodeConfiguration::register(Site::class, 'site', 'Site', 'Description', NodeConfiguration::SITE_GROUP, ContentNodeInterface::class);
		$this->assertFalse(NodeTypeConfigurations::get('site')->allowsChild(Site::class));
		$this->assertFalse(NodeTypeConfigurations::get('site')->allowsChild(Page::class));
		$this->assertFalse(NodeTypeConfigurations::get('site')->allowsChild(Page::class));
		$this->assertTrue(NodeTypeConfigurations::get('site')->allowsChild(Content::class));
	}

	/**
	 * @test
	 */
	public function classChildTypeDenyAbstractNodeTest() {
		NodeConfiguration::register(Site::class, 'site', 'Site', 'Description', NodeConfiguration::SITE_GROUP, AbstractNode::class);
		$this->assertTrue(NodeTypeConfigurations::get('site')->allowsChild(Site::class));
		$this->assertTrue(NodeTypeConfigurations::get('site')->allowsChild(Page::class));
		$this->assertTrue(NodeTypeConfigurations::get('site')->allowsChild(Page::class));
		$this->assertTrue(NodeTypeConfigurations::get('site')->allowsChild(Content::class));
	}

	/**
	 * @test
	 */
	public function classChildTypeDenyNodeInterfaceTest() {
		NodeConfiguration::register(Site::class, 'site', 'Site', 'Description', NodeConfiguration::SITE_GROUP, NodeInterface::class);
		$this->assertTrue(NodeTypeConfigurations::get('site')->allowsChild(Site::class));
		$this->assertTrue(NodeTypeConfigurations::get('site')->allowsChild(Page::class));
		$this->assertTrue(NodeTypeConfigurations::get('site')->allowsChild(Page::class));
		$this->assertTrue(NodeTypeConfigurations::get('site')->allowsChild(Content::class));
	}

	/**
	 * @test
	 */
	public function classChildTypeDenyConcreteTest() {
		NodeConfiguration::register(Site::class, 'site', 'Site', 'Description', NodeConfiguration::SITE_GROUP, Page::class, Content::class, AbstractContentNode::class, AbstractPageNode::class);
		$this->assertFalse(NodeTypeConfigurations::get('site')->allowsChild(Site::class));
		$this->assertTrue(NodeTypeConfigurations::get('site')->allowsChild(Page::class));
		$this->assertTrue(NodeTypeConfigurations::get('site')->allowsChild(Page::class));
		$this->assertTrue(NodeTypeConfigurations::get('site')->allowsChild(Content::class));
	}
}
