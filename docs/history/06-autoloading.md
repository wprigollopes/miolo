# Autoloading Before Autoload

**Before PHP had `spl_autoload_register()`, MIOLO built its own class
loading system with XML manifests, manual `include()` chains, and
file-size tracking.**

Today, PHP autoloading is invisible. Composer generates a classmap or
PSR-4 autoloader, you `use App\Models\User`, and the file is loaded
automatically. Nobody thinks about it.

In PHP 3 and 4, there was no autoloading. If you needed a class, you
wrote `require_once 'path/to/class.php'` — and you wrote it in every
file that used that class. Get the path wrong, and you got a fatal error.
Forget the include, and the class didn't exist.

---

## What Didn't Exist

- **`__autoload()`** — PHP 5.0, 2004 (deprecated in 7.2)
- **`spl_autoload_register()`** — PHP 5.1.2, 2006
- **PSR-0** (autoloading standard) — 2009
- **PSR-4** (improved autoloading) — 2013
- **Composer autoloader** — 2012

### The PHP 3/4 Reality

Every file that used a class had to explicitly include the file where
that class was defined:

```php
require_once 'classes/database/mdatabase.class.php';
require_once 'classes/ui/controls/mform.class.php';
require_once 'classes/utils/mutil.class.php';

$db = new MDatabase();
$form = new MForm();
```

If class A included class B, and class B included class C, you had to
manage the entire dependency chain yourself. Circular includes crashed
the application. Missing includes produced cryptic "class not found"
errors.

---

## MIOLO's Three-Layer Autoloading

MIOLO solved this problem with three progressively more sophisticated
mechanisms:

### Layer 1: Bootstrap Includes

**File:** `classes/miolo.class.php` — `init()` method

Before any autoloading can work, the autoloader itself must be loaded.
MIOLO bootstraps with bare `include()` calls — the chicken-and-egg
problem of autoloading:

```php
public function init()
{
    include('flow/mexception.class.php');
    include('utils/mxmltree.class.php');
    include('compatibility/mcompatibility.class.php');
    include('utils/mautoload.class.php');

    $this->getObject('autoload');
    $autoload = $this->autoload;
    // Now autoloading works
}
```

These four files are the minimum needed to bootstrap the autoloader.
Everything else is loaded on demand.

### Layer 2: The `uses()` Method

**File:** `classes/miolo.class.php` — `uses()` method

`uses()` is MIOLO's manual require-by-path system with deduplication
and file-size tracking:

```php
public function uses($name, $module = NULL)
{
    $unique = ($module != NULL ? $module : 'classes') . '::' . $name;

    if (!array_key_exists($unique, $this->uses)) {
        $path = $module
            ? $this->getModulePath($module, $name)
            : $this->getAbsolutePath('classes/' . $name);

        $this->uses[$unique] = array($name, filesize($path));
        include_once($path);
        $this->logMessage('[USES] file:' . $path);
    }
}
```

Key features:
- **Deduplication** — the `$this->uses` array tracks what's already loaded
- **File-size tracking** — records the size of every included file (for
  profiling and memory analysis)
- **Logging** — every include is logged, so you can trace exactly what
  was loaded during a request
- **Module-aware** — can load classes from either the core (`classes/`)
  or any module (`modules/$module/classes/`)

Usage throughout the codebase:

```php
$MIOLO->uses('ui/controls/mform.class.php');
$MIOLO->uses('classes/bUtil.class.php', 'base');  // from the 'base' module
```

### Layer 3: XML Autoloader

**File:** `classes/utils/mautoload.class.php`

The most sophisticated layer: an XML manifest that maps class names to
file paths.

```php
class MAutoLoad
{
    public function __construct()
    {
        $file = $MIOLO->getConf('home.classes') . '/etc/autoload.xml';
        $this->xml = new MSimpleXML($file);
    }

    public function getFile($className)
    {
        $value = $this->xml->xpath("loadclass[name='$className']");
        $fileName = (string) $value[0]->file;
        return $fileName;
    }
}
```

**File:** `classes/etc/autoload.xml`

The manifest:

```xml
<autoload>
    <loadclass>
        <name>mdatabase</name>
        <file>database/mdatabase.class.php</file>
    </loadclass>
    <loadclass>
        <name>msql</name>
        <file>database/msql.class.php</file>
    </loadclass>
    <loadclass>
        <name>mbusiness</name>
        <file>model/mbusiness.class.php</file>
    </loadclass>
    <loadclass>
        <name>mhandler</name>
        <file>flow/mhandler.class.php</file>
    </loadclass>
    <!-- 60+ more entries -->
</autoload>
```

When PHP encounters an unknown class, the autoloader looks it up in
the XML, finds the file path, and includes it.

### Bridging Old and New

**File:** `classes/support.inc.php`

When PHP 5.1 introduced `spl_autoload_register()`, MIOLO registered
its XML autoloader as a proper SPL autoloader — but only when Composer
isn't present:

```php
function miolo_autoload($className)
{
    global $autoload;
    if (($file = $autoload->getFile($className)) != '') {
        include_once($file);
    }
}

if (!class_exists('Composer\\Autoload\\ClassLoader', false)) {
    spl_autoload_register('miolo_autoload');
}
```

This graceful dual-mode shim means the framework works both with and
without Composer — essential for a codebase that spans 25 years of PHP.

### Extension Autoloading

**File:** `classes/miolo.class.php` — `loadExtensions()`

Extensions (plugins) ship their own `autoload.xml` files:

```php
public function loadExtensions()
{
    foreach ($extensions as $extension) {
        $autoload = $dir . '/' . $extension . '/autoload.xml';
        if (file_exists($autoload)) {
            $this->autoload->loadFile($autoload);
        }
    }
}
```

This is a plugin architecture — each extension registers its classes
independently. The same concept as Composer's `autoload` section in
`composer.json`, but implemented with XML files.

---

## Comparison: MIOLO Autoloading vs Modern Standards

| Feature | MIOLO (2001-2006) | PSR-0 (2009) | PSR-4 / Composer (2013) |
|---|---|---|---|
| Class → File mapping | XML manifest | Namespace → directory | Namespace prefix → base dir |
| Registration | Custom + `spl_autoload_register` | `spl_autoload_register` | `vendor/autoload.php` |
| Discovery | Manual XML entries | Convention-based | Composer classmap/PSR-4 |
| Module support | Per-module autoload.xml | No concept | `autoload` in `composer.json` |
| Performance | XML parsing on every request | Directory traversal | Optimized classmap dump |
| Deduplication | `$this->uses` array | `include_once` | Composer classmap cache |
| Profiling | File-size tracking built in | None | None |

---

## What This Tells Us

The autoloading problem — "given a class name, find and load its file" —
is universal. MIOLO solved it three different ways as PHP evolved: manual
includes (PHP 3/4), XML manifests (PHP 4/5), and SPL registration
(PHP 5.1+). Each solution carries the DNA of its era.

The XML autoloader is particularly interesting because it's the same
concept as Composer's `classmap` — a lookup table from class names to
file paths. Composer generates this table automatically from PSR-4 rules;
MIOLO maintained it by hand. The engineering insight is identical; only
the automation level differs.
