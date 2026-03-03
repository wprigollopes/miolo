# Object Persistence

[Back to Index](index.md)

## Overview

The persistence layer provides transparent object-relational mapping for `MBusiness` objects.
Based on the works of Scott W. Ambler and Artyom Rudoy.

**Features:**
- Business classes inherit from `PersistentObject` via `MBusiness`
- Methods: `save()`, `delete()`, `retrieve()` with automatic SQL generation
- Multi-object operations via `MQuery` or `Cursor` (array of objects)
- Lazy read via proxies (retrieve only `proxy=true` attributes)
- Association support: oneToOne, oneToMany, manyToMany with automatic cascade
- Inheritance support (map inheritance tree to DB schema)
- Transaction support, automatic OID generation
- Value conversion classes

## XML Mapping

Map files are named `<class_name>.xml` and located in `<module>/classes/map/`.

### Basic Structure

```xml
<?xml version="1.0" encoding="ISO-8859-1" standalone="yes"?>
<map>
    <moduleName>mymodule</moduleName>
    <className>course</className>
    <tableName>tb_course</tableName>
    <databaseName>admin</databaseName>

    <attribute>
        <attributeName>idCourse</attributeName>
        <columnName>id_course</columnName>
        <key>primary</key>
        <idgenerator>seq_course</idgenerator>
    </attribute>

    <attribute>
        <attributeName>name</attributeName>
        <columnName>course_name</columnName>
        <proxy>true</proxy>
    </attribute>
</map>
```

### Attribute Elements

| Element | Required | Description |
|---------|----------|-------------|
| `<attributeName>` | Yes | Attribute name in the PHP class |
| `<columnName>` | No | Column name in the table (omit for pure association attributes) |
| `<key>` | No | `primary` or `foreign` |
| `<idgenerator>` | No | Sequence name for auto ID generation |
| `<proxy>` | No | If `true`, included in proxy retrievals (default: `true`) |
| `<reference>` | No | For inheritance: superclass attribute referenced |
| `<attributeIndex>` | No | For indexed attributes (arrays from multiple columns) |

### Association Elements

```xml
<association>
    <toClassModule>mymodule</toClassModule>
    <toClassName>student</toClassName>
    <cardinality>oneToMany</cardinality>
    <target>students</target>
    <retrieveAutomatic>true</retrieveAutomatic>
    <saveAutomatic>false</saveAutomatic>
    <deleteAutomatic>false</deleteAutomatic>
    <inverse>true</inverse>
    <entry>
        <fromAttribute>idCourse</fromAttribute>
        <toAttribute>idCourse</toAttribute>
    </entry>
    <orderAttribute>
        <orderAttributeName>name</orderAttributeName>
        <orderAttributeDirection>ascend</orderAttributeDirection>
    </orderAttribute>
</association>
```

| Element | Description |
|---------|-------------|
| `<toClassModule>` | Module of associated class |
| `<toClassName>` | Associated class name |
| `<cardinality>` | `oneToOne`, `oneToMany`, `manyToMany` |
| `<target>` | Attribute receiving associated object(s); identifies the association |
| `<retrieveAutomatic>` | Auto-cascade on retrieve (default: `false`) |
| `<saveAutomatic>` | Auto-cascade on save (default: `false`) |
| `<deleteAutomatic>` | Auto-cascade on delete (default: `false`) |
| `<joinAutomatic>` | `inner`, `left`, `right` for simultaneous SQL JOIN |
| `<inverse>` | Whether direction is inverse to FK->PK (default: `false`) |
| `<entry>` | FK mapping: `<fromAttribute>` and `<toAttribute>` (multiple allowed) |
| `<orderAttribute>` | Sort associated objects: `<orderAttributeName>` + `<orderAttributeDirection>` |
| `<indexAttribute>` | Use attribute values as array indices (1:N, N:N) |

### ManyToMany Associations

Require an associative class:

```xml
<association>
    <toClassModule>mymodule</toClassModule>
    <toClassName>group</toClassName>
    <associativeClassModule>mymodule</associativeClassModule>
    <associativeClassName>groupuser</associativeClassName>
    <cardinality>manyToMany</cardinality>
    <target>groups</target>
    <direction>
        <fromAttribute>users</fromAttribute>
        <toAttribute>groups</toAttribute>
    </direction>
</association>
```

### Inheritance

Use `<extends>` to map class inheritance across tables:

```xml
<map>
    <moduleName>mymodule</moduleName>
    <className>student</className>
    <tableName>ga_student</tableName>
    <databaseName>admin</databaseName>
    <extends>
        <moduleName>mymodule</moduleName>
        <className>person</className>
    </extends>
    <attribute>
        <attributeName>number</attributeName>
        <columnName>enrollment</columnName>
        <key>primary</key>
    </attribute>
    <attribute>
        <attributeName>idperson</attributeName>
        <columnName>id_person</columnName>
        <reference>idperson</reference>    <!-- links to parent class -->
    </attribute>
</map>
```

### Value Converters

Define in `<converter>` within an attribute:

```xml
<attribute>
    <attributeName>name</attributeName>
    <columnName>nome</columnName>
    <converter>
        <converterName>CaseConverter</converterName>
        <parameter>
            <parameterName>case</parameterName>
            <parameterValue>upper</parameterValue>
        </parameter>
    </converter>
</attribute>
```

**IConverter interface:**

```php
class CaseConverter implements IConverter
{
    private $case;

    function init($properties)
    {
        $this->case = $properties['case'];
    }

    function convertFrom($object)    // DB -> attribute
    {
        return ($this->case == 'upper') ? strtoupper($object) : strtolower($object);
    }

    function convertTo($object)      // attribute -> DB
    {
        return strtoupper($object);
    }

    function convertColumn($object)  // SQL SELECT column construction
    {
        return $object;
    }

    function convertWhere($object)   // SQL WHERE clause construction
    {
        return $object;
    }
}
```

Pre-defined converters: `TrivialConverter` (no conversion), `CaseConverter` (upper/lower).

---

## Complete Mapping Example

### USER class

```xml
<map>
   <moduleName>persistence</moduleName>
   <className>user</className>
   <tableName>cm_usuario</tableName>
   <databaseName>admin</databaseName>

   <attribute>
       <attributeName>iduser</attributeName>
       <columnName>idusuario</columnName>
       <key>primary</key>
       <idgenerator>seq_cm_usuario</idgenerator>
   </attribute>
   <attribute>
       <attributeName>login</attributeName>
       <columnName>login</columnName>
       <proxy>true</proxy>
   </attribute>
   <attribute>
       <attributeName>password</attributeName>
       <columnName>password</columnName>
   </attribute>
   <attribute><attributeName>person</attributeName></attribute>
   <attribute><attributeName>groups</attributeName></attribute>
   <attribute><attributeName>sector</attributeName></attribute>

   <!-- oneToOne: user -> person -->
   <association>
       <toClassModule>persistence</toClassModule>
       <toClassName>person</toClassName>
       <cardinality>oneToOne</cardinality>
       <target>person</target>
       <retrieveAutomatic>true</retrieveAutomatic>
       <saveAutomatic>true</saveAutomatic>
       <entry>
           <fromAttribute>idperson</fromAttribute>
           <toAttribute>idperson</toAttribute>
       </entry>
   </association>

   <!-- manyToMany: user <-> group (via groupuser) -->
   <association>
       <toClassModule>persistence</toClassModule>
       <toClassName>group</toClassName>
       <associativeClassModule>persistence</associativeClassModule>
       <associativeClassName>groupuser</associativeClassName>
       <cardinality>manyToMany</cardinality>
       <target>groups</target>
       <direction>
           <fromAttribute>users</fromAttribute>
           <toAttribute>groups</toAttribute>
       </direction>
   </association>
</map>
```

---

## PersistentObject Methods

Since `MBusiness` inherits from `PersistentObject`, all business objects have:

```php
// Retrieve
$obj->retrieve();                                    // fill from DB by primary key
$obj->retrieveAsProxy();                             // fill only proxy=true attributes
$obj->retrieveFromQuery($query);                     // fill from MQuery first record
$obj->retrieveFromCriteria($criteria);               // fill from criteria result
$obj->retrieveAssociation('students');               // fill association attribute
$cursor = $obj->retrieveAssociationAsCursor('students');

// Save
$obj->save();                                        // INSERT or UPDATE
$obj->saveAssociation('students');                   // persist association data

// Delete
$obj->delete();                                      // remove from DB
$obj->deleteAssociation('students', $studentObj);    // remove association

// Status
$obj->isPersistent();    // true if loaded from DB (UPDATE on save)
$obj->setPersistent($v); // force INSERT vs UPDATE
$obj->isProxy();         // true if only proxy attributes loaded
$obj->setProxy($v);
```

## Cursor

A cursor returns objects one at a time from a query:

```php
$cursor = $user->proxyCursorByLogin('2146');
while ($obj = $cursor->getObject()) {
    echo "ID: " . $obj->iduser;
    echo "Login: " . $obj->login;
}
```

## RetrieveCriteria -- Query by Criteria

All persistence queries use `RetrieveCriteria` obtained via `getCriteria()`.

### Simple Query

```php
$criteria = $group->getCriteria();
$query = $criteria->retrieveAsQuery();
// SQL: SELECT cm_group.idgroup, cm_group.name FROM cm_group
```

### Retrieval Methods

```php
$query  = $criteria->retrieveAsQuery($params);       // MQuery
$cursor = $criteria->retrieveAsCursor($params);       // Cursor (objects)
$query  = $criteria->retrieveAsProxyQuery($params);   // proxy fields only
$cursor = $criteria->retrieveAsProxyCursor($params);  // proxy cursor
```

### Filtering (WHERE)

Operators: `=`, `<>`, `>`, `<`, `>=`, `<=`, `LIKE`, `IN`

```php
// Simple filter
$criteria->addCriteria('name', 'LIKE', "'SIGA%'");

// Multiple AND conditions
$criteria->addCriteria('name', 'LIKE', "'C%'");
$criteria->addCriteria('active', '=', '1');

// OR condition
$criteria->addOrCriteria('name', 'LIKE', "'A%'");

// Compound conditions with CriteriaCondition
$cc = new CriteriaCondition;
$cc->addCriteria($criteria->getCriteria('name', 'LIKE', "'%A%'"));
$cc->addOrCriteria($criteria->getCriteria('name', 'LIKE', "'%E%'"));
$criteria->addCriteria('name', 'LIKE', "'C%'");
$criteria->addCriteria($cc);
// SQL: WHERE (name LIKE 'C%') AND ((name LIKE '%A%') OR (name LIKE '%E%'))
```

### Parameters

```php
$criteria->addCriteria('name', 'LIKE', '?');
$query = $criteria->retrieveAsQuery('A%');
```

### IN Operator

```php
$values = array('ADMIN', 'MANAGER', 'EDITOR');
$criteria->addCriteria('role', 'IN', $values);
// SQL: WHERE (role IN ('ADMIN', 'MANAGER', 'EDITOR'))
```

### Ordering

```php
$criteria->addOrderAttribute('name');              // ASC
$criteria->addOrderAttribute('date', false);       // DESC
```

### Column Selection

```php
$criteria->addColumnAttribute('login');
$criteria->addColumnAttribute('sector.nick');      // navigate associations
```

### Joins via Associations

```php
// oneToOne join (navigating user -> sector)
$criteria = $user->getCriteria();
$criteria->addCriteria('sector.nick', 'LIKE', "'PROR%'");
$query = $criteria->retrieveAsProxyQuery();
// SQL: ... FROM cm_user, cm_sector WHERE ... AND (cm_user.idsector = cm_sector.idsector)

// manyToMany join (navigating user -> groups)
$criteria->addCriteria('groups.name', 'LIKE', "'ADMIN%'");
// SQL: ... FROM cm_groupuser, cm_user, cm_group WHERE ...

// Path join (navigating multiple levels: group -> users -> sector)
$criteria->addColumnAttribute('users.sector.nick');
$criteria->addCriteria('users.sector.nick', '=', "'HQ'");
```

### GROUP BY and HAVING

```php
$criteria->addColumnAttribute('name');
$criteria->addColumnAttribute('count(users.iduser)');
$criteria->addGroupAttribute('name');
$criteria->addHavingCriteria('count(users.iduser)', '>', '50');
$criteria->addOrHavingCriteria('count(users.iduser)', '<', '5');
```

### Aliases

```php
$criteria->setAlias('G');
$criteria->addColumnAttribute('G.idgroup');
// SQL: SELECT G.idgroup, G.name FROM cm_group G

// Association alias
$criteria->setAssociationAlias('users', 'U');
$criteria->addColumnAttribute('U.iduser');

// Self-join
$criteria->setAutoAssociationAlias('S1', 'S2');
$criteria->addCriteria('S1.nick', '=', 'S2.parent');
// SQL: ... FROM cm_sector S1, cm_sector S2 WHERE ...
```

### Subqueries

```php
$subCriteria = $user->getCriteria();
$subCriteria->addCriteria('sector.nick', 'LIKE', "'PROR%'");
$subCriteria->addColumnAttribute('iduser');

$criteria = $user->getCriteria();
$criteria->addCriteria('iduser', 'IN', $subCriteria);
// SQL: ... WHERE (iduser IN (SELECT iduser FROM ...))
```

**Correlated subquery:**

```php
$subCriteria = $user->getCriteria();
$subCriteria->setReferenceAlias('S');
$subCriteria->addColumnAttribute('count(iduser)');
$subCriteria->addCriteria('idsector', '=', 'S.idsector');

$criteria = $sector->getCriteria();
$criteria->setAlias('S');
$criteria->addCriteria($subCriteria, '>', '150');
```

### Join Types

```php
$criteria->setAssociationType('users', 'right');  // 'inner', 'left', 'right'
```

### DISTINCT

```php
$criteria->setDistinct(true);
```

### Inheritance

Automatically joins parent/child tables:

```php
$criteria = $student->getCriteria();
$criteria->addColumnAttribute('number');
$criteria->addColumnAttribute('name');  // from parent class (person)
// SQL: ... FROM ga_student, cm_person WHERE ... AND (ga_student.idperson = cm_person.idperson)
```
