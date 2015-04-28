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

	$firstNode = new MyNode();
	$childNode = new MyNode();
	$anotherNode = new MyNode();

	$service = new ContentNodeService();

	// Inser $childNode into $firstNode
	$service->into($childNode, $firstNode);

	// Add $anotherNode
	$service->add($anotherNode);

	// Or move it before $childNode
	$service->before($anotherNode, $childNode);

	// Persist the changes
	$service->flush();

	// Doing it all at once
	$firstNode = new MyNode();
	$childNode = new MyNode();
	$anotherNode = new MyNode();

	$service->into($childNode, $firstNode)->before($anotherNode, $childNode)->flush();
