<?php
/**
 * Standalone integration test for the Eloquent foundation layer.
 *
 * Run: php tests/MModelTest.php
 *
 * Tests MEloquentBootstrap, MModel, and MEloquentTransaction
 * using an in-memory SQLite database (no external DB required).
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\MEloquentBootstrap;
use App\Database\MEloquentTransaction;
use App\Database\MModel;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\SoftDeletes;

// ── Test Model Classes ────────────────────────────────────────────

class TestProduto extends MModel
{
    const TABLENAME  = 'produto';
    const PRIMARYKEY = 'id';

    public $timestamps = false;
    protected $connection = 'default';
    protected $fillable = ['nome', 'preco', 'ativo', 'categoria_id'];
    protected $casts = [
        'preco' => 'float',
        'ativo' => 'boolean',
    ];

    public function categoria()
    {
        return $this->belongsTo(TestCategoria::class, 'categoria_id');
    }

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }
}

class TestCategoria extends MModel
{
    const TABLENAME  = 'categoria';
    const PRIMARYKEY = 'id';

    public $timestamps = false;
    protected $connection = 'default';
    protected $fillable = ['nome'];

    public function produtos()
    {
        return $this->hasMany(TestProduto::class, 'categoria_id');
    }
}

class TestSoftDeleteModel extends MModel
{
    use SoftDeletes;

    const TABLENAME = 'soft_item';
    public $timestamps = false;
    protected $connection = 'default';
    protected $fillable = ['nome'];
}

// ── Test Harness ──────────────────────────────────────────────────

$pass = 0;
$fail = 0;

function assert_test(string $label, bool $condition, string $detail = ''): void
{
    global $pass, $fail;
    if ($condition) {
        echo "  PASS: {$label}\n";
        $pass++;
    } else {
        echo "  FAIL: {$label}" . ($detail ? " — {$detail}" : "") . "\n";
        $fail++;
    }
}

// ── Bootstrap with fake config ────────────────────────────────────

echo "=== MModel Integration Test ===\n\n";

// Create a MConfigLoader with SQLite in-memory config
$configLoader = new \App\Utils\MConfigLoader(false);
$configLoader->setConf('db.test.system', 'sqlite');
$configLoader->setConf('db.test.name', ':memory:');

echo "1. Bootstrap\n";

MEloquentBootstrap::reset(); // ensure clean state
$capsule = MEloquentBootstrap::boot($configLoader);
assert_test('boot() returns Capsule', $capsule instanceof \Illuminate\Database\Capsule\Manager);
assert_test('getCapsule() returns same instance', MEloquentBootstrap::getCapsule() === $capsule);
assert_test('idempotent boot', MEloquentBootstrap::boot($configLoader) === $capsule);

// ── Create schema ─────────────────────────────────────────────────

DB::connection('default')->getSchemaBuilder()->create('categoria', function ($table) {
    $table->increments('id');
    $table->string('nome');
});

DB::connection('default')->getSchemaBuilder()->create('produto', function ($table) {
    $table->increments('id');
    $table->string('nome');
    $table->float('preco')->default(0);
    $table->boolean('ativo')->default(true);
    $table->unsignedInteger('categoria_id')->nullable();
});

DB::connection('default')->getSchemaBuilder()->create('soft_item', function ($table) {
    $table->increments('id');
    $table->string('nome');
    $table->softDeletes();
});

// ── CRUD Tests ────────────────────────────────────────────────────

echo "\n2. CRUD Operations\n";

$p = TestProduto::create(['nome' => 'Widget', 'preco' => 19.99, 'ativo' => true]);
assert_test('create() returns model', $p instanceof TestProduto);
assert_test('create() sets id', $p->id > 0);

$found = TestProduto::find($p->id);
assert_test('find() returns model', $found !== null);
assert_test('find() correct nome', $found->nome === 'Widget');
assert_test('cast float works', $found->preco === 19.99);
assert_test('cast boolean works', $found->ativo === true);

$found->nome = 'Updated Widget';
$found->save();
$refreshed = TestProduto::find($p->id);
assert_test('update via save()', $refreshed->nome === 'Updated Widget');

$refreshed->delete();
assert_test('delete()', TestProduto::find($p->id) === null);

// ── TABLENAME / PRIMARYKEY Constants ──────────────────────────────

echo "\n3. Table/Key Constants\n";

$model = new TestProduto;
assert_test('TABLENAME constant', $model->getTable() === 'produto');
assert_test('PRIMARYKEY constant', $model->getKeyName() === 'id');

// ── Query Builder + Scopes ────────────────────────────────────────

echo "\n4. Query Builder & Scopes\n";

TestProduto::create(['nome' => 'Active1', 'preco' => 10, 'ativo' => true]);
TestProduto::create(['nome' => 'Active2', 'preco' => 200, 'ativo' => true]);
TestProduto::create(['nome' => 'Inactive', 'preco' => 50, 'ativo' => false]);

$all = TestProduto::all();
assert_test('all() returns 3', $all->count() === 3);

$ativos = TestProduto::ativos()->get();
assert_test('scope ativos() returns 2', $ativos->count() === 2);

$expensive = TestProduto::ativos()->where('preco', '>', 100)->get();
assert_test('chained where + scope', $expensive->count() === 1);
assert_test('correct filtered result', $expensive->first()->nome === 'Active2');

$count = TestProduto::where('ativo', true)->count();
assert_test('count()', $count === 2);

// ── Relationships ─────────────────────────────────────────────────

echo "\n5. Relationships\n";

$cat = TestCategoria::create(['nome' => 'Eletronicos']);
$p1 = TestProduto::create(['nome' => 'TV', 'preco' => 999, 'ativo' => true, 'categoria_id' => $cat->id]);
$p2 = TestProduto::create(['nome' => 'Radio', 'preco' => 49, 'ativo' => true, 'categoria_id' => $cat->id]);

$loaded = TestProduto::find($p1->id);
assert_test('belongsTo loads', $loaded->categoria !== null);
assert_test('belongsTo correct', $loaded->categoria->nome === 'Eletronicos');

$catLoaded = TestCategoria::find($cat->id);
assert_test('hasMany count', $catLoaded->produtos->count() === 2);

// ── Transactions ──────────────────────────────────────────────────

echo "\n6. Transactions\n";

$beforeCount = TestProduto::count();

MEloquentTransaction::run(function () {
    TestProduto::create(['nome' => 'InTransaction', 'preco' => 5, 'ativo' => true]);
});
assert_test('transaction commit', TestProduto::count() === $beforeCount + 1);

// Rollback test
$beforeCount = TestProduto::count();
try {
    MEloquentTransaction::run(function () {
        TestProduto::create(['nome' => 'WillRollback', 'preco' => 1, 'ativo' => true]);
        throw new \RuntimeException('force rollback');
    });
} catch (\RuntimeException) {
    // expected
}
assert_test('transaction rollback', TestProduto::count() === $beforeCount);

// Manual transaction
MEloquentTransaction::begin();
TestProduto::create(['nome' => 'Manual', 'preco' => 7, 'ativo' => true]);
MEloquentTransaction::commit();
assert_test('manual begin/commit', TestProduto::where('nome', 'Manual')->exists());

MEloquentTransaction::begin();
TestProduto::create(['nome' => 'ManualRollback', 'preco' => 3, 'ativo' => true]);
MEloquentTransaction::rollback();
assert_test('manual rollback', !TestProduto::where('nome', 'ManualRollback')->exists());

// ── Soft Deletes ──────────────────────────────────────────────────

echo "\n7. Soft Deletes\n";

$item = TestSoftDeleteModel::create(['nome' => 'SoftItem']);
$item->delete();
assert_test('soft deleted not in default query', TestSoftDeleteModel::find($item->id) === null);
assert_test('soft deleted in trashed query', TestSoftDeleteModel::withTrashed()->find($item->id) !== null);

$item->restore();
assert_test('restore() works', TestSoftDeleteModel::find($item->id) !== null);

// ── Serialization ─────────────────────────────────────────────────

echo "\n8. Serialization\n";

$p = TestProduto::first();
$arr = $p->toArray();
assert_test('toArray() has nome', isset($arr['nome']));

$json = $p->toJson();
assert_test('toJson() is valid JSON', json_decode($json) !== null);

// ── Results ───────────────────────────────────────────────────────

echo "\n=== Results: {$pass} passed, {$fail} failed ===\n";
exit($fail > 0 ? 1 : 0);
