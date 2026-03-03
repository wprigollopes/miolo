# Database Access (DAO)

[Back to Index](index.md)

## Overview

The DAO layer abstracts relational database access, providing a single programming interface
regardless of the DBMS.

**Features:**
- Unified programming interface across all supported databases
- Automatic SQL generation adapted to the target DBMS (JOINs, offsets, max rows)
- Sequence (generator) support
- Standard date/time format conversion
- Native transaction support
- ResultSet abstraction with browse, pagination, filtering, and sorting

**Supported DBMS:** PostgreSQL, MySQL, Oracle, Firebird, SQLite, MSSQL, ODBC

## Key Concepts

| Concept | Description |
|---------|-------------|
| **Field** | A table column |
| **Record** | A table row |
| **ResultSet** | 2D array returned from a query (0-based column indices) |
| **DataSet** | `MDataSet` instance wrapping a ResultSet for navigation |

## Basic Workflow

```php
global $MIOLO;

// 1. Get database connection
$db = $MIOLO->getDatabase('admin');

// 2. Build SQL
$sql = new MSQL('*', 'miolo_transaction');

// 3. Execute query
$query = $db->getQuery($sql);

// 4. Work with results
$n = $query->getRowCount();
$result = $query->result;  // 2D array
```

## MSQL -- Building Queries

### Constructor

```php
new MSQL($columns, $tables, $where, $orderBy, $groupBy, $having, $forUpdate)
```

### Simple Queries

```php
// Select all from table
$sql = new MSQL('*', 'miolo_transaction');

// Select specific columns
$sql = new MSQL('idtrans, name', 'miolo_transaction');

// With WHERE
$sql = new MSQL('*', 'miolo_transaction', 'idtrans = 100');

// With ORDER BY
$sql = new MSQL('*', 'miolo_transaction', '', 'name');
```

### Building WHERE Clauses

```php
$sql = new MSQL('*', 'miolo_transaction');
$sql->setWhere('idtrans = 100');
$sql->setWhereAnd("name LIKE 'S%'");
$sql->setWhereOr("name LIKE 'A%'");
```

### Parameterized Queries

```php
// Single parameter
$sql = new MSQL('*', 'miolo_transaction', 'idtrans = ?');
$sql->setParameters(100);

// Multiple parameters
$sql = new MSQL('*', 'miolo_transaction', "(idtrans = ?) AND (name LIKE ?)");
$sql->setParameters(100, 'A%');

// Or as array
$sql->setParameters(array(100, 'A%'));
```

### JOINs

```php
// INNER JOIN with aliases
$sql = new MSQL('s.system, t.trans', '', '', 's.system, t.trans');
$sql->setJoin('miolo_system s', 'miolo_transaction t', '(s.idsystem = t.idsystem)');

// LEFT JOIN with 3 tables and GROUP BY
$sql = new MSQL('s.system, t.trans, count(a.idgroup)', '', '',
    's.system, t.trans', 's.system, t.trans');
$sql->setLeftJoin('miolo_system s', 'miolo_transaction t', '(s.idsystem = t.idsystem)');
$sql->setLeftJoin('miolo_transaction t', 'miolo_access a', '(t.idtrans = a.idtrans)');

// RIGHT JOIN
$sql->setRightJoin('table1', 'table2', '(table1.id = table2.id)');
```

### HAVING Clause

```php
$sql = new MSQL('s.system, count(t.idtrans)', '', '', 's.system', 's.system',
    '(count(t.idtrans) > 3)');
$sql->setLeftJoin('miolo_system s', 'miolo_transaction t', '(s.idsystem = t.idsystem)');
```

### Subqueries

Use the `:` prefix to prevent value quoting:

```php
$sql = new MSQL('a.idgroup', 'miolo_access a');
$sql->setWhere('a.idtrans IN ?');
$sqlx = new MSQL('t.idtrans', 'miolo_transaction t', '(t.idsystem = 1)');
$sql->setParameters(':(' . $sqlx->select() . ')');
```

### DML Operations

```php
// INSERT
$args = array($id, 'Transaction Name', 1);
$cmd = $sql->insert($args);

// UPDATE
$cmd = $sql->update($args);

// DELETE
$cmd = $sql->delete($args);

// INSERT from subquery
$cmd = $sql->insertFrom($subSql);
```

### Generating SQL Strings

```php
$selectStr = $sql->select();     // SELECT ...
$insertStr = $sql->insert($args); // INSERT INTO ...
$updateStr = $sql->update($args); // UPDATE ...
$deleteStr = $sql->delete($args); // DELETE FROM ...
```

## MDatabase -- Executing Queries

### Getting a Connection

```php
$db = $MIOLO->getDatabase('admin');  // 'admin' = config key in miolo.conf <db>
```

### Execute and Query

```php
// SELECT -- returns MQuery
$query = $db->getQuery($sql);

// DML (INSERT/UPDATE/DELETE)
$ok = $db->execute($sql->insert($args));

// Count records
$n = $db->count($sql);

// Get all data from table
$data = $db->getTable('miolo_user');
```

### Sequences

```php
$id = $db->getNewId('seq_miolo_transaction');
```

### Date Conversion

```php
$dbDate = $db->charToDate('25/12/2024', 'DD/MM/YYYY');
$dbTs   = $db->charToTimestamp('25/12/2024 14:30:00', 'DD/MM/YYYY HH24:MI:SS');
$strDate = $db->dateToChar($dateField, 'DD/MM/YYYY');
$strTs   = $db->timestampToChar($tsField, 'DD/MM/YYYY HH24:MI:SS');
```

## Transactions

### Array-based Transaction (Implicit)

Passing an array to `execute()` wraps it in a transaction:

```php
$id = $db->getNewId('seq_miolo_transaction');
$sql = new MSQL('idtrans, name, idsystem', 'miolo_transaction');
$cmd = array();
$cmd[] = $sql->insert(array($id, 'Test', 1));

$sql2 = new MSQL('idtrans, idgroup, rights', 'miolo_access');
$cmd[] = $sql2->insert(array($id, 1, 1));
$cmd[] = $sql2->insert(array($id, 2, 1));

$ok = $db->execute($cmd);  // array triggers transaction with auto-commit/rollback
```

### MTransaction (Explicit)

```php
$transaction = $db->getTransaction();
$transaction->addCommand($sql1->insert($args1));
$transaction->addCommand($sql2->insert($args2));
$ok = $transaction->process();  // commit or rollback
if (!$ok) {
    $error = $transaction->getError();
}
```

### Business Layer Transactions

```php
$business->beginTransaction();
try {
    $business->save();
    $business->endTransaction();  // commit
} catch (Exception $e) {
    $business->endTransaction();  // rollback (errors exist)
}
```

## MQuery -- Working with Results

### Accessing the ResultSet

```php
$query = $db->getQuery($sql);

// Direct array access
$result = $query->result;  // 2D array
echo $result[0][1];        // row 0, column 1

// Row count
$n = $query->getRowCount();

// Column info
$colCount = $query->getColumnCount();
$colName  = $query->getColumnName(0);
$colNum   = $query->getColumnNumber('name');
$colNames = $query->getColumnNames();
```

### Navigation (Browse)

```php
$query->moveFirst();
while (!$query->eof()) {
    echo $query->fields('name') . '<br>';
    $query->moveNext();
}

// Other navigation
$query->moveLast();
$query->movePrev();
$query->moveTo(5);

// Current record values
$row    = $query->getRowValues();    // numeric array
$fields = $query->getFieldValues();  // associative array
$obj    = $query->getRowObject();    // object with column names as properties
$value  = $query->getValue('name');  // single field
```

### Pagination

**Method 1: QueryRange**
```php
$range = new MQueryRange($page, 5);  // page number, rows per page
$result = $db->queryRange($sql->select(), $range);
$total = $range->total;
```

**Method 2: MSQL SetRange**
```php
$sql->setRange($page, 5);
$query = $db->getQuery($sql);
```

**Method 3: Query object**
```php
$query->setPageLength(10);
$pageCount = $query->getPageCount();
for ($page = 1; $page <= $pageCount; $page++) {
    $pageData = $query->getPage($page);
}
```

### Post-Query Sorting

```php
$query->setOrder('name');  // sort ResultSet in PHP
```

### Post-Query Filtering

Operators: `=`, `!=`, `like`, `regex`

```php
$query->addFilter('system', 'like', 'C%');
$query->applyFilter();  // irreversible

// Multiple filters (AND/OR)
$query->addFilter('system', 'like', 'C%');
$query->addFilter('name', '!=', 'Admin', 'OR');
$query->applyFilter();

// Regex filter
$query->addFilter('system', 'regex', '^(.*?)A(.*)');
$query->applyFilter();

// Check if filtered
if ($query->isFiltered()) { ... }
```

### Condensing Results

```php
// Two columns to associative array (key => value)
$assoc = $query->chunkResult(0, 1);  // col 0 = key, col 1 = value

// Display keys in values
$assoc = $query->chunkResult(0, 1, true);  // "key - value"

// Multiple columns to string or array
$result = $query->chunkResultMany($keyCol, array($val1, $val2), 'S', ', ');
$result = $query->chunkResultMany($keyCol, array($val1, $val2), 'A');

// Tree structure
$tree = $query->treeResult($groupCol, $nodeCol);
```

### Export to CSV

```php
$query->getCSV('export.csv');
```

## MBusiness -- Database Access

Business classes provide a higher-level interface:

```php
$business = $MIOLO->getBusiness('mymodule', 'course');

// Query (returns MQuery)
$query = $business->query($sql, $parameters, $maxrows);

// Execute DML
$business->execute($sql, $parameters);

// Execute batch in transaction
$business->executeBatch($cmdArray);

// Stored procedure
$business->executeSP($sql, $parameters);

// Affected rows
$n = $business->getAffectedRows();

// Error handling
if ($business->checkError()) {
    $errors = $business->getErrors();
}
```
