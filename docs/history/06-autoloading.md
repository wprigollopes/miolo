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

## MIOLO's Autoloading Evolution

### Version 1.0: The First `Uses()` Method

**SVN branch:** `branches/1.0` — `classes/miolo.class` (note: `.class`,
not `.class.php`)

Version 1.0 already had the `Uses()` method with file-size tracking
and error reporting — but in Portuguese:

```php
// Version 1.0: MIOLO::Uses() — the first class loader
// $Id: miolo.class,v 1.35 2004/08/31 01:44:23 vgartner Exp $
function Uses($name, $module = null)
{   global $MIOLOCONF;

    $this->ProfileEnter('MIOLO::Uses');

    if ($module) {
        $path = $this->GetModulePath($module, $name);
    } else {
        $path = $this->GetAbsolutePath($name);
    }

    if (!file_exists($path)) {
        // Error messages in Portuguese!
        printf("MIOLO::Uses ERRO: Arquivo '%s' NAO existe.<br>\n", $path);
        $this->error[] = "Arquivo '$path' NAO existe!";
    } else {
        $this->uses[] = array($name, filesize($path));
        include_once($path);
    }

    $this->ProfileExit('MIOLO::Uses');
}
```

Version 1.0 also had a `UsesDump()` method (in the MIOLO class itself —
later refactored into `MDump` in v2.0) that generated an HTML table
showing every loaded file and its size — a built-in profiling tool:

```php
function UsesDump()
{
    $total = 0;
    $html = "<p><b>Uses Information:</b>\n<table>\n";
    foreach ($this->uses as $u) {
        list($name, $size) = $u;
        $total += $size;
        $html .= "<tr><td>$name:</td><td>$size bytes</td></tr>\n";
    }
    $html .= "<tr><td>Total:</td><td>$total bytes</td></tr>\n</table>\n";
    return $html;
}
```

The constructor bootstrapped the framework by calling `Uses()` for each
core file — no autoloading, just explicit includes in order:

```php
function MIOLO($home = null, $logname = 'miolo')   // PHP 4 constructor
{
    $this->Uses('login.class');       // note: .class, not .class.php
    $this->Uses('context.class');
    $this->Uses('types.class');
    $this->Uses('error.class');
    $this->Uses('ui/theme.class');
    $this->Uses('ui/statusbar.class');
    $this->Uses('../modules/modules.inc');
}
```

**Key 1.0 conventions:**
- Files used `.class` extension (not `.class.php`)
- Class names had no `M` prefix: `Database`, `Context`, `Theme`, `UI`
- Error messages were in Portuguese (`"Arquivo NAO existe"`)
- CVS `$Id` tags tracked file versions
- `.cvsignore` files present — the project started in CVS before SVN

### Version 2.0: Three-Layer Autoloading

The 2.0 rewrite introduced a much more sophisticated system with three
layers:

#### Layer 1: Bootstrap Includes

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

#### Layer 2: The `uses()` Method (Evolved)

**File:** `classes/miolo.class.php` — `uses()` method

The 2.0 `uses()` evolved from 1.0 with a key-based deduplication hash
(instead of a plain array) and English logging:

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

## The File Extension Evolution

A small but telling detail: version 1.0 used `.class` as the file
extension (`miolo.class`, `context.class`, `postgres_connection.class`).
Version 2.0 changed to `.class` as well but within organized
subdirectories. The `.class.php` extension (seen in the current
codebase) was adopted later — adding `.php` ensured that web servers
wouldn't serve raw source code if directory listings were enabled.

| Version | Extension | Example |
|---|---|---|
| 1.0 | `.class` | `database/postgres_connection.class` |
| 2.0 | `.class` | `database/postgres/mconnection.class` |
| Later | `.class.php` | `database/postgres/mconnection.class.php` |

---

## What This Tells Us

The autoloading problem — "given a class name, find and load its file" —
is universal. MIOLO solved it across four eras: manual `Uses()` with
Portuguese error messages (1.0), a more structured `uses()` with
key-based deduplication (2.0), XML manifests with autoloading (2.0+),
and SPL registration with Composer fallback (current). Each solution
carries the DNA of its era.

The version 1.0 `Uses()` method is particularly interesting because it
already had file-size tracking and a `UsesDump()` profiling tool — the
team was concerned about memory usage from day one, building observability
into the loading mechanism itself.

The XML autoloader is the same concept as Composer's `classmap` — a
lookup table from class names to file paths. Composer generates this
table automatically from PSR-4 rules; MIOLO maintained it by hand.
The engineering insight is identical; only the automation level differs.
