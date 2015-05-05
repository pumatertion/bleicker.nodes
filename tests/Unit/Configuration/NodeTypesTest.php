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
		NodeConfiguration::instance(Content::class, 'Example Content', 'Description', NodeConfiguration::CONTENT_GROUP)->register('content');
		NodeConfiguration::instance(Content::class, 'Example Content', 'Description', NodeConfiguration::CONTENT_GROUP)->register('content');
	}

	/**
	 * @test
	 */
	public function addTest() {
		NodeConfiguration::instance(Site::class, 'Site', 'Description', NodeConfiguration::SITE_GROUP, Page::class, Content::class)->register('site');
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
	 * @expectedException \Bleicker\Nodes\Configuration\Exception\NotRegisteredException
	 */
	public function notRegisteredChildTypeTest() {
		NodeTypeConfigurations::allowsChild('grid', Page::class);
	}

	/**
	 * @test
	 */
	public function interfaceChildTypeNotAllowedTest() {
		NodeConfiguration::instance(Site::class, 'Site', 'Description', NodeConfiguration::SITE_GROUP)->register('site');
		$this->assertFalse(NodeTypeConfigurations::allowsChild('site', NodeConfigurationInterface::class));
	}

	/**
	 * @test
	 */
	public function classChildTypeNotAllowedTest() {
		NodeConfiguration::instance(Site::class, 'Site', 'Description', NodeConfiguration::SITE_GROUP)->register('site');
		$this->assertFalse(NodeTypeConfigurations::allowsChild('site', Page::class));
	}

	/**
	 * @test
	 */
	public function classChildTypeDenyAbstractTest() {
		NodeConfiguration::instance(Site::class, 'Site', 'Description', NodeConfiguration::SITE_GROUP, AbstractContentNode::class)->register('site');
		$this->assertFalse(NodeTypeConfigurations::allowsChild('site', Site::class));
		$this->assertFalse(NodeTypeConfigurations::allowsChild('site', Page::class));
		$this->assertFalse(NodeTypeConfigurations::allowsChild('site', Page::class));
		$this->assertTrue(NodeTypeConfigurations::allowsChild('site', Content::class));
	}

	/**
	 * @test
	 */
	public function classChildTypeDenyInterfaceTest() {
		NodeConfiguration::instance(Site::class, 'Site', 'Description', NodeConfiguration::SITE_GROUP, ContentNodeInterface::class)->register('site');
		$this->assertFalse(NodeTypeConfigurations::allowsChild('site', Site::class));
		$this->assertFalse(NodeTypeConfigurations::allowsChild('site', Page::class));
		$this->assertFalse(NodeTypeConfigurations::allowsChild('site', Page::class));
		$this->assertTrue(NodeTypeConfigurations::allowsChild('site', Content::class));
	}

	/**
	 * @test
	 */
	public function classChildTypeDenyAbstractNodeTest() {
		NodeConfiguration::instance(Site::class, 'Site', 'Description', NodeConfiguration::SITE_GROUP, AbstractNode::class)->register('site');
		$this->assertTrue(NodeTypeConfigurations::allowsChild('site', Site::class));
		$this->assertTrue(NodeTypeConfigurations::allowsChild('site', Page::class));
		$this->assertTrue(NodeTypeConfigurations::allowsChild('site', Page::class));
		$this->assertTrue(NodeTypeConfigurations::allowsChild('site', Content::class));
	}

	/**
	 * @test
	 */
	public function classChildTypeDenyNodeInterfaceTest() {
		NodeConfiguration::instance(Site::class, 'Site', 'Description', NodeConfiguration::SITE_GROUP, NodeInterface::class)->register('site');
		$this->assertTrue(NodeTypeConfigurations::allowsChild('site', Site::class));
		$this->assertTrue(NodeTypeConfigurations::allowsChild('site', Page::class));
		$this->assertTrue(NodeTypeConfigurations::allowsChild('site', Page::class));
		$this->assertTrue(NodeTypeConfigurations::allowsChild('site', Content::class));
	}

	/**
	 * @test
	 */
	public function classChildTypeDenyConcreteTest() {
		NodeConfiguration::instance(Site::class, 'Site', 'Description', NodeConfiguration::SITE_GROUP, Page::class, Content::class, AbstractContentNode::class, AbstractPageNode::class)->register('site');
		$this->assertFalse(NodeTypeConfigurations::allowsChild('site', Site::class));
		$this->assertTrue(NodeTypeConfigurations::allowsChild('site', Page::class));
		$this->assertTrue(NodeTypeConfigurations::allowsChild('site', Page::class));
		$this->assertTrue(NodeTypeConfigurations::allowsChild('site', Content::class));
	}
}
