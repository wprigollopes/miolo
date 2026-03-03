# Autoloader Modernization Design

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Replace the legacy XML-based autoloader (MAutoLoad + autoload.xml + MCompatibility + compatibility.xml) with Composer-only autoloading.

**Architecture:** Composer's classmap already discovers all 300+ classes. The XML autoloader is fully redundant. We remove it and inline the 8 compatibility aliases + MIOLO 2.0 fallback into a small registered autoloader function.

**Tech Stack:** Composer classmap autoloading, `spl_autoload_register`, `class_alias`

---

## Current State

```
Composer classmap (vendor/autoload.php)
    ↓ (miss)
miolo_autoload() in support.php  ← only registered when Composer absent (dead path)
    ↓
MAutoLoad (parses autoload.xml via MSimpleXML, XPath lookups)
    ↓ (miss)
MCompatibility (parses compatibility.xml, class_alias + MIOLO 2.0 sAutoload)
```

**Files to remove:**
- `classes/utils/mAutoLoad.php` — XML autoloader class
- `classes/etc/autoload.xml` — 376-line XML class map (redundant with Composer classmap)
- `classes/compatibility/mCompatibility.php` — XML compatibility handler
- `classes/etc/compatibility.xml` — 8 legacy alias mappings

**Files to modify:**
- `classes/mIOLO.php` — remove manual includes and MAutoLoad initialization from `init()`, simplify `loadExtensions()`
- `classes/support.php` — replace `miolo_autoload()` with a thin compatibility autoloader
- `composer.json` — add PagSeguro classmap if still needed

**Files to keep (MSimpleXML consumers unrelated to autoloading):**
- `classes/ui/mUI.php` — uses MSimpleXML for menu XML parsing
- `classes/utils/mSimpleXml.php` — still needed by mUI

## Target State

```
Composer classmap (vendor/autoload.php)
    ↓ (miss — only for compatibility aliases)
miolo_compatibility_autoload() in support.php
    ↓
class_alias("M{$name}", $name) for 8 legacy names
    +
sAutoload::SAGUAutoload() for MIOLO 2.0 integration (if configured)
```

## Design Decisions

1. **8 compatibility aliases** (form→MForm, business→MBusiness, etc.) move to a simple autoloader function in `support.php` using `class_alias()`. These are needed because code may reference the short names.

2. **PagSeguro autoloading** in `miolo_autoload()` is dead code — the PagSeguro library directory doesn't exist in the codebase. Remove it.

3. **Extension autoloading** (`loadExtensions()`) — no extensions currently have `autoload.xml` files. Convert to register extension directories with Composer's classmap at runtime, or simply remove the XML loading. Since extensions are a dynamic concept, keep the method but have it add extension dirs to the include path as a fallback.

4. **`mXMLTree`** manual include in `init()` — Composer classmap handles this. Remove the manual include.

5. **`eMioloException.php`** manual include in `init()` — Composer classmap handles this. Remove the manual include.

6. **`global $autoload`** — used by `miolo_autoload()` to call `$autoload->getFile()`. Both the global and the function that uses it will be removed.

---

### Task 1: Create compatibility autoloader in support.php

**Files:**
- Modify: `classes/support.php`

**Step 1: Replace miolo_autoload() and related code**

Replace the entire `miolo_autoload()` function, `miolo2AutoloadFileCheck()`, and the `spl_autoload_register` call with a simpler compatibility autoloader:

```php
/**
 * Compatibility aliases for legacy short class names.
 * These map old MIOLO class names (without 'M' prefix) to their modern equivalents.
 */
function miolo_compatibility_autoload($className)
{
    // Legacy short names → M-prefixed class names
    static $aliases = [
        'Form'           => 'MForm',
        'Business'       => 'MBusiness',
        'Sql'            => 'MSQL',
        'Container'      => 'MContainer',
        'Hyperlink'      => 'MLink',
        'ImageForm'      => 'MImageForm',
        'FormButton'     => 'MFormButton',
        'MultiTextField' => 'MMultiTextField',
    ];

    // Handle case-insensitive lookup
    foreach ($aliases as $legacy => $modern) {
        if (strcasecmp($className, $legacy) === 0 && class_exists($modern, true)) {
            class_alias($modern, $className);
            return;
        }
    }

    // MIOLO 2.0 module integration fallback
    if (class_exists('MIOLO', false)) {
        $MIOLO = MIOLO::getInstance();
        if ($MIOLO && strlen($MIOLO->getConf('options.miolo2modules'))) {
            if (method_exists('sAutoload', 'SAGUAutoload')) {
                $original = $MIOLO->getConf('tempvar');
                if ($original && (substr(strtolower($className), 0, 8) === 'business'
                    || in_array(substr(strtolower($className), 0, 3), ['bas', 'acd', 'fin', 'acp']))) {
                    sAutoload::SAGUAutoload($original, $MIOLO->getConf('options.miolo2modules'), true);
                }
            }
        }
    }
}

spl_autoload_register('miolo_compatibility_autoload');
```

**Step 2: Remove miolo2AutoloadFileCheck() function**

Remove the entire function — it was only used by `miolo_autoload()`.

**Step 3: Remove the `global $autoload` reference from miolo_autoload()**

Already handled by replacing the function.

**Step 4: Commit**

```bash
git add classes/support.php
git commit -m "feat: replace XML autoloader with Composer-based compatibility shim"
```

---

### Task 2: Simplify mIOLO::init()

**Files:**
- Modify: `classes/mIOLO.php`

**Step 1: Remove manual includes and autoload initialization**

In `init()` method, remove:
```php
global $autoload;

include ( 'flow/eMioloException.php' );
include ( 'utils/mXMLTree.php' );
include ( 'compatibility/mCompatibility.php' );
include ( 'utils/mAutoload.php' );
```

And remove:
```php
$this->getObject('autoload');
$autoload = $this->autoload;
```

The `init()` method should start with:
```php
public function init( $home = NULL, $logname = 'miolo')
{
    $this->handlers = array();
    $this->uses     = array();
    $this->import   = array();
    $this->setLog($logname);
    // ... rest stays the same
```

**Step 2: Simplify loadExtensions()**

Replace the XML-based extension autoloading with Composer classmap additions:

```php
public function loadExtensions()
{
    $extensions = array_filter((array) $this->getConf('extensions.extension'));
    $dir = $this->getConf('home.extensions');

    foreach ($extensions as $extension)
    {
        $extDir = $dir . '/' . $extension;
        if (is_dir($extDir))
        {
            // Register extension directory for autoloading
            spl_autoload_register(function($class) use ($extDir) {
                $file = $extDir . '/' . $class . '.php';
                if (file_exists($file)) {
                    include_once $file;
                }
            });
        }
    }
}
```

**Step 3: Remove the `autoload` property usage**

Remove `$this->autoload` references. The `getObject('autoload')` call dynamically creates `$this->autoload = new MAutoLoad()`, which we no longer need.

**Step 4: Commit**

```bash
git add classes/mIOLO.php
git commit -m "feat: remove MAutoLoad initialization from MIOLO::init()"
```

---

### Task 3: Delete legacy autoloader files

**Files:**
- Delete: `classes/utils/mAutoLoad.php`
- Delete: `classes/etc/autoload.xml`
- Delete: `classes/compatibility/mCompatibility.php`
- Delete: `classes/etc/compatibility.xml`

**Step 1: Remove the files**

```bash
git rm classes/utils/mAutoLoad.php
git rm classes/etc/autoload.xml
git rm classes/compatibility/mCompatibility.php
git rm classes/etc/compatibility.xml
```

**Step 2: Commit**

```bash
git commit -m "feat: remove legacy XML autoloader files"
```

---

### Task 4: Verify and update composer.json

**Files:**
- Modify: `composer.json`

**Step 1: Run composer dump-autoload**

Verify that all classes are still discoverable after removing the XML autoloader files:

```bash
composer dump-autoload -o
```

**Step 2: Verify no broken references**

Search for any remaining references to the deleted files:

```bash
grep -r "mAutoLoad\|autoload\.xml\|mCompatibility\|compatibility\.xml" classes/ modules/ --include="*.php" -l
```

**Step 3: Commit if changes needed**

---

### Task 5: Final cleanup and verification

**Files:**
- Verify: all modified files

**Step 1: Grep for remaining references**

```bash
grep -rn "autoload\.xml\|MAutoLoad\|MCompatibility\|compatibility\.xml" --include="*.php" .
```

**Step 2: Update any remaining string references in comments/docs**

**Step 3: Final commit**

```bash
git commit -m "chore: clean up remaining autoloader references"
```

## Status: COMPLETED
