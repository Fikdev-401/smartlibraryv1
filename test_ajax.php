<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$env = file_get_contents(__DIR__ . '/.env');
preg_match('/DB_DATABASE=(.+)/', $env, $m); $db = trim($m[1] ?? '', " \t\"'");
preg_match('/DB_USERNAME=(.+)/', $env, $m); $user = trim($m[1] ?? '', " \t\"'");
preg_match('/DB_PASSWORD=(.+)/', $env, $m); $pass = trim($m[1] ?? '', " \t\"'");

echo "DB = $db, USER = $user\n";

$pdo = new PDO("mysql:host=127.0.0.1;dbname=$db;charset=utf8mb4", $user, $pass);
$row = $pdo->query("SELECT id_user FROM ebook LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$testId = $row['id_user'] ?? null;
echo "Test id_user = " . var_export($testId, true) . "\n";

$request = Illuminate\Http\Request::create("/gen/user/upload/details/ajax/$testId", 'GET', [
    'draw' => 1, 'start' => 0, 'length' => 10,
    'search' => ['value' => ''],
    'order'  => [['column' => 4, 'dir' => 'desc']],
    'columns' => [
        ['data'=>'DT_Row_Index','searchable'=>false,'orderable'=>false],
        ['data'=>'judul','searchable'=>true,'orderable'=>true],
        ['data'=>'penulis','searchable'=>true,'orderable'=>true],
        ['data'=>'tahun','searchable'=>true,'orderable'=>true],
        ['data'=>'created_at','searchable'=>true,'orderable'=>true],
        ['data'=>'action','searchable'=>false,'orderable'=>false],
    ],
]);

try {
    // Login as an admin user to bypass auth middleware
    $admin = App\User::where('level','ADMIN')->orWhere('level','SUPERUSER')->first();
    if ($admin) {
        Illuminate\Support\Facades\Auth::loginUsingId($admin->id);
        echo "Logged in as: " . $admin->id . " (level: " . $admin->level . ")\n";
    } else {
        echo "WARNING: no admin user found\n";
    }

    $response = $kernel->handle($request);
    echo "Status: " . $response->getStatusCode() . "\n";
    echo "Content-Type: " . $response->headers->get('Content-Type') . "\n";
    echo "Body length: " . strlen($response->getContent()) . "\n";
    echo "Body (first 800 chars):\n" . substr($response->getContent(), 0, 800) . "\n";
} catch (Throwable $e) {
    echo "EXCEPTION: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace top: " . substr($e->getTraceAsString(), 0, 1500) . "\n";
}
