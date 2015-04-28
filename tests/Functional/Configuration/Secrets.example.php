<?php

use Bleicker\Registry\Registry;

Registry::set('DbConnection', ['driver' => 'pdo_sqlite', 'path' => 'db.sqlite']);