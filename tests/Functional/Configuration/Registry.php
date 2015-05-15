<?php

use Bleicker\Registry\Registry;

Registry::set('doctrine.db.testing.driver', 'pdo_sqlite');
Registry::set('doctrine.db.testing.path', __DIR__ . '/../testing.sqlite');

if (file_exists(__DIR__ . '/Registry.local.php')) {
	include __DIR__ . '/Registry.local.php';
}
