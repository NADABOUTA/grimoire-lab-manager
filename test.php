<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
if (!$user) {
    echo "No user found.\n";
    exit;
}

$p = \App\Models\Project::create([
    'title'=>'Test Post',
    'description'=>'desc',
    'status'=>'en_cours',
    'avancement'=>0
]);
$p->users()->attach($user->id, ['role'=>'responsable']);

auth()->login($user);

try {
    app(\Illuminate\Contracts\Auth\Access\Gate::class)->authorize('addMembre', $p);
    echo "Gate allowed!\n";
} catch (\Illuminate\Auth\Access\AuthorizationException $e) {
    echo "Gate denied: " . $e->getMessage() . "\n";
}
