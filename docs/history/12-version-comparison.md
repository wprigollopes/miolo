# Version Comparison: v1.0 to v2.0 — An Architectural Leap

**MIOLO 1.0 was a PHP 4 framework with 688 files. Version 2.0 was a
complete rewrite with 8,675 files. Nearly everything changed: language
version, configuration format, class naming, directory layout, database
support, UI architecture, and JavaScript strategy.**

This comparison is based on direct analysis of the `archive/v1.0` and
`archive/v2.0` git branches (orphan branches in this repository
containing full SVN exports from `branches/1.0/trunk/` and
`tags/2.0.01/` respectively). To inspect them:
`git checkout archive/v1.0` or `git checkout archive/v2.0`.

---

## At a Glance

| Metric | v1.0 | v2.0 | Change |
|---|---|---|---|
| Total files | 688 | 8,675 | 12.6x |
| PHP class files (`.class`) | 81 | 429 | 5.3x |
| PHP includes (`.inc`) | 74 | 182 | 2.5x |
| JavaScript files | 3 | 4,318 | 1,439x |
| CSS files | 6 | 778 | 130x |
| Image assets | 127 | 1,907 | 15x |
| SQL scripts | 5 | 16 | 3.2x |
| Config files | 4 | 20 | 5x |
| Application modules | 4 | 8 | 2x |
| Database engines | 4 | 7 | 1.75x |
| UI themes | 1 | 6 | 6x |
| Third-party JS libraries | 0 | 8+ | — |
| i18n translations | Portuguese only | Portuguese + Spanish | — |
| PHP target | PHP 4 | PHP 5.2+ | — |
| Config format | PHP arrays | XML | — |
| Source control | CVS → SVN | SVN | — |

---

## PHP Language Evolution

Version 1.0 is pure PHP 4 code. Version 2.0 is PHP 5.

| Feature | v1.0 | v2.0 |
|---|---|---|
| Constructor | `function MIOLO()` (PHP 4) | `public function __construct()` |
| Properties | `var $theme` | `public $theme` / `private $instance` |
| Visibility | None — everything public by default | `public`, `private`, `protected` |
| Static methods | Not available | `static private $instance`, `public static function getInstance()` |
| Abstract classes | Not available | `abstract class MComponent`, `abstract class MConnection` |
| Interfaces | Not available | `IAccess`, `IGroup`, `ILog`, `ISession`, `ITransaction`, `IUser` |
| Type hints | Not available | Parameter type hints: `PersistentObject $object` |
| Exceptions | Not available | `throw new EDatabaseExecException(...)` |
| Global access | `global $MIOLO` everywhere | `MIOLO::getInstance()` singleton |
| Class naming | `Database`, `Context`, `Theme` | `MDatabase`, `MContext`, `MTheme` (M-prefix) |

The 1.0 constructor used `function MIOLO()` (the PHP 4 convention where
the constructor name matched the class name). Every property used `var`
because visibility modifiers didn't exist. The `static` keyword, which
makes the Singleton pattern possible, wasn't available until PHP 5.

---

## Directory Structure

### v1.0: Flat and Simple

```
classes/            Core framework (16 files)
  database/         4 drivers × 2 files each (flat)
  ui/               15 UI classes
  components/       3 utility components
  contrib/          Barcode, TSCounter, PostScript
  pslib/            PostScript library
etc/                Configs + installer
html/               Web root + single theme
  themes/miolo/     One theme
  scripts/          3 JavaScript files
locale/pt_BR/       Portuguese only
modules/
  common/           Auth, users, admin
  examples/         Widget demos
  tutorial/         CRUD tutorial
  wiki/             Full wiki application
```

### v2.0: Deep and Specialized

```
classes/            Core framework (expanded)
  database/         7 engine subdirectories, each with 5+ files
  ui/
    controls/       85+ control classes
    painter/        HTML rendering layer
    reports/        JasperReports, PDF, EzPDF
    themes/         6 themes (clean, kenobi, lemon, miolo, miolo2, system)
  extensions/       PHPMailer, CPAINT, Doctrine DBAL, Jasper, SPAW
  flow/             Exception/error handling
  interfaces/       6 PHP interfaces
  model/            Business layer base classes
  persistence/      31-class ORM subsystem
  security/         Auth, permissions, LDAP
  services/         Session, logging, tracing, web services, profiling
  tests/            Unit testing framework
  utils/            Utilities
docs/               PDF documentation
etc/
  miolo/            XML config files
  webinstaller/     Web-based installer (new)
  setup/            Qt3 installer (carried over)
html/
  scripts/          4,318 JS files (jQuery, Dojo, CKEditor, etc.)
locale/
  pt_BR/            Portuguese
  es/               Spanish (new)
modules/
  admin/            Administration
  admin_ldap/       LDAP admin (new)
  base/             Base module (new)
  common/           Shared utilities
  hangman/          Demo game (new)
  helloworld/       Getting-started demo (new)
  locadora/         Video rental sample (new)
  tutorial/         Expanded tutorial
var/
  db/               SQLite databases
```

---

## Database Layer

### v1.0: 4 Standalone Drivers

Each driver was an independent class with no shared parent:

```
database/postgres_connection.class  (290 lines)
database/postgres_query.class
database/mysql_connection.class     (186 lines)
database/mysql_query.class
database/mssql_connection.class     (240 lines)
database/mssql_query.class
database/sqlite_connection.class    (233 lines)
database/sqlite_query.class
```

The `Database` factory (`database.class`, 293 lines) loaded drivers by
string name. No base class, no inheritance, no shared interface.

### v2.0: 7 Engines with Inheritance Hierarchy

Each engine has its own subdirectory with 5+ files extending a common
base:

```
database/mconnection.class      (base — 212 lines)
database/mdatabase.class        (factory — 373 lines)
database/mquery.class           (base — 328 lines)
database/msql.class             (query builder — 1,400 lines)
database/mdataset.class         (result sets — 452 lines)
database/mtransaction.class     (base — 107 lines)
database/midgenerator.class     (ID generation)
database/mschema.class          (schema introspection)
database/mdbal.class            (DBAL facade — 191 lines)
database/misr.class             (ISR abstraction — 420 lines)

database/postgres/mconnection.class  (615 lines)
database/postgres/mquery.class
database/postgres/msqljoin.class
database/postgres/mtransaction.class
database/postgres/midgenerator.class
database/postgres/mschema.class

database/mysql/...         (same pattern)
database/firebird/...      (new engine)
database/oracle8/...       (new engine)
database/sqlite/...
database/mssql/...
database/odbc/...          (new engine)
```

New engines added: **Firebird**, **Oracle 8**, **ODBC**. The PostgreSQL
driver grew from 290 to 615 lines — the SQLSTATE error mapping alone
accounts for 500+ lines.

Additionally, v2.0 bundled **Doctrine DBAL** (143 PHP files) as an
extension under `classes/extensions/doctrine-dbal/`.

---

## Configuration Format

### v1.0: PHP Arrays

```php
<?php
$MIOLOCONF['home']['miolo']    = '/var/www/html/bis/classes';
$MIOLOCONF['DB']['bis']['system'] = 'postgres';
$MIOLOCONF['DB']['bis']['host']   = '127.0.0.1';
$MIOLOCONF['theme']['main']    = 'fermi';
$MIOLOCONF['login']['check']   = false;
?>
```

### v2.0: XML

```xml
<?xml version="1.0" encoding="ISO-8859-1" standalone="yes"?>
<conf>
    <home>
       <miolo>/var/www/sagunovo/miolo20</miolo>
       <classes>/var/www/sagunovo/miolo20/classes</classes>
    </home>
    <db>
       <miolo>
           <system>sqlite</system>
           <host>localhost</host>
           <name>/var/www/sagunovo/miolo20/var/db/miolo.sqlite</name>
       </miolo>
    </db>
    <theme>
       <main>miolo2</main>
    </theme>
    <login>
        <check>false</check>
    </login>
</conf>
```

The move from PHP to XML was significant: XML configs can be parsed
without executing PHP, validated against schemas, and edited by tools
that don't understand PHP syntax. It also meant configuration was no
longer executable code — a security improvement.

---

## UI / Frontend

### v1.0: Minimal Server-Side

- **1 theme** (`miolo`) with 127 image assets
- **3 JavaScript files** — `common.js`, `tabpane.js`, `wiki.js`
- **6 CSS files**
- **15 UI classes** — forms, listings, tabs, menus, page navigation
- **No component hierarchy** — the `UI` class was a bootstrap loader
  that included other files
- **No painter pattern** — HTML generated directly

### v2.0: Full Component Architecture

- **6 themes** (`clean`, `kenobi`, `lemon`, `miolo`, `miolo2`, `system`)
- **4,318 JavaScript files** — jQuery, Dojo, CKEditor, HTMLArea,
  ActiveWidgets, JSCookMenu, Tigra, datepicker with 40+ locales
- **778 CSS files** (modular: `m_boxes.css`, `m_controls.css`,
  `m_forms.css`, `m_grids.css`, etc.)
- **85+ UI control classes** — `MComponent` → `MControl` → grids, forms,
  popups, lookups, menus, trees, charts, calendars, toolbars
- **Painter pattern** — `MBasePainter` / `MHTMLPainter` separating
  component logic from HTML rendering
- **Report renderers** — JasperReports (with 72 Java JARs), PSLib PDF,
  EzPDF
- **AJAX support** — via CPAINT and Dojo integration

The jump from 3 to 4,318 JavaScript files is dramatic but mostly
reflects bundled third-party libraries. MIOLO's own JS files (~30)
carry the `m_` prefix: `m_common.js`, `m_lookup.js`, `m_grid.js`,
`m_validate.js`, `m_ajax.js`, `m_popup.js`, etc.

---

## New Subsystems in v2.0

### ORM / Persistence Layer (31 classes)

An entire Data Mapper ORM, absent in v1.0:

```
classes/persistence/
    persistentobject.class       Base mapped entity
    persistentmanager.class      CRUD operations
    criteria/                    10 query criteria classes
    map/                         7 mapping classes (ClassMap, TableMap, ColumnMap...)
    converter/                   7 type converters
    phpconfigloader.class        PHP-based mappings
    xmlconfigloader.class        XML-based mappings
```

### Web Services

```
html/webservices.php             HTTP entry point
classes/services/mwebservice.class   Service class
```

### Web Installer

A complete browser-based installation wizard:

```
etc/webinstaller/
    index.php                    Installer entry point
    installer.class              Main logic
    handlers/                    Step-by-step installation screens
```

### Interfaces

PHP 5 interfaces for the admin subsystem — a contract layer that
didn't exist in PHP 4:

```
classes/interfaces/
    IAccess, IGroup, ILog, ISession, ITransaction, IUser
```

### Security Layer

```
classes/security/
    Auth, permissions, LDAP integration
```

### Testing Framework

```
classes/tests/
    Unit testing framework (framework-internal, not PHPUnit)
```

---

## Module Growth

| Module | v1.0 | v2.0 |
|---|---|---|
| `common` | Auth, users, admin | Shared utilities |
| `admin` | (part of common) | Standalone admin module |
| `admin_ldap` | — | LDAP-based admin (new) |
| `base` | — | Base utilities module (new) |
| `examples` | Widget demos | (removed or merged) |
| `tutorial` | Basic CRUD | Expanded: AJAX, persistence, ORM demos |
| `wiki` | Full wiki app | (not present in 2.0.01 tag) |
| `hangman` | — | Demo game (new) |
| `helloworld` | — | Getting-started demo (new) |
| `locadora` | — | Video rental sample app (new) |

---

## Third-Party Dependencies

### v1.0: 3 Vendored Libraries

| Library | Purpose |
|---|---|
| TSCounter | Web hit counter |
| Barcode I25 | Barcode generation |
| PSLib | PostScript/PDF generation |

No `vendor/` directory. No package manager. All code copied into
`classes/contrib/` and `classes/pslib/`.

### v2.0: Expanded Ecosystem

| Library | Purpose | Location |
|---|---|---|
| TSCounter | Web hit counter (carried over) | `classes/contrib/` |
| Barcode I25 | Barcode generation (carried over) | `classes/contrib/` |
| PSLib | PostScript library (carried over) | `classes/pslib/` |
| dBug | Debug output | `classes/contrib/` |
| EasyDownload | File download handler | `classes/contrib/` |
| PHPMailer | Email library | `classes/extensions/` |
| CPAINT | AJAX toolkit | `classes/extensions/` |
| Doctrine DBAL | Database abstraction (143 files) | `classes/extensions/doctrine-dbal/` |
| JasperReports | Report engine (72 JARs) | `classes/extensions/jasper/` |
| SPAW | WYSIWYG editor | `classes/extensions/` |
| EzPDF | PDF generation | `classes/ezpdf/` |
| jQuery | DOM manipulation | `html/scripts/jquery/` |
| Dojo Toolkit | AJAX + widgets | `html/scripts/dojoroot/` |
| CKEditor | Rich text editor | `html/scripts/ckeditor/` |
| ActiveWidgets | Data grid | `html/scripts/activewidgets/` |
| HTMLArea | Rich text editor | `html/scripts/htmlarea/` |
| JSCookMenu | DHTML menus | `html/scripts/jscookmenu/` |
| Dynarch Calendar | Date picker (40+ locales) | `html/scripts/datepicker/` |
| Tigra | Tree/menu widget | `html/scripts/tigra/` |

Still no Composer. All libraries vendored directly. But the scope of
external dependencies grew enormously — reflecting the web's own
maturation from static pages to rich interactive applications.

---

## The Compatibility Bridge

**File:** `classes/compatibility.class`

Version 2.0 included a backwards-compatibility shim that mapped old
class names to new ones:

```php
class Business  extends MBusiness  {}
class Database  extends MDatabase  {}
class sql       extends MSQL       {}
```

This allowed code written for v1.0's naming convention (`Database`,
`Business`) to work under v2.0's M-prefix convention (`MDatabase`,
`MBusiness`) — a migration aid that acknowledged the scale of the
naming change.

---

## What This Tells Us

The jump from v1.0 to v2.0 wasn't an upgrade — it was a rewrite.
Nearly every architectural decision changed:

- **Language**: PHP 4 → PHP 5 (unlocking visibility, static, abstract,
  interfaces, exceptions, `__construct`)
- **Architecture**: Standalone classes → inheritance hierarchies
- **Configuration**: Executable PHP → declarative XML
- **Database**: 4 flat drivers → 7 engines with base classes and 10+
  supporting abstractions (query builder, datasets, transactions,
  ID generators, schema introspection, DBAL)
- **UI**: Bootstrap loader → 85+ component classes with painter pattern
- **Frontend**: 3 JS files → 4,318 (bundled frameworks for AJAX, rich
  text, grids, calendars, menus)
- **ORM**: Nothing → 31-class persistence layer with XML mappings
- **Naming**: `Database` → `MDatabase` (with compatibility shim)
- **Themes**: 1 → 6
- **Modules**: 4 → 8
- **Third-party**: 3 vendored libs → 18+ including Doctrine DBAL and
  JasperReports

The v1.0 codebase (688 files, ~23K lines of PHP) was a working
framework — it powered real applications at UNIVATES. But it was
constrained by PHP 4's limited OOP. The v2.0 rewrite (8,675 files)
took the architectural lessons from v1.0 and rebuilt them with PHP 5's
full object model. The compatibility bridge shows this was a conscious
migration, not an abandonment — v1.0 code could still run under v2.0.

This pattern — build a working v1, learn from it, rewrite with better
tools — is how many successful frameworks evolve. Django 1.0 to 2.0,
Angular.js to Angular, Vue 2 to Vue 3. MIOLO did the same, just earlier
and with less fanfare.
