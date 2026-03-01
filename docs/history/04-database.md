# Database: From Raw Drivers to PDO

**Every database driver had to be written from scratch.** There was no
PDO, no Doctrine, no Eloquent. If your application needed to talk to
PostgreSQL, MySQL, Oracle, and SQLite, you wrote four separate abstraction
layers.

MIOLO did exactly this — and in doing so, invented patterns that PDO and
modern ORMs would later standardize.

---

## What Didn't Exist

- **PDO** (PHP Data Objects) — arrived in PHP 5.1 (2005)
- **Doctrine DBAL** — first release 2006, mature by 2010
- **Eloquent ORM** — Laravel, 2011
- **Prepared statements** — not available in PHP until PDO (PHP 5.1, 2005) and MySQLi
- **Migration tools** — Rails migrations (2005) pioneered the concept;
  PHP equivalents came much later (Phinx 2012, Doctrine Migrations 2010)
- **Connection pooling** — not available in PHP's share-nothing model

### PHP 3/4 Database APIs

Each database had its own procedural API with completely different function
signatures:

```php
// PostgreSQL
$conn = pg_connect("host=localhost dbname=mydb");
$result = pg_query($conn, "SELECT * FROM users");
$row = pg_fetch_array($result);

// MySQL
$conn = mysql_connect("localhost", "user", "pass");
mysql_select_db("mydb", $conn);
$result = mysql_query("SELECT * FROM users");
$row = mysql_fetch_array($result);

// Oracle
$conn = OCILogon("user", "pass", "mydb");
$stmt = OCIParse($conn, "SELECT * FROM users");
OCIExecute($stmt);
OCIFetchInto($stmt, $row);
```

Three databases, three completely incompatible APIs. No common interface.
No way to switch databases without rewriting every query call.

---

## How MIOLO Solved It

### Version 1.0: No Base Class at All

**SVN branch:** `branches/1.0` (contains CVS `$Id` tags — this code
predates SVN itself)

In version 1.0, there was **no base connection class**. Each database
driver was a completely standalone class. `PostgresConnection`,
`MysqlConnection`, `MssqlConnection`, and `SqliteConnection` each
implemented `Open()`, `Close()`, `Execute()`, and `CreateQuery()`
independently, with no shared parent:

```php
// Version 1.0: PostgresConnection — standalone, NO inheritance
// $Id: postgres_connection.class,v 1.4 2004/08/23 18:29:44 vgartner Exp $
class PostgresConnection
{
    var $conf;       // name of database configuration
    var $id;         // the connection identifier
    var $traceback;  // a list of transaction errors
    var $level;      // a counter for the transaction level

    function PostgresConnection($conf)   // PHP 4 constructor
    {   global $MIOLO;
        $this->conf = $conf;
        $MIOLO->Uses('database/postgres_query.class');
    }

    function Open($dbhost, $LoginDB, $LoginUID, $LoginPWD, $persistent = true)
    {   global $MIOLO;
        if ($this->id) { Close(); }
        $arg = "host=$dbhost dbname=$LoginDB port=5432 user=$LoginUID password=$LoginPWD";
        $this->id = pg_Connect($arg);
        if (!$this->id) {
            $this->traceback[] = "Unable to estabilish DataBase Conection";
        }
        return $this->id;
    }

    function Close()  { pg_close($this->id); $this->id = 0; }
    function Begin()  { $this->Execute("begin transaction"); $this->level++; }
}
```

```php
// Version 1.0: MysqlConnection — ALSO standalone, same interface by convention
class MysqlConnection
{
    var $conf;
    var $id;
    var $traceback;
    var $level;

    function MysqlConnection($conf)   // PHP 4 constructor
    {   global $MIOLO;
        $this->conf = $conf;
        $MIOLO->Uses('database/mysql_query.class');
    }

    function Open($dbhost, $dbname, $dbuser, $dbpass, $persistent = true)
    {   global $MIOLO;
        $arg = $dbhost . ':' . ($mysql_sock ? $mysql_sock : '/tmp/mysql.sock');
        $this->id = mysql_pconnect($arg, $dbuser, $dbpass);
        $rc = mysql_select_db($dbname, $this->id);
        return $this->id;
    }

    function Close()  { mysql_close($this->id); $this->id = 0; }
}
```

The drivers shared no code. They had the same method names (`Open`,
`Close`, `Execute`, `CreateQuery`) purely by **developer convention** —
there was no interface, no base class, no contract. The directory was
flat: `database/postgres_connection.class`, `database/mysql_connection.class`,
`database/mssql_connection.class`, `database/sqlite_connection.class`.
Note the `.class` extension — not `.class.php`.

The `Database` factory class loaded the correct driver file at runtime
and instantiated it using PHP 4's string-based class construction —
a workaround because variable class names weren't reliable for
direct instantiation:

```php
// Version 1.0: Database factory
// $Id: database.class,v 1.8 2005/01/21 21:47:05 thomas Exp $
class Database
{
    var $conn;

    function Database($conf, $system, $host, $db, $user, $pass)
    {   global $MIOLO;
        $MIOLO->Uses('database/' . $system . '_connection.class');
        // Constructs the driver class name from config and instantiates it
        // at runtime — e.g., creates a new PostgresConnection or MysqlConnection
        // using PHP 4's string-to-class-name mechanism
        $this->conn->Open($host, $db, $user, $pass);
    }
}
```

### Version 2.0: The Base Class Emerges

**SVN tag:** `2.0.01` (the earliest 2.0 release)

The 2.0 rewrite introduced `MConnection` — a base class with empty
stub methods that every driver was expected to override. This was a
complete architectural overhaul: the directory structure changed to
subdirectories (`database/postgres/`, `database/mysql/`), classes gained
the `M` prefix convention, and the constructor moved to PHP 5's
`__construct()`.

```php
// Version 2.0.01: MConnection — a plain class, NOT abstract
// The comment says: "Virtual methods - to be implemented by the specific drivers"
class MConnection
{
    var $db;
    var $id;
    var $traceback = array();
    var $affectedrows;

    function __construct($db)   // PHP 5 constructor
    {
        $this->db = $db;
        $this->_miolo = $this->db->_miolo;
    }

    // "Virtual methods" — empty stubs, overridden by each driver
    public function _connect($dbhost, $LoginDB, $LoginUID, $LoginPWD, $persistent = true, $port='')
    {
    }
    public function _close()    {}
    public function _error()    {}
    public function _execute($sql) {}
    public function _createquery() {}

    // Concrete: the algorithm skeleton that calls the stubs
    function Open($dbhost, $LoginDB, $LoginUID, $LoginPWD, $persistent = true)
    {
        if ($this->id) { $this->Close(); }
        $this->_connect($dbhost, $LoginDB, $LoginUID, $LoginPWD, $persistent);
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

This is the **Template Method** pattern — the base class defines the
algorithm skeleton (`Open`, `Execute`), and concrete drivers implement
the steps (`_connect`, `_execute`). The `abstract` keyword was added
later during the PHP 5 migration; the design intent was always there,
enforced by convention rather than by the language. PDO would later
solve the same problem at the C extension level.

### The Three-Stage Evolution

| Stage | Version | Base class | Constructor | Driver files | Factory |
|---|---|---|---|---|---|
| **Standalone** | 1.0 (2001-2005) | None | PHP 4: `function PostgresConnection()` | Flat: `postgres_connection.class` | String-based instantiation |
| **Convention-based** | 2.0 (earliest tag: 2.0.01) | `MConnection` (plain class, empty stubs) | PHP 5: `__construct()` | Subdirs: `postgres/mconnection.class` | Dynamic class name |
| **Language-enforced** | Later 2.x | `abstract class MConnection` | PHP 5: `__construct()` | Same as 2.0 | Dynamic class name |

The progression mirrors PHP's own evolution: from a language that
couldn't express abstract contracts, through one that could, to one
that enforced them. The design intent was Template Method all along —
the language just hadn't caught up yet.

### Driver Implementations

#### PostgreSQL Driver

**File:** `classes/database/postgres/mconnection.class.php`

Uses `pg_connect()`, `pg_send_query()`, `pg_get_result()` — the native
PostgreSQL functions:

```php
public function _connect($dbhost, $loginDB, $loginUID, $loginPWD, $persistent)
{
    $this->id = pg_connect($arg);
    pg_query($this->id, "SET CLIENT_ENCODING TO '{$encoding}'");
}

public function _execute($sql)
{
    pg_send_query($this->id, $sql);
    $rs = pg_get_result($this->id);
    $this->affectedrows = pg_affected_rows($rs);
}
```

The error handler maps every PostgreSQL SQLSTATE code to a framework
exception — a 500-line switch statement:

```php
switch ($this->getErrorField($result, PGSQL_DIAG_SQLSTATE)) {
    case '23503': // FOREIGN KEY VIOLATION
        throw new MDatabaseException($message, MDatabaseException::KNOWN_ERROR_CODE);
    case '23505': // UNIQUE VIOLATION
        throw new MDatabaseException(
            _M('The identifier already exists on database'), ...
        );
    // ... covers 80+ SQLSTATE codes
}
```

#### MySQL Driver

**File:** `classes/database/mysql/mconnection.class.php`

Uses `mysqli_connect()` / `mysqli_query()`:

```php
public function _connect($dbhost, $loginDB, $loginUID, $loginPWD, $persistent)
{
    $this->id = mysqli_connect($dbhost, $loginUID, $loginPWD, $loginDB, $port);
    mysqli_select_db($this->id, $loginDB);
}

public function _execute($sql)
{
    $rs = mysqli_query($this->id, $sql);
    if ($rs) { mysqli_free_result($rs); }
}
```

#### Oracle Driver

**File:** `classes/database/oracle8/mconnection.class.php`

Uses OCI8 functions — Oracle's PHP extension:

```php
public function _connect($dbhost, $loginDB, $loginUID, $loginPWD, $persistent)
{
    $this->id = $persistent
        ? OCIPLogon($loginUID, $loginPWD, $loginDB)
        : OCILogon($loginUID, $loginPWD, $loginDB);
}

public function _parse($sql)
{
    $statement = oci_parse($this->id, $sql);
    return $statement;
}

public function _bind($stmt, $ph, $pv)
{
    ocibindbyname($stmt, $ph, $pv);
}
```

#### SQLite Driver

**File:** `classes/database/sqlite/mconnection.class.php`

Uses the original `sqlite_open()` — the procedural SQLite1 API, before
SQLite3 existed:

```php
public function _connect($dbhost, $loginDB, $loginUID, $loginPWD, $persistent)
{
    $this->id = sqlite_open($loginDB);
}

public function _execute($sql)
{
    $success = @sqlite_exec($this->id, $sql);
    $this->affectedrows = sqlite_changes($this->id);
}
```

### The Factory: Dynamic Driver Loading

**File:** `classes/database/mdatabase.class.php`

The `MDatabase` class instantiates the correct driver by composing the
class name from a configuration string — a **Factory Method** pattern:

```php
$className = "{$system}Connection"; // "PostgresConnection", "MysqlConnection"
$this->conn = new $className($this);
```

The same pattern is used for transactions and schema introspection:

```php
$className = "{$this->system}Transaction";
$transaction = new $className($this->conn);
```

---

## Comparison: MIOLO Database Layer vs PDO

```php
// MIOLO (2001)
$db = $MIOLO->getDatabase('myapp');
$query = $db->getQuery("SELECT name, email FROM users WHERE active = 't'");
while ($row = $query->fetch()) {
    echo $row['name'];
}

// PDO (2005)
$pdo = new PDO('pgsql:host=localhost;dbname=myapp', 'user', 'pass');
$stmt = $pdo->query("SELECT name, email FROM users WHERE active = 't'");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['name'];
}

// Eloquent (2011)
$users = User::where('active', true)->get();
foreach ($users as $user) {
    echo $user->name;
}
```

| Feature | MIOLO (2001) | PDO (2005) | Eloquent (2011) |
|---|---|---|---|
| Multi-driver | Yes (5 drivers) | Yes (12+ drivers) | Yes (via PDO) |
| Connection | `MDatabase` factory | `new PDO($dsn)` | Config-based |
| Query building | `MSQL` class | Raw SQL | Fluent builder + Eloquent |
| Error handling | Custom exception per SQLSTATE | PDOException | Eloquent exceptions |
| Prepared statements | No | Yes | Yes (transparent) |
| Result fetching | `$query->fetch()` | `$stmt->fetch()` | Collections |
| Transactions | `MTransaction` class | `$pdo->beginTransaction()` | `DB::transaction()` |
| Migrations | None (manual SQL) | None | Artisan migrations |
| Profiling | Built-in (`profileEnter/Exit`) | None (external tools) | Telescope, Debugbar |

---

## The MSQL Query Builder

**File:** `classes/database/msql.class.php`

MIOLO also included a fluent query builder — before "fluent interface"
was even a named pattern (Martin Fowler coined it in 2005):

```php
$sql = new MSQL();
$sql->setColumns('id, name')
    ->setTables('users')
    ->addIlikeCondition('name', '%john%')
    ->addBetweenCondition('age', 18, 65)
    ->addInnerJoin('orders', 'users.id = orders.user_id')
    ->setOrderBy('name DESC')
    ->setLimit(20);

echo $sql->select();
// SELECT id, name FROM users
// INNER JOIN orders ON users.id = orders.user_id
// WHERE name ILIKE '%john%' AND age BETWEEN 18 AND 65
// ORDER BY name DESC LIMIT 20
```

This is the same concept as Laravel's Query Builder:

```php
DB::table('users')
    ->select('id', 'name')
    ->where('name', 'ilike', '%john%')
    ->whereBetween('age', [18, 65])
    ->join('orders', 'users.id', '=', 'orders.user_id')
    ->orderBy('name', 'desc')
    ->limit(20)
    ->get();
```

---

## The Database Landscape in 1999

The databases themselves were different too:

- **PostgreSQL 6.5** (1999) — no schemas, limited query optimizer,
  no `RETURNING` clause, no CTEs, no window functions
- **MySQL 3.23** (1999) — no transactions (InnoDB wasn't default until
  5.5 in 2010), no subqueries, no views, no stored procedures, no
  foreign keys in MyISAM
- **Oracle 8i** (1999) — the enterprise option, expensive, with its own
  procedural language (PL/SQL)
- **SQLite** — didn't exist until 2000; embedded, single-file, no server

MIOLO started with MySQL, migrated to PostgreSQL on Rasmus Lerdorf's
recommendation, and maintained drivers for all of them because different
client institutions used different databases.

Schema changes were applied by hand with SQL scripts. There were no
migration files, no version tracking, no rollback mechanisms. You wrote
a `.sql` file, ran it with `psql`, and documented what you'd done in a
text file or email.

---

## What This Tells Us

MIOLO's database layer evolved through three distinct architectural
stages that mirror PHP's own maturation: standalone drivers with no
shared code (1.0), a convention-based base class with empty stubs
(2.0), and finally a language-enforced abstract class (later 2.x).

The SVN evidence reveals something important: the version 1.0 drivers
(preserved in CVS `$Id` tags dating to 2004, with `.cvsignore` files
confirming CVS predated SVN) had **no abstraction layer at all**. Four
standalone classes implemented the same interface purely by developer
discipline. The `Database` factory dynamically constructed the driver
name as a string — a PHP 4 workaround for what would later become
trivial with `new $className()`.

The 2.0 rewrite was a complete architectural overhaul: base class,
subdirectory structure, `M` prefix naming convention, PHP 5
constructors. The patterns — Template Method, Factory, error mapping —
were introduced here, not in 1.0. PDO would later standardize the same
abstractions at the C extension level.

The 500-line SQLSTATE switch statement in the PostgreSQL driver is
particularly telling: MIOLO's developers read the PostgreSQL error code
documentation and mapped every possible failure mode to a meaningful
exception. This is the kind of work that library maintainers do so
application developers don't have to — and it was done by hand, in
southern Brazil, with PHP 4 and 5.
