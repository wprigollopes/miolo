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

The `MIOLO` class is a textbook Singleton — a single instance that serves
as the application's entry point:

```php
class MIOLO
{
    static private $instance = NULL;

    public static function getInstance()
    {
        if (self::$instance == NULL) {
            self::$instance = new MIOLO();
        }
        return self::$instance;
    }
}
```

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

**File:** `classes/database/mdatabase.class.php`

The database system creates the correct driver by composing the class
name from a configuration string:

```php
$className = "{$system}Connection"; // "PostgresConnection", "MysqlConnection"
$this->conn = new $className($this);
```

The same pattern is used for transactions and schema introspection:

```php
$className = "{$this->system}Transaction";
$transaction = new $className($this->conn);
```

This is a **Factory Method** — the decision of which class to instantiate
is deferred to runtime based on configuration.

### 4. Template Method — Abstract Connection

**File:** `classes/database/mconnection.class.php`

`MConnection` defines the algorithm skeleton for database operations
while deferring steps to concrete subclasses:

```php
abstract class MConnection
{
    // Template: defines the interface
    abstract public function _connect($dbhost, $loginDB, $loginUID, $loginPWD, $persistent);
    abstract public function _close();
    abstract public function _error();
    abstract public function _execute($sql);
    abstract public function _createquery();

    // Concrete: uses the abstract steps
    public function open($conf)
    {
        $this->_connect($conf['host'], $conf['name'], $conf['user'], $conf['password']);
        // ...
    }
}
```

Five concrete implementations (PostgreSQL, MySQL, Oracle, SQLite, MsSQL)
fill in the abstract methods. The base class handles the common logic.

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

**File:** `classes/flow/mhandler.class.php`

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

MIOLO implemented Singleton, Factory, Template Method, Decorator, MVC,
Service Locator, and Visitor patterns in PHP 3/4 — a language that didn't
even have abstract classes or interfaces. The team was reading the GoF
book and applying it to web development at a time when most PHP code was
procedural spaghetti.

The MVC implementation is particularly notable because it predates Struts
(Java, 2000) and Rails (Ruby, 2004) — the frameworks that popularized
MVC for web applications. MIOLO didn't copy the pattern from another web
framework; it applied it directly from software engineering literature to
a web context.
