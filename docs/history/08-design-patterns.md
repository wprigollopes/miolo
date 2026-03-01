# Design Patterns: When Patterns Were New to PHP

**The Gang of Four book was published in 1994. PHP 3 was released in 1998.
MIOLO started implementing design patterns in PHP before most PHP
developers had heard of them.**

In 1999, PHP was a procedural scripting language. Functions, includes,
and global variables were the primary tools. Object-oriented programming
was barely supported (PHP 3 had primitive class support; PHP 4 improved
it but still lacked visibility modifiers, abstract classes, interfaces,
and proper constructors).

MIOLO adopted design patterns early — not because it was fashionable,
but because the problems demanded structured solutions.

---

## The Patterns Landscape in 1999

- **Design Patterns (GoF book)** — published 1994, but primarily known
  in the Java/C++ world
- **Patterns of Enterprise Application Architecture (Fowler)** — 2002
- **PHP and OOP** — PHP 3 (1998) had basic classes; PHP 5 (2004) added
  real OOP (interfaces, abstract classes, visibility, static methods)
- **MVC in web frameworks** — Struts (Java, 2000) was one of the first;
  Rails (2004) popularized it for web development

MIOLO implemented these patterns in PHP 3/4 — before the language even
had the constructs to express them properly.

---

## Patterns Found in MIOLO

### 1. Singleton — The Framework Core

**File:** `classes/miolo.class.php`

The `MIOLO` class is a textbook Singleton — but it wasn't always one.

**Version 1.0** used a **global variable** — the PHP 4 approach, since
PHP 4 had no `static` keyword for properties:

```php
// Version 1.0: global variable, no singleton pattern
// $Id: miolo.class,v 1.35 2004/08/31 01:44:23 vgartner Exp $
define('MIOLO_VERSION', '1.0 Final');

class MIOLO
{
    var $theme;       // all properties declared with 'var'
    var $painter;
    var $profile;
    var $uses;

    function MIOLO($home = null, $logname = 'miolo')   // PHP 4 constructor
    {   global $MIOLOCONF;
        $this->login =& $_SESSION['login'];
        // ...
    }
}

// Instantiated globally in startup.inc:
$MIOLO = new MIOLO();
// Accessed everywhere via: global $MIOLO;
```

**Version 2.0** introduced the Singleton pattern, enabled by PHP 5's
`static` keyword:

```php
// Version 2.0.01: proper Singleton
class MIOLO
{
    static private $instance = NULL;

    public static function getInstance()
    {
        if (self::$instance == NULL) {
            self::$instance = new Miolo;
        }
        return self::$instance;
    }
}
```

The evolution from `global $MIOLO` to `MIOLO::getInstance()` tracks
the language's own evolution. The *intent* — a single framework entry
point that everything else hangs off — was the same in both versions.
PHP just couldn't express it cleanly until version 5.

Every subsystem — database, auth, session, page, theme — is accessed
through this singleton. It's the ancestor of Laravel's `app()` container
and Symfony's kernel.

### 2. Service Locator — Lazy Object Resolution

**File:** `classes/miolo.class.php` — `getObject()` method

`getObject()` lazily instantiates named services by prefixing 'M' to the
requested name:

```php
public function getObject($class, $param = NULL)
{
    if (!isset($this->$class)) {
        $className = 'M' . $class;
        $this->$class = new $className($param);
    }
    return $this->$class;
}
```

Usage:

```php
$MIOLO->getObject('log');    // creates MLog on first call, returns cached instance after
$MIOLO->getObject('trace');  // creates MTrace
$MIOLO->getObject('auth');   // creates MAuth
```

This is a **Service Locator** — a registry that resolves service names to
instances. Modern frameworks replaced this with Dependency Injection
containers (Symfony DI 2007, Laravel Container 2011), but the concept
of "ask for a service by name, get an instance" is identical.

### 3. Factory Method — Database Driver Instantiation

The database system creates the correct driver at runtime based on a
configuration string. This pattern evolved significantly between 1.0
and 2.0.

**Version 1.0** — The `Database` factory dynamically constructed the
class name as a string and used PHP 4's string-based instantiation to
create the driver:

```php
// Version 1.0: Database factory
// $Id: database.class,v 1.8 2005/01/21 21:47:05 thomas Exp $
class Database
{
    function Database($conf, $system, $host, $db, $user, $pass)
    {   global $MIOLO;
        $MIOLO->Uses('database/' . $system . '_connection.class');
        // Dynamically builds and executes the instantiation as a string
        // e.g., creates "new PostgresConnection($conf)"
        $this->conn->Open($host, $db, $user, $pass);
    }
}
```

**Version 2.0** — `MDatabase` uses PHP 5's variable class names:

```php
// Version 2.0.01: MDatabase factory
$className = "{$system}Connection"; // "PostgresConnection", "MysqlConnection"
$this->conn = new $className($this);
```

The same pattern is used for transactions and schema introspection:

```php
$className = "{$this->system}Transaction";
$transaction = new $className($this->conn);
```

This is a **Factory Method** — the decision of which class to instantiate
is deferred to runtime based on configuration. In 1.0, PHP 4's limited
OOP required string-based workarounds; in 2.0, PHP 5 let the pattern
be expressed cleanly with `new $className()`.

### 4. Template Method — Convention-Based Overriding

This pattern went through three stages, each reflecting PHP's
evolving OOP capabilities.

**Stage 1: Version 1.0 — No base class at all.** Each database driver
(`PostgresConnection`, `MysqlConnection`, `MssqlConnection`,
`SqliteConnection`) was a standalone class with no parent. They shared
the same method names (`Open`, `Close`, `Execute`, `CreateQuery`)
purely by developer convention. There was no Template Method pattern
yet — just four independent classes that happened to look alike.

**Stage 2: Version 2.0.01 — Empty stubs by convention.** The earliest
SVN 2.0 tag introduced `MConnection` as a plain `class` with empty
stub methods that drivers were expected to override:

```php
// Original MConnection (tag 2.0.01) — NOT abstract
class MConnection
{
    var $db;
    var $id;
    var $traceback = array();
    var $affectedrows;

    // "Virtual methods" — empty stubs, overridden by convention
    public function _connect($dbhost, $LoginDB, $LoginUID, $LoginPWD, $persistent = true, $port='')
    {
    }

    public function _close()
    {
    }

    public function _error()
    {
    }

    public function _execute($sql)
    {
    }

    public function _createquery()
    {
    }

    // Concrete methods that call the "virtual" stubs
    function Open($dbhost, $LoginDB, $LoginUID, $LoginPWD, $persistent = true)
    {
        if ($this->id) {
            $this->Close();
        }
        $this->_connect($dbhost, $LoginDB, $LoginUID, $LoginPWD, $persistent);
        if (!$this->id) {
            $this->traceback[] = "Unable to estabilish DataBase Conection";
        }
        return $this->id;
    }

    function Execute($sql)
    {
        $this->_miolo->LogSQL($sql, false, $this->db->conf);
        if (!($success = $this->_execute($sql))) {
            throw new EDatabaseExecException($this->GetError());
        }
        return $success;
    }
}
```

The concrete PostgreSQL driver then extended this and overwrote the stubs:

```php
// PostgresConnection (tag 2.0.01) — overrides the empty stubs
class PostgresConnection extends MConnection
{
    function _connect($dbhost, $LoginDB, $LoginUID, $LoginPWD, $persistent = true, $port='5432')
    {
        $arg = "host=$dbhost dbname=$LoginDB port=$port user=$LoginUID";
        $this->id = pg_connect($arg);
    }

    function _close()
    {
        pg_close($this->id);
    }

    function _execute($sql)
    {
        pg_send_query($this->id, $sql);
        $rs = pg_get_result($this->id);
        $this->affectedrows = pg_affected_rows($rs);
    }
}
```

**Stage 3: Later 2.x — Language-enforced.** The `abstract class` with
`abstract` methods seen in the current codebase was added during the
PHP 5 migration, when the language finally provided the construct to
enforce what the team had been doing by convention.

This is the Template Method pattern implemented **without language
support** — and then progressively strengthened as the language evolved.
The convention was:

1. The base class defines empty methods prefixed with `_` (underscore)
2. A comment says "Virtual methods — to be implemented by the specific
   drivers"
3. Concrete drivers override them by *convention*, not by contract
4. If a driver forgets to implement `_execute()`, nothing fails at
   class load time — you get silent no-ops at runtime

The three-stage evolution tells an important story: in version 1.0,
there was no abstraction at all — just four standalone classes with
matching interfaces, maintained by developer discipline. In 2.0, the
team introduced a base class with empty stubs, formalizing the shared
interface. Later, the `abstract` keyword made the contract explicit.
The **design intent** evolved from "we'll just keep them consistent"
to "the compiler enforces it."

### 5. Decorator — Form Wrapping

**File:** `classes/ui/controls/mform.class.php`

The docblock explicitly labels `MForm` as a Decorator:

```php
// "This class acts as a Decorator for MBaseForm,
//  adding an outer box for the form."
public function generateInner()
{
    parent::generateInner();
    $body = $this->inner;
    $this->formBox->setControls(array($body, $footer));
    $this->inner = $this->formBox;
}
```

`MForm` wraps `MBaseForm` by adding an outer `MBox` container. The inner
form generates its content, then the decorator wraps it in additional
structure. This is the Decorator pattern from GoF — adding behavior to
an object without modifying its class.

### 6. MVC — The Core Architecture

MIOLO implements the Model-View-Controller pattern as its fundamental
architecture:

| MVC Component | MIOLO Implementation | Files |
|---|---|---|
| **Model** | `MBusiness` classes + `MSQL` query builder | `classes/model/mbusiness.class.php`, `modules/*/db/` |
| **View** | `MTheme` + `MControl` component tree + `MHTMLPainter` | `classes/ui/controls/`, `classes/ui/painter/` |
| **Controller** | `MHandler` dispatcher + `MContext` router | `classes/flow/mhandler.class.php`, `classes/services/mcontext.class.php` |

The request flow:

```
HTTP Request
    → MContext (parse URL → extract module + action)
    → MHandler (dispatch → include handler file)
    → Handler file (uses MBusiness for data, creates MControl objects)
    → MTheme + MHTMLPainter (render controls to HTML)
    → HTTP Response
```

### MHandler: The Controller Dispatcher

**File:** `classes/flow/mhandler.class.php` (version 2.0)

```php
public function dispatch($handler)
{
    global $context, $module, $action, $item, $session, $page,
           $auth, $perms, $navbar, $theme, $history, $self, $url;

    $MIOLO   = $this->manager;
    $context = $this->manager->context;
    $module  = $context->module;
    $action  = $context->action;
    // ...

    $file = $this->path . $handler . '.inc.php';
    if ($return = file_exists($file)) {
        include($file);
    }
}
```

### Version 1.0 Context: Routing Before MVC Was Formalized

**File:** `branches/1.0/trunk/classes/context.class`

The 1.0 `Context` class reveals how raw the early routing was. It read
`module` and `action` directly from `$_REQUEST` and used colon-separated
tokens for nested actions:

```php
class Context
{
    var $module;
    var $action;
    var $item;
    var $actionTokens;

    function Context()   // PHP 4 constructor
    {   global $MIOLOCONF;
        $module = $_REQUEST['module'];
        $action = $_REQUEST['action'];
        $item   = $_REQUEST['item'];
        $this->actionTokens = explode(':', $this->action);
    }

    function ShiftAction()
    {
        return $this->actionTokens[$this->currentToken++];
    }
}
```

### Comparison: MIOLO MVC (2001) vs Laravel MVC (2011)

| Aspect | MIOLO | Laravel |
|---|---|---|
| Router | `MContext` — regex URL parsing | `Route::get()` / `Route::post()` definitions |
| Controller | Include-based handler files | Controller classes with methods |
| Dependency injection | Global singleton + `getObject()` | Service container + constructor injection |
| Request object | Global `$_GET`/`$_POST` + `MContext` vars | `Request $request` injected object |
| Response | Direct `echo` via painter | `Response` object, view rendering |
| Middleware | Hardcoded checks in `dispatch()` | Named middleware stack |
| URL generation | `MContext::composeURL()` | `route('name', params)` helper |
| Module system | Filesystem-based (`modules/$name/`) | Service providers + packages |

The architectural intent is the same. The difference is that Laravel has
the benefit of PHP 5.4+ features (traits, closures, type hints) and 12
years of framework design evolution.

### 7. Visitor — The Painter

**File:** `classes/ui/painter/mhtmlpainter.class.php`

The painter "visits" each control type and produces HTML:

```php
public function div($control) { return "\n<div" . ... . "</div>"; }
public function span($control) { return "<span" . ... . "</span>"; }
public function textField($control) { return "<input type='text'" . ... . "/>"; }
```

This separates the control's logic from its rendering — a Visitor pattern.
You could theoretically write a different painter (e.g., for XML or PDF
output) without modifying the controls themselves.

### 8. Observer / Event System

**File:** `classes/flow/mhandler.class.php`

The dispatch mechanism is an implicit Observer: handler files are
dynamically included based on the action name, and they can set
`$this->forward` to chain to another handler:

```php
do {
    $this->prepare();
    if ($this->tokenOk()) {
        $this->handler();
    }
} while ($this->forward != '');
```

This forward-chaining is conceptually similar to Laravel's middleware
pipeline or event dispatch — one handler can trigger another.

---

## What This Tells Us

The SVN history reveals that MIOLO's patterns evolved in lockstep with
PHP's own maturation. Version 1.0 (preserved in CVS before the SVN
migration) shows the raw starting point: global variables instead of
Singleton, standalone classes instead of Template Method, string-based
instantiation instead of clean Factory Methods, class names without
the `M` prefix (`Database`, `Context`, `Theme`, `UI` vs `MDatabase`,
`MContext`, `MTheme`), and PHP 4 constructors (`function MIOLO()` vs
`__construct()`).

Version 2.0 was a complete architectural rewrite that introduced the
patterns we see today: Singleton via `getInstance()`, Template Method
via empty stub base classes, Factory via dynamic class names, Service
Locator via `getObject()`. The team was reading the GoF book and
applying it to web development as the language gained the constructs
to express these patterns properly.

The MVC implementation is particularly notable because it predates
Struts (Java, 2000) and Rails (Ruby, 2004) — the frameworks that
popularized MVC for web applications. Even the version 1.0 `Context`
class with its colon-separated action tokens (`main:list:detail`)
shows MVC thinking applied directly from software engineering
literature to a web context, before any other PHP framework existed.
