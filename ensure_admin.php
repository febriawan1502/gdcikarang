<?php
use App\Models\User;
$u = User::where('role', 'admin')->first();
if (!$u) {
    $u = User::create([
        'name' => 'Administrator', 
        'email' => 'admin@pojok.com', 
        'password' => bcrypt('password'), 
        'role' => 'admin'
    ]);
    echo "Created Admin User: " . $u->email . "\n";
} else {
    echo "Existing Admin User: " . $u->email . "\n";
}
