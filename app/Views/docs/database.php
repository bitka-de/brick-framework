@extends('docs')

@section('title', 'Database')

@section('content')
<h1>🗄️ Database</h1>

<div class="info-block">
    <h5>🚧 In Entwicklung</h5>
    <p>Das Database-System ist derzeit in Entwicklung. Derzeit unterstützt das Framework einfache Datenbankverbindungen mit PDO.</p>
</div>

<div class="table-of-contents">
    <h5>🗺️ Inhaltsübersicht</h5>
    <ul>
        <li><a href="#aktuelle-losung">Aktuelle Lösung</a></li>
        <li><a href="#pdo-setup">PDO-Setup</a></li>
        <li><a href="#grundlegende-queries">Grundlegende Queries</a></li>
        <li><a href="#geplante-features">Geplante Features</a></li>
        <li><a href="#migration-system">Migration-System</a></li>
        <li><a href="#query-builder">Query Builder</a></li>
    </ul>
</div>

<h2 id="aktuelle-losung">🔧 Aktuelle Lösung</h2>

<p class="lead">Derzeit unterstützt das Brick Framework einfache Datenbankverbindungen über PHP's PDO-Extension.</p>

<div class="example-block">
    <h6>✅ Was bereits funktioniert</h6>
    <ul>
        <li>PDO-basierte Datenbankverbindungen</li>
        <li>MySQL, PostgreSQL, SQLite Support</li>
        <li>Prepared Statements</li>
        <li>Transaktions-Management</li>
    </ul>
</div>

<h2 id="pdo-setup">🔌 PDO-Setup</h2>

<h3>Datenbankverbindung erstellen</h3>

<div class="code-block">
<pre><code class="language-php">&lt;?php

// Database Helper Class
class Database
{
    private static ?PDO $connection = null;
    
    public static function connect(): PDO
    {
        if (self::$connection === null) {
            // Konfiguration
            $config = [
                'host' =&gt; 'localhost',
                'dbname' =&gt; 'brick_app',
                'username' =&gt; 'root',
                'password' =&gt; 'password',
                'charset' =&gt; 'utf8mb4'
            ];
            
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
            
            self::$connection = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE =&gt; PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE =&gt; PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES =&gt; false
            ]);
        }
        
        return self::$connection;
    }
    
    public static function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::connect()-&gt;prepare($sql);
        $stmt-&gt;execute($params);
        return $stmt;
    }
}</code></pre>
</div>

<h3>Umgebungs-Konfiguration</h3>

<div class="code-block">
<pre><code class="language-php">&lt;?php

// config/database.php
return [
    'default' =&gt; 'mysql',
    
    'connections' =&gt; [
        'mysql' =&gt; [
            'driver' =&gt; 'mysql',
            'host' =&gt; $_ENV['DB_HOST'] ?? 'localhost',
            'port' =&gt; $_ENV['DB_PORT'] ?? 3306,
            'database' =&gt; $_ENV['DB_DATABASE'] ?? 'brick_app',
            'username' =&gt; $_ENV['DB_USERNAME'] ?? 'root',
            'password' =&gt; $_ENV['DB_PASSWORD'] ?? '',
            'charset' =&gt; 'utf8mb4',
            'collation' =&gt; 'utf8mb4_unicode_ci',
            'options' =&gt; [
                PDO::ATTR_ERRMODE =&gt; PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE =&gt; PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES =&gt; false
            ]
        ],
        
        'sqlite' =&gt; [
            'driver' =&gt; 'sqlite',
            'database' =&gt; __DIR__ . '/../storage/database.sqlite',
            'options' =&gt; [
                PDO::ATTR_ERRMODE =&gt; PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE =&gt; PDO::FETCH_ASSOC
            ]
        ]
    ]
];</code></pre>
</div>

<h2 id="grundlegende-queries">📊 Grundlegende Queries</h2>

<h3>Daten abfragen</h3>

<div class="code-block">
<pre><code class="language-php">&lt;?php

// Alle Benutzer laden
$users = Database::query('SELECT * FROM users')-&gt;fetchAll();

// Einzelnen Benutzer laden
$user = Database::query('SELECT * FROM users WHERE id = ?', [1])-&gt;fetch();

// Suche mit LIKE
$searchTerm = '%john%';
$users = Database::query(
    'SELECT * FROM users WHERE name LIKE ? ORDER BY name',
    [$searchTerm]
)-&gt;fetchAll();

// Mit JOIN
$postsWithAuthors = Database::query('
    SELECT 
        posts.*,
        users.name as author_name,
        users.email as author_email
    FROM posts 
    JOIN users ON posts.user_id = users.id 
    WHERE posts.published = 1
    ORDER BY posts.created_at DESC
')-&gt;fetchAll();</code></pre>
</div>

<h3>Daten einfügen</h3>

<div class="code-block">
<pre><code class="language-php">&lt;?php

// Neuen Benutzer erstellen
$stmt = Database::query('
    INSERT INTO users (name, email, password, created_at) 
    VALUES (?, ?, ?, NOW())
', [
    'Max Mustermann',
    'max@example.com',
    password_hash('secret', PASSWORD_DEFAULT)
]);

$userId = Database::connect()-&gt;lastInsertId();

// Post erstellen
Database::query('
    INSERT INTO posts (user_id, title, content, published, created_at)
    VALUES (?, ?, ?, ?, NOW())
', [
    $userId,
    'Mein erster Post',
    'Dies ist der Inhalt meines ersten Posts.',
    1
]);</code></pre>
</div>

<h3>Daten aktualisieren</h3>

<div class="code-block">
<pre><code class="language-php">&lt;?php

// Benutzer aktualisieren
Database::query('
    UPDATE users 
    SET name = ?, email = ?, updated_at = NOW() 
    WHERE id = ?
', [
    'Max Mustermann (updated)',
    'max.new@example.com',
    $userId
]);

// Post veröffentlichen
Database::query('
    UPDATE posts 
    SET published = 1, published_at = NOW() 
    WHERE id = ? AND user_id = ?
', [$postId, $userId]);</code></pre>
</div>

<h3>Transaktionen</h3>

<div class="code-block">
<pre><code class="language-php">&lt;?php

try {
    $pdo = Database::connect();
    $pdo-&gt;beginTransaction();
    
    // Benutzer erstellen
    $stmt = $pdo-&gt;prepare('
        INSERT INTO users (name, email, password) 
        VALUES (?, ?, ?)
    ');
    $stmt-&gt;execute(['John Doe', 'john@example.com', $hashedPassword]);
    $userId = $pdo-&gt;lastInsertId();
    
    // Profil erstellen
    $stmt = $pdo-&gt;prepare('
        INSERT INTO user_profiles (user_id, bio, avatar) 
        VALUES (?, ?, ?)
    ');
    $stmt-&gt;execute([$userId, 'Bio text', '/avatars/default.png']);
    
    $pdo-&gt;commit();
    echo "Benutzer erfolgreich erstellt!";
    
} catch (Exception $e) {
    $pdo-&gt;rollBack();
    echo "Fehler: " . $e-&gt;getMessage();
}</code></pre>
</div>

<h2 id="geplante-features">🚀 Geplante Features</h2>

<div class="row">
    <div class="col-md-6">
        <h4>🏁 Version 1.1</h4>
        <ul>
            <li>⏳ Model-System (ActiveRecord)</li>
            <li>⏳ Database-Manager</li>
            <li>⏳ Schema Builder</li>
            <li>⏳ Migration-System</li>
        </ul>
    </div>
    
    <div class="col-md-6">
        <h4>🚀 Version 1.2</h4>
        <ul>
            <li>⏳ Query Builder</li>
            <li>⏳ Relationship-Management</li>
            <li>⏳ Database Seeding</li>
            <li>⏳ Connection Pooling</li>
        </ul>
    </div>
</div>

<h2 id="migration-system">🛠️ Migration-System (Vorschau)</h2>

<h3>Migration erstellen</h3>

<div class="code-block">
<pre><code class="language-php">&lt;?php

// migrations/2025_09_29_000001_create_users_table.php

use Brick\Database\Migration;
use Brick\Database\Schema\Blueprint;
use Brick\Database\Schema\Schema;

class CreateUsersTable extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table-&gt;id();
            $table-&gt;string('name');
            $table-&gt;string('email')-&gt;unique();
            $table-&gt;timestamp('email_verified_at')-&gt;nullable();
            $table-&gt;string('password');
            $table-&gt;rememberToken();
            $table-&gt;timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}</code></pre>
</div>

<h3>Migration ausführen</h3>

<div class="code-block">
<pre><code class="language-bash"># Zukünftige CLI-Kommandos
php brick migrate
php brick migrate:rollback
php brick migrate:reset
php brick make:migration create_posts_table</code></pre>
</div>

<h2 id="query-builder">🔍 Query Builder (Vorschau)</h2>

<h3>Fluent Interface</h3>

<div class="code-block">
<pre><code class="language-php">&lt;?php

use Brick\Database\DB;

// Select Queries
$users = DB::table('users')
    -&gt;where('active', 1)
    -&gt;orderBy('name')
    -&gt;limit(10)
    -&gt;get();

// Join Queries
$posts = DB::table('posts')
    -&gt;join('users', 'posts.user_id', '=', 'users.id')
    -&gt;select('posts.*', 'users.name as author')
    -&gt;where('posts.published', 1)
    -&gt;get();

// Insert
$userId = DB::table('users')-&gt;insertGetId([
    'name' =&gt; 'Jane Doe',
    'email' =&gt; 'jane@example.com',
    'password' =&gt; $hashedPassword,
    'created_at' =&gt; now()
]);

// Update
DB::table('users')
    -&gt;where('id', $userId)
    -&gt;update([
        'email_verified_at' =&gt; now(),
        'updated_at' =&gt; now()
    ]);

// Delete
DB::table('users')
    -&gt;where('email_verified_at', null)
    -&gt;where('created_at', '&lt;', now()-&gt;subDays(30))
    -&gt;delete();</code></pre>
</div>

<h3>Model-System (Vorschau)</h3>

<div class="code-block">
<pre><code class="language-php">&lt;?php

namespace App\Models;

use Brick\Database\Model;

class User extends Model
{
    protected string $table = 'users';
    
    protected array $fillable = [
        'name', 'email', 'password'
    ];
    
    protected array $hidden = [
        'password', 'remember_token'
    ];
    
    protected array $casts = [
        'email_verified_at' =&gt; 'datetime',
        'created_at' =&gt; 'datetime',
        'updated_at' =&gt; 'datetime'
    ];
    
    // Relationships
    public function posts()
    {
        return $this-&gt;hasMany(Post::class);
    }
    
    public function profile()
    {
        return $this-&gt;hasOne(UserProfile::class);
    }
}

// Usage
$user = User::create([
    'name' =&gt; 'John Doe',
    'email' =&gt; 'john@example.com',
    'password' =&gt; bcrypt('secret')
]);

$posts = $user-&gt;posts()-&gt;where('published', 1)-&gt;get();
$profile = $user-&gt;profile;</code></pre>
</div>

<h2>💼 Aktuelle Best Practices</h2>

<div class="example-block">
    <h5>✨ Empfehlungen</h5>
    <ul>
        <li><strong>Prepared Statements:</strong> Verwenden Sie immer Parameter-Binding</li>
        <li><strong>Transaktionen:</strong> Nutzen Sie Transaktionen für atomare Operationen</li>
        <li><strong>Error Handling:</strong> Implementieren Sie umfassendes Error-Handling</li>
        <li><strong>Connection Pooling:</strong> Verwenden Sie Singleton-Pattern für Connections</li>
    </ul>
</div>

<div class="warning-block">
    <h5>⚠️ Sicherheitshinweise</h5>
    <ul>
        <li>Niemals direkte String-Interpolation in SQL verwenden</li>
        <li>Immer Prepared Statements für Benutzereingaben nutzen</li>
        <li>Datenbankzugangsdaten in Umgebungsvariablen speichern</li>
        <li>Regelmäßige Backups der Datenbank erstellen</li>
    </ul>
</div>

<h2>🚀 Nächste Schritte</h2>

<div class="row">
    <div class="col-md-4">
        <a href="/docs/api" class="btn btn-primary w-100">📚 API Referenz</a>
    </div>
    <div class="col-md-4">
        <a href="/docs" class="btn btn-outline-primary w-100">📖 Dokumentation</a>
    </div>
    <div class="col-md-4">
        <a href="https://github.com/bitka-de/brick-framework" class="btn btn-outline-secondary w-100">💙 GitHub</a>
    </div>
</div>
@endsection

@section('prev-page')
<a href="/docs/middleware" class="btn btn-outline-secondary">
    ← Middleware
</a>
@endsection

@section('next-page')
<a href="/docs/api" class="btn btn-primary">
    API Referenz →
</a>
@endsection