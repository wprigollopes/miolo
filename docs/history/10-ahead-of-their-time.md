# Ahead of Their Time: Concepts MIOLO Implemented Before They Had Names

**Many patterns that are now considered standard in web development were
implemented in MIOLO years before they were formalized, named, or
popularized by mainstream frameworks.**

This isn't a claim that MIOLO invented these patterns — most come from
software engineering literature (GoF, Fowler) or other language ecosystems
(Java, Smalltalk). What's remarkable is that a small team in southern
Brazil, working in PHP 3/4 with limited internet access, recognized the
need for these patterns and implemented them in a web context before the
PHP ecosystem caught up.

---

## 1. ORM / Data Mapper (Before Doctrine, Before Eloquent)

**Doctrine ORM** — first stable release 2008
**Eloquent** — Laravel, 2011
**MIOLO Persistence Layer** — implemented ~2004

### MIOLO's Implementation

**Files:** `classes/persistence/persistentobject.class.php`,
`persistentmanager.class.php`, `xmlconfigloader.class.php`

MIOLO implements a full **Data Mapper** ORM with XML class mappings —
the same architecture as Java's Hibernate (2001) and later PHP's
Doctrine 2 (2010).

```php
class PersistentObject
{
    public function retrieve() { $this->manager->retrieveObject($this); }
    public function save()     { $this->manager->saveObject($this); }
    public function delete()   { $this->manager->deleteObject($this); }
    public function getCriteria() { return $this->manager->getRetrieveCriteria($this); }
}
```

The XML configuration maps PHP classes to database tables and attributes
to columns:

```php
// XMLConfigLoader reads mapping files:
// modules/$module/business/map/$name.xml
$cm = new ClassMap($className, $database, $this->broker);
$tableMap->setName((string)$xml->tableName);
$am = new AttributeMap((string)$attr->attributeName, $cm);
$colm = new ColumnMap($attr->columnName, $tableMap, $converter);
```

### Comparison: MIOLO PersistentObject vs Eloquent vs Doctrine

```php
// MIOLO (2004)
$user = new PersistentObject('User');
$user->name = 'John';
$user->save();        // INSERT into mapped table

$user->retrieve();    // SELECT using mapped columns
$user->delete();      // DELETE using mapped primary key

$criteria = $user->getCriteria();
$criteria->addCondition('age', '>', 18);
$results = $criteria->list();

// Eloquent (2011)
$user = new User();
$user->name = 'John';
$user->save();

$user = User::find(1);
$user->delete();

$results = User::where('age', '>', 18)->get();

// Doctrine 2 (2010)
$user = new User();
$user->setName('John');
$em->persist($user);
$em->flush();

$results = $em->getRepository(User::class)
    ->findBy(['age' => ['>', 18]]);
```

| Feature | MIOLO (2004) | Doctrine 2 (2010) | Eloquent (2011) |
|---|---|---|---|
| Pattern | Data Mapper | Data Mapper | Active Record |
| Mapping | XML files | Annotations/XML/YAML | Convention-based |
| Entity class | `PersistentObject` | POPO (Plain PHP Object) | Extends `Model` |
| Query builder | `getCriteria()` | DQL / QueryBuilder | Fluent builder |
| Unit of Work | No | Yes | No |
| Lazy loading | No | Yes | Yes |
| Migrations | No | Yes (Doctrine Migrations) | Yes (Artisan) |

---

## 2. Component-Based UI (Before React, Before Vue)

**React** — 2013
**Vue** — 2014
**Angular** — 2010
**MIOLO UI Controls** — 2001

MIOLO's 140+ UI control classes implement a server-side component model
where each widget encapsulates HTML generation, JavaScript setup, state
management, and validation:

```php
// MIOLO (2001): server-side components
$form = new MForm('userForm');
$form->addField(new MTextField('name', 'Name:', 30));
$form->addField(new MCalendarField('birthdate', 'Birth Date:'));
$form->addField(new MSelection('role', $options, 'Role:'));
$grid = new MDataGrid($query, $columns, $href, 15);
```

```jsx
// React (2013): client-side components
<Form name="userForm">
    <TextField name="name" label="Name:" maxLength={30} />
    <DatePicker name="birthdate" label="Birth Date:" />
    <Select name="role" options={options} label="Role:" />
</Form>
<DataGrid data={data} columns={columns} pageSize={15} />
```

The abstraction is the same: declare components, set properties, compose
them. The rendering target differs (server HTML vs browser DOM), but the
developer experience is remarkably similar.

---

## 3. Middleware Pipeline (Before Express, Before Laravel Middleware)

**Express.js middleware** — 2010
**Laravel middleware** — 2015 (5.0)
**MIOLO request lifecycle** — 2001

**File:** `classes/miolo.class.php`

MIOLO's `handlerRequest()` implements a sequential pipeline:

```php
do {
    $this->prepare();
    if ($this->tokenOk()) {
        $this->handler();
    } else {
        $this->page->onError('Invalid Token; Duplicated Submission');
    }
} while ($this->forward != '');
```

The full lifecycle: initialize, context parsing, login check,
token validation, handler dispatch, page generation, terminate.

This is a middleware pipeline. It's not as flexible as Laravel's named
middleware stack (you can't add/remove middleware dynamically), but the
architectural concept — a chain of processing steps that a request
passes through — is identical.

---

## 4. Template Engine (Before Twig, Before Blade)

**Twig** — 2009
**Blade** — Laravel, 2011
**Smarty** — 2001 (contemporary)
**MIOLO MTemplate** — 2001

**File:** `classes/utils/mtemplate.class.php`

```php
class MTemplate
{
    public function templateFile($file, $vars)
    {
        $str = implode("", file($file));
        $str = preg_replace(array_keys($vars), $vars, $str);
        return $str;
    }
}
```

A minimalist template engine: load a `.tpl` file, replace variables
with regex. Simple, but it separates presentation from logic — the core
principle behind every template engine.

---

## 5. URL Obfuscation / Token Security (Before CSRF Tokens Were Standard)

**OWASP CSRF guidance** — 2003+
**Rails CSRF tokens** — 2004
**MIOLO URL scrambling** — 2001

**File:** `classes/miolo.class.php`

MIOLO implemented RC4 encryption for URL parameters to prevent tampering:

```php
public function scramble($text)
{
    $rc4 = new MRC4Crypt;
    $crypto = base64_encode($rc4->rc4($pwd, $text));
    return urlencode($crypto);
}
```

URLs became opaque tokens (`MIOLO_URI=...`) rather than readable
query strings. Combined with a token validation check in the request
pipeline, this provided both CSRF-like protection and URL tamper
prevention — before CSRF was even named as a vulnerability class.

---

## 6. Custom AJAX Engine (Before jQuery.ajax)

**jQuery** — 2006
**jQuery.ajax()** — 2006
**MIOLO MAjax (via CPaint2)** — 2006

**Files:** `classes/ui/majax.class.php`, `html/scripts/m_ajax.js`

MIOLO adapted the CPaint2 (Cross-Platform Asynchronous INterface Toolkit)
library to create a server-side AJAX responder. PHP methods were registered
as remotely callable, serialized as JSON, and dispatched to a JavaScript
client.

The JavaScript side used Dojo's `dojo.declare` to build the `Miolo.Ajax`
class, which handled sending requests, processing JSON responses, updating
target page elements, and displaying loading indicators and errors.

This is the exact pattern that later became standard: make an HTTP request,
get a response, update part of the page without a full reload. MIOLO
implemented it independently of jQuery, using Dojo as the JavaScript layer.

---

## 7. Query Builder with Fluent Interface (Before Fluent Interfaces Had a Name)

**"Fluent Interface" coined by Martin Fowler** — 2005
**Doctrine QueryBuilder** — 2010
**Laravel Query Builder** — 2011
**MIOLO MSQL** — ~2001

**File:** `classes/database/msql.class.php`

```php
$sql = new MSQL();
$sql->setColumns('id, name')
    ->setTables('users')
    ->addIlikeCondition('name', '%john%')
    ->addBetweenCondition('age', 18, 65)
    ->addInnerJoin('orders', 'users.id = orders.user_id')
    ->setOrderBy('name DESC')
    ->setLimit(20);
```

Method chaining where each setter returns `$this` — the pattern that
Martin Fowler would name "Fluent Interface" four years later.

---

## 8. Entity-Attribute-Value (Custom Fields System)

**EAV pattern** — described in database literature since the 1990s
**WordPress custom fields** — 2004
**MIOLO custom fields** — implemented in MForm

**File:** `classes/ui/controls/mform.class.php`

```php
public function addCustomFields($identifier, $customizedId = NULL, $suffix = '')
{
    $this->mioloCustomFields = MCustomField26::listByIdentifier($identifier);
    // Dynamically generates MTextField, MCalendarField, MSelection, etc.
    // based on field metadata retrieved from the database
}
```

Runtime-extensible forms where field definitions are stored in the
database and rendered dynamically. Users can add fields without code
changes — the same pattern used by WordPress custom fields, Salesforce
custom objects, and every modern form builder.

---

## 9. Plugin / Extension Architecture

**WordPress plugins** — 2004
**Symfony bundles** — 2007
**MIOLO extensions** — ~2004

**File:** `classes/miolo.class.php` — `loadExtensions()`

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

Extensions register their own classes via autoload manifests, are loaded
dynamically at startup, and can extend the framework's capabilities.
This is the same concept as WordPress plugins or Symfony bundles.

---

## 10. Performance Profiling Instrumentation

**Xdebug profiling** — practical ~2008
**Blackfire** — 2014
**Laravel Telescope** — 2018
**MIOLO MProfile** — 2001

**File:** `classes/services/mprofile.class.php`

```php
$this->profileEnter('Database::execute');
// ... do work ...
$this->profileExit('Database::execute');
```

Every database call, file include, and handler dispatch is instrumented
with enter/exit timing. The dump shows exactly how long each phase took.
This is the same data that Blackfire or Xdebug profiling provides, built
in userland PHP.

---

## Summary Timeline

| Concept | MIOLO | First mainstream PHP adoption |
|---|---|---|
| MVC pattern | 2001 | Rails influence ~2005, Symfony/Laravel 2007-2011 |
| Service Locator | 2001 | Symfony DI 2007, Laravel 2011 |
| ORM / Data Mapper | ~2004 | Doctrine 2 (2010) |
| Component-based UI | 2001 | React (2013) |
| Fluent Query Builder | ~2001 | Doctrine QueryBuilder (2010) |
| Middleware pipeline | 2001 | Laravel 5.0 (2015) |
| Custom AJAX | 2006 | jQuery.ajax (2006) — contemporary |
| URL security tokens | 2001 | Rails CSRF (2004) |
| Plugin architecture | ~2004 | WordPress plugins (2004) — contemporary |
| Performance profiling | 2001 | Blackfire (2014) |
| Custom fields (EAV) | ~2010 | WordPress custom fields (2004) |

---

## What This Tells Us

MIOLO didn't invent these patterns — they come from software engineering
literature and other language ecosystems. But the team recognized their
applicability to web development and implemented them in PHP years before
the PHP framework ecosystem formalized them.

The SVN history (with CVS artifacts in version 1.0 confirming an even
earlier origin) shows that many of these patterns weren't present in the
first version. Version 1.0 was simpler — standalone database drivers,
global variables, minimal UI abstraction, Portuguese error messages.
The 2.0 rewrite was where the architectural ambition crystallized:
Singleton, Template Method, Factory, component hierarchy, Service
Locator, and the full MVC pipeline. This suggests the team was actively
studying design patterns between versions and applying them as PHP 5
made proper OOP possible.

This is the story of many early frameworks: the good ideas were always
there, scattered across academic papers and enterprise Java. What
Rails, Django, Symfony, and Laravel did was package them into accessible,
well-documented frameworks. MIOLO was doing the same packaging — earlier,
in a less connected environment, for a smaller audience.
