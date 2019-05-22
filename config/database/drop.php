<?php

use Illuminate\Database\Capsule\Manager;

$tables = [
    'activations',
    'persistences',
    'reminders',
    'role_users',
    'throttle',
    'roles',
    'user',
    'contents',
    'events',
    'timelines'
];

Manager::schema()->disableForeignKeyConstraints();
foreach ($tables as $table) {
    Manager::schema()->dropIfExists($table);
}
