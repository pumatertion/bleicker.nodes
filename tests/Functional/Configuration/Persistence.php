<?php

use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Persistence\EntityManager;
use Bleicker\Persistence\EntityManagerInterface;
use Bleicker\Registry\Registry;
use Doctrine\ORM\Tools\Setup;

Registry::set('doctrine.schema.paths.nodes', __DIR__ . "/../../../src/Schema/Persistence");
Registry::set('doctrine.schema.paths.nodes-functional', __DIR__ . "/../Schema/Persistence");

ObjectManager::get(EntityManagerInterface::class, function () {
	$configuration = Setup::createYAMLMetadataConfiguration(Registry::get('doctrine.schema.paths'), TRUE);
	return EntityManager::create(
		Registry::get('doctrine.db.testing'),
		$configuration
	);
});
