# API Reference

[Back to Index](index.md)

Quick reference of method signatures for all major MIOLO classes.

## MIOLO (Main Class)

| Method | Signature | Description |
|--------|-----------|-------------|
| `_REQUEST` | `($vars, $from = 'ALL')` | Get variable from `$_REQUEST`/`$_SESSION`/`$_GLOBAL` |
| `assert` | `($cond, $msg, $goto)` | Halt execution if condition is true |
| `checkAccess` | `($trans, $access, $deny)` | Check user access permission |
| `checkLogin` | `()` | Check if user is logged in |
| `confirmation` | `($msg, $gotoOK, $gotoCancel, $eventOk, $eventCancel, $halt)` | Show confirmation prompt |
| `error` | `($msg, $goto, $caption, $event, $halt)` | Show error prompt |
| `getAbsolutePath` | `($rel)` | Absolute path from MIOLO directory |
| `getAbsoluteURL` | `($rel, $module)` | Complete URL for file |
| `getActionURL` | `($module, $action, $item, $args, $dispatch, $scramble)` | URL for handler execution |
| `getAuth` | `()` | Get MAuth reference |
| `getBusiness` | `($module, $name, $data)` | Get/create business object |
| `getBusinessMAD` | `($name, $data)` | Get MAD module business object |
| `getConf` | `($key)` | Get configuration value |
| `getContext` | `($url, $style, $scramble)` | Get MContext reference |
| `getCurrentAction` | `()` | Get current URL action |
| `getCurrentModule` | `()` | Get current URL module |
| `getCurrentURL` | `()` | Get current URL |
| `getDatabase` | `($conf, $user, $pass)` | Get MDatabase reference |
| `getInstance` | `()` | Get MIOLO singleton |
| `getLogin` | `()` | Get MLogin reference |
| `getModulePath` | `($module, $file)` | Absolute path for module file |
| `getPage` | `()` | Get MPage reference |
| `getPerms` | `()` | Get MPerms reference |
| `getSession` | `()` | Get MSession reference |
| `getSysTime` | `()` | Current timestamp `'d/m/Y H:i:s'` |
| `getTheme` | `($themeId, $layout)` | Get MTheme reference |
| `getUI` | `()` | Get MUI reference |
| `import` | `($namespace, $class)` | Register class for lazy loading |
| `information` | `($msg, $goto, $event, $halt)` | Show info prompt |
| `invokeHandler` | `($module, $action)` | Execute handler |
| `logError` | `($error, $conf)` | Write to error log |
| `logMessage` | `($msg)` | Write to message log |
| `logSQL` | `($sql, $force, $conf)` | Write SQL to log |
| `prompt` | `($prompt, $halt)` | Show prompt |
| `question` | `($msg, $gotoYes, $gotoNo, $eventYes, $eventNo, $halt)` | Show question prompt |
| `setConf` | `($key, $value)` | Modify configuration value |
| `setLog` | `($logname)` | Set log filename |
| `trace` | `($msg, $file, $line)` | Send trace message |
| `uses` | `($name, $module)` | Include file |
| `usesBusiness` | `($module, $name)` | Include business class file |

## MBusiness

| Method | Signature | Description |
|--------|-----------|-------------|
| `beginTransaction` | `()` | Start transaction |
| `checkError` | `()` | Has errors? |
| `endTransaction` | `()` | Commit/rollback |
| `execute` | `($sql, $parameters)` | Execute DML |
| `executeBatch` | `($cmds)` | Execute array in transaction |
| `executeSP` | `($sql, $parameters)` | Execute stored procedure |
| `getAffectedRows` | `()` | Rows affected by last SQL |
| `getBusiness` | `($module, $name, $data)` | Get business object |
| `getById` | `($data)` | Default retrieve by ID |
| `getData` | `()` | Get transfer object |
| `getDatabase` | `($database)` | Set database connection |
| `getDb` | `()` | Get MDatabase |
| `getErrors` | `()` | Get error array |
| `log` | `($operation, $description)` | Log message |
| `onCreate` | `($data)` | Called after construction |
| `query` | `($sql, $parameters, $maxrows)` | Execute query, return MQuery |
| `setData` | `($data)` | Copy attributes from object |

## MDatabase

| Method | Signature | Description |
|--------|-----------|-------------|
| `charToDate` | `($date, $format)` | String to DB date |
| `charToTimestamp` | `($ts, $format)` | String to DB timestamp |
| `close` | `()` | Close connection |
| `count` | `($sql)` | Count records |
| `dateToChar` | `($date, $format)` | DB date to string |
| `execute` | `($sql)` | Execute DML |
| `executeBatch` | `($sql_array)` | Execute in transaction |
| `getAffectedRows` | `()` | Affected rows |
| `getErrors` | `()` | Error list |
| `getNewId` | `($sequence, $table)` | Next sequence value |
| `getQuery` | `($sql, $maxrows)` | Execute, return MQuery |
| `getTable` | `($tablename)` | Select * from table |
| `getTransaction` | `()` | Get MTransaction |
| `timestampToChar` | `($ts, $format)` | DB timestamp to string |

## MSQL

| Method | Signature | Description |
|--------|-----------|-------------|
| `__construct` | `($cols, $tables, $where, $orderBy, $groupBy, $having, $forUpdate)` | Build SQL |
| `clear` | `()` | Clear all attributes |
| `delete` | `($params)` | Generate DELETE |
| `insert` | `($params)` | Generate INSERT |
| `insertFrom` | `($sql)` | INSERT from subquery |
| `select` | `($params)` | Generate SELECT |
| `update` | `($params)` | Generate UPDATE |
| `setColumns` | `($string, $distinct)` | Set columns |
| `setDb` | `($db)` | Set database for DBMS-specific syntax |
| `setGroupBy` | `($string)` | GROUP BY |
| `setHaving` | `($string)` | HAVING |
| `setJoin` | `($t1, $t2, $cond, $type)` | JOIN |
| `setLeftJoin` | `($t1, $t2, $cond)` | LEFT JOIN |
| `setRightJoin` | `($t1, $t2, $cond)` | RIGHT JOIN |
| `setOrderBy` | `($string)` | ORDER BY |
| `setParameters` | `($arg, ...)` | Set parameter values |
| `setRange` | `($page, $rows)` | Set pagination |
| `setTables` | `($string)` | Set tables |
| `setWhere` | `($string)` | Set WHERE |
| `setWhereAnd` | `($string)` | Add AND condition |
| `setWhereOr` | `($string)` | Add OR condition |

## MQuery

| Method | Signature | Description |
|--------|-----------|-------------|
| `result` | *(property)* | 2D result array |
| `addFilter` | `($field, $oper, $value, $connector)` | Add filter |
| `applyFilter` | `()` | Apply filter |
| `bof` / `eof` | `()` | Before first / after last |
| `chunkResult` | `($key, $value, $showKeyValue)` | Condense to assoc array |
| `fields` | `($fieldName)` | Field value in current record |
| `getColumnCount` / `getColumnName` / `getColumnNames` | | Column info |
| `getCSV` | `($filename)` | Export to CSV |
| `getFieldValues` | `()` | Associative array of current record |
| `getPage` | `($pageno)` | Get page subset |
| `getPageCount` | `()` | Total pages |
| `getRowCount` | `()` | Total rows |
| `getRowObject` | `()` | Current record as object |
| `getRowValues` | `()` | Current record as array |
| `getValue` | `($colName)` | Single field value |
| `moveFirst` / `moveLast` / `moveNext` / `movePrev` / `moveTo` | | Navigate |
| `setOrder` | `($order)` | Sort ResultSet |
| `setPageLength` | `($length)` | Set pagination |

## MForm

| Method | Signature | Description |
|--------|-----------|-------------|
| `__construct` | `($title, $action, $close, $icon)` | Constructor |
| `addButton` | `($btn)` | Add button |
| `addError` | `($err)` | Add error message |
| `addField` | `($field, $hint)` | Add control |
| `addFields` | `($fields)` | Add array of controls |
| `addInfo` | `($info)` | Add info message |
| `addJsCode` | `($jscode)` | Add JavaScript |
| `addValidator` | `($validator)` | Add validator |
| `clearButtons` / `clearFields` | `()` | Clear |
| `createFields` | `()` | Override for field creation |
| `getButton` | `($name)` | Get button by name |
| `getData` | `()` | Get transfer object |
| `getField` | `($name)` | Get control by name |
| `getFieldValue` | `($name, $value)` | Get field value |
| `getFormValue` | `($name, $value)` | Get posted value |
| `hasErrors` | `()` | Has error messages? |
| `isSubmitted` | `()` | Was form submitted? |
| `onSubmit` | `($jscode)` | Set onSubmit JS |
| `setAction` | `($action)` | Set form action URL |
| `setButtons` | `($btn)` | Set button array |
| `setClose` | `($action)` | Set close URL |
| `setData` | `($data)` | Transfer object to fields |
| `setFieldAttr` | `($name, $attr, $value)` | Set field attribute |
| `setFields` | `($fields)` | Set field array |
| `setFieldValue` | `($name, $value)` | Set field value |
| `setLabelWidth` | `($width)` | Label width (px or %) |
| `setTitle` | `($title)` | Set title (NULL = no title bar) |
| `setValidators` | `($validators)` | Set validators |
| `validate` | `($required, $assert)` | Validate fields |
| `validateAll` | `($assert)` | Validate all fields filled |

## MGrid

| Method | Signature | Description |
|--------|-----------|-------------|
| `__construct` | `($data, $columns, $href, $pageLength, $index, $name, $useSelecteds)` | Constructor |
| `addActionDelete` | `($href)` | Add delete action |
| `addActionIcon` | `($alt, $icon, $href)` | Add icon action |
| `addActionSelect` | `()` | Add checkbox column |
| `addActionText` | `($alt, $text, $href)` | Add text action |
| `addActionUpdate` | `($href)` | Add edit action |
| `addFilterSelection` | `($index, $label, $options, $value)` | Selection filter |
| `addFilterText` | `($index, $label, $value)` | Text filter |
| `setClose` | `($action)` | Close URL |
| `setColumnAttr` | `($col, $attr, $value)` | Column attribute |
| `setControls` | `($controls)` | Controls area |
| `setData` | `($data)` | Set data |
| `setFilter` | `($status)` | Show/hide filter |
| `setIndex` | `($index)` | Index column |
| `setLinkType` | `($type)` | `'hyperlink'` or `'linkbutton'` |
| `setPageLength` | `($length)` | Rows per page |
| `setRowMethod` | `($class, $method)` | Per-row callback |

## RetrieveCriteria

| Method | Signature | Description |
|--------|-----------|-------------|
| `retrieveAsQuery` | `($params)` | Return MQuery |
| `retrieveAsCursor` | `($params)` | Return Cursor |
| `retrieveAsProxyQuery` | `($params)` | Proxy MQuery |
| `addColumnAttribute` | `($attr, $alias)` | Add column |
| `addCriteria` | `($op1, $operator, $op2)` | AND filter |
| `addOrCriteria` | `($op1, $operator, $op2)` | OR filter |
| `addGroupAttribute` | `($attr)` | GROUP BY |
| `addHavingCriteria` | `($op1, $operator, $op2)` | HAVING AND |
| `addJoinCriteria` | `($criteria)` | JOIN |
| `addOrderAttribute` | `($attr, $ascend)` | ORDER BY |
| `getCriteria` | `($op1, $operator, $op2)` | Get BaseCriteria |
| `setAlias` | `($alias, $classMap)` | Table alias |
| `setAssociationAlias` | `($name, $alias)` | Association alias |
| `setAssociationType` | `($name, $joinType)` | inner/left/right |
| `setDistinct` | `($distinct)` | DISTINCT |

## PersistentObject

| Method | Signature | Description |
|--------|-----------|-------------|
| `isPersistent` / `setPersistent` | `($v)` | Persistent status |
| `isProxy` / `setProxy` | `($v)` | Proxy status |
| `retrieve` | `()` | Fill from DB by key |
| `retrieveAsProxy` | `()` | Fill proxy attributes only |
| `retrieveFromQuery` | `($query)` | Fill from MQuery |
| `retrieveAssociation` | `($target, $order)` | Fill association |
| `getCriteria` | `()` | Get RetrieveCriteria |
| `getDeleteCriteria` | `()` | Get DeleteCriteria |
| `save` | `()` | Persist to DB |
| `saveAssociation` | `($target)` | Persist association |
| `delete` | `()` | Remove from DB |
| `deleteAssociation` | `($target, $obj)` | Remove association |

## MUI

| Method | Signature | Description |
|--------|-----------|-------------|
| `alert` | `($msg, $info, $href)` | Error prompt |
| `createForm` | `($title)` | New MForm |
| `getForm` | `($module, $name, $data, $dir)` | Get form instance |
| `getGrid` | `($module, $name, $data, $dir)` | Get grid instance |
| `getImage` | `($module, $name)` | Image URL |
| `getImageSrc` | `($name, $module)` | Image absolute path |
| `getImageTheme` | `($theme, $name)` | Theme image URL |
| `getReport` | `($module, $name, $data, $dir)` | Get report instance |
| `getWindow` | `($winId, $modal, $reload)` | JS to open window |

## MPage

| Method | Signature | Description |
|--------|-----------|-------------|
| `addJsCode` | `($jscode)` | Add JavaScript |
| `addScript` | `($url)` | Add script URL |
| `addStyle` | `($url)` | Add stylesheet (relative) |
| `addStyleURL` | `($url)` | Add stylesheet (absolute) |
| `isPostBack` | `()` | Was page submitted? |
| `onLoad` | `($jscode)` | onLoad JavaScript |
| `onSubmit` | `($jscode)` | onSubmit JavaScript |
| `redirect` | `($url)` | Redirect |
| `request` | `($vars, $component, $from)` | Get submitted values |
| `setTitle` | `($title)` | Set page title |
| `setViewState` | `($key, $value)` | Save state |
| `getViewState` | `($key)` | Get saved state |

## Exceptions

| Exception | Extends | Use |
|-----------|---------|-----|
| `EMioloException` | `Exception` | Base exception |
| `EBusinessException` | `EMioloException` | Business logic errors |
| `EControlException` | `EMioloException` | UI control errors |
| `EDatabaseException` | `EMioloException` | Database connection errors |
| `EDatabaseExecException` | `EMioloException` | SQL execution errors |
| `EDatabaseQueryException` | `EMioloException` | Query errors |
| `EDatabaseTransactionException` | `EMioloException` | Transaction errors |
| `EDataNotFoundException` | `EMioloException` | Record not found |
| `EFileNotFoundException` | `EInOutException` | File not found |
| `ELoginException` | `EMioloException` | Authentication errors |
| `ESecurityException` | `EMioloException` | Authorization errors |
| `ESessionException` | `EMioloException` | Session errors |
| `ETimeOutException` | `EMioloException` | Timeout errors |

## Data Structures

| Class | Description |
|-------|-------------|
| `MList` | Generic keyed list: `add`, `get`, `delete`, `insert`, `set`, `clear`, `hasItems` |
| `MObjectList` | List of objects (extends MList) |
| `MStringList` | List of strings: `addValue`, `find`, `getText`, `getValueText` |
| `MQueryRange` | Pagination: `new MQueryRange($page, $rowsPerPage)` |
| `MKrono` | Date/time utilities |
| `MFormatValue` | Value formatting |
| `MTreeArray` | Tree structure utilities |
