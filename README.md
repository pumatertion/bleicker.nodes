### Usage ###

#### Introduce your Node implementation ####

MyNode.yml:

	MyNode:
      type: entity

MyNode.php:

	class myNode extends AbstractContentNode {

	}

#### Bootstap an usage of Service ####

ExampleApp.php

	use Bleicker\ObjectManager\ObjectManager;
	use Bleicker\Persistence\EntityManager;
	use Bleicker\Persistence\EntityManagerInterface;
	use Bleicker\Registry\Registry;
	use Doctrine\ORM\Tools\Setup;

	// Register schemas of this node package
	Registry::set('doctrine.schema.paths.nodes', __DIR__ . "/vendor/bleicker/nodes/src/Schema/Persistence");

	// Register schemas of you app
	Registry::set('doctrine.schema.paths.nodes-functional', __DIR__ . "/Schema/Persistence");

	// Register DB Connection
	Registry::set('DbConnection', ['url' => 'mysql://john:doe@localhost/yourdb']);

	// Register the PersistenceManagerInterface
	ObjectManager::register(EntityManagerInterface::class, function () {
		return EntityManager::create(
			Registry::get('DbConnection'),
			Setup::createYAMLMetadataConfiguration(Registry::get('doctrine.schema.paths'))
		);
	});

	/** @var EntityManagerInterface $entityManager */
	$entityManager = ObjectManager::get(EntityManagerInterface::class);

	$node = new Page();
	$node1 = new Content();
	$node2 = new Content();
	$node3 = new Content();
	
	$node->addChild($node1)->addChild($node2)->addChildAfter($node3, $node1);

	$entityManager->persist($node);
	$entityManager->flush();
