<?php
// Seeds users collection
// admin : n0t_4_u_:) : role=admin
// jsmith : password123 : role=user
// bthomas : letmein99 : role=user
// Drop and recreate collection on each run
// NOTE: remove this file from the server after initial seeding (production)

require __DIR__ . '/config.php';

$db->users->drop();

$users = $db->users;

$users->insertMany([
    [
        'username' => 'admin',
        'password' => 'n0t_4_u_:)',
        'role' => 'admin',
        'display_name' => 'Admin',
        'email' => 'admin@phantom.local',
        'created_at' => '2023-02-11',
        'last_login' => '2026-08-07 09:14:22',
        'status' => 'active',
    ],
    [
        'username' => 'jsmith',
        'password' => 'password123',
        'role' => 'user',
        'display_name' => 'Jessica Smith',
        'email' => 'j.smith@phantom.local',
        'created_at' => '2024-06-03',
        'last_login' => '2026-08-07 13:41:05',
        'status' => 'active',
    ],
    [
        'username' => 'bthomas',
        'password' => 'letmein99',
        'role' => 'user',
        'display_name' => 'Brian Thomas',
        'email' => 'b.thomas@phantom.local',
        'created_at' => '2025-01-20',
        'last_login' => '2026-08-06 17:52:40',
        'status' => 'disabled',
    ],
]);

$count = $users->countDocuments();

echo "<pre>";
echo "Seed complete.\n";
echo "Users in collection: {$count}\n";
echo "admin / jsmith / bthomas\n";
echo "</pre>";
