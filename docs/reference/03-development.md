# Development Guide

[Back to Index](index.md)

## Basic Concepts

| Concept | Description |
|---------|-------------|
| **Application** | A collection of modules. Each MIOLO installation is associated with a single application. |
| **Module** | A component reflecting a sub-domain. Has a name, standard directory structure, and configuration file. |
| **Control (Widget)** | UI component for HTML page rendering. Controls can aggregate other controls and have properties and events. |
| **Page** | Specific control (`MPage` instance) serving as base for HTML page rendering. |
| **Theme** | Container control (`MTheme` instance) for controls rendered in the HTML page. Defines visual "elements" with associated CSS. |
| **Handler** | `MHandler` instance. Processes user requests from the browser. Each module defines handlers in its `handlers/` directory. |
| **Namespace** | Aliases for directories, enabling physical file relocation without code changes. |

## Development Process

1. Model classes and database
2. Create module directory structure
3. Define theme (existing or new)
4. Create module-specific controls if needed
5. Create module configuration in `modules/<module>/etc/module.conf`
6. Create handler class in `modules/<module>/handlers/handler.class`
7. Create main handler in `modules/<module>/handlers/main.inc`
8. Create business classes in `modules/<module>/classes`
9. Create forms in `modules/<module>/forms`

## URL Structure

### Handler URLs

```
http://host/index.php?module=<module>&action=<action>[&parameter list]
```

The `action` parameter is a colon-separated path of handlers to execute sequentially:

```
http://host/index.php?module=example&action=main:course:find
```

This executes: `main.inc` -> `course.inc` -> `find.inc`

### File URLs

```
Images: http://host/index.php?module=common&action=html:images:save.png
PDF:    http://host/index.php?module=common&action=html:files:example.pdf
CSS:    http://host/index.php?module=miolo&action=themes:kenobi:theme.css
```

### Friendly URLs

With Apache `mod_rewrite`, `index.php` can be omitted:

```
http://host/index.php/common/html/images/save.png
http://host/common/html/images/save.png
```

### The `item` Parameter

The third URL parameter is conventionally the `item` variable, accessible inside handlers:

```
http://host/index.php?module=example&action=main:course:find&item=10&form=data
```

## Page Lifecycle

1. `index.php` instantiates the MIOLO class (Singleton)
2. Executes `MIOLO::HandlerRequest`
3. Analyzes URL to determine: file request or handler execution
4. **If file:** locates and sends to browser
5. **If handler:**
   - Includes `support.inc`, initializes MIOLO properties
   - Initializes session handling
   - Verifies login information
   - Obtains theme
   - Initializes page
   - Calls `MIOLO::InvokeHandler`
6. `InvokeHandler` calls the startup module's "main" handler
7. The main handler calls the next handler in the action sequence
8. After all handlers execute, state variables are saved and the page is rendered

## Global Variables

| Variable | Type | Description |
|----------|------|-------------|
| `$MIOLO` | MIOLO | Main framework class instance (Singleton) |
| `$page` | MPage | Current page object |
| `$context` | MContext | Context object (URL parsing) |
| `$theme` | MTheme | Current theme object |
| `$auth` | MAuth | Authentication object |
| `$perms` | MPerms | Permissions object |
| `$session` | MSession | Current session object |
| `$state` | MState | State variables object |
| `$menu` | -- | Main menu object |
| `$log` | MLog | Admin module log object |
| `$navbar` | -- | Navigation bar object |
| `$module` | string | Name of current handler's module |
| `$action` | string | Path of current handler |
| `$item` | string | Item field from current URL |
| `$url` | string | Complete URL of current handler |

## Handlers

### Handler Class (Required)

Every module must have a handler class extending `MHandler`:

```php
<?php
// File: modules/<module>/handlers/handler.class
class Handler<ModuleName> extends MHandler
{
}
?>
```

### Main Handler (Required)

Every module must have a `main.inc` file as the entry point:

```php
<?php
// File: modules/<module>/handlers/main.inc
$ui = $MIOLO->getUI();
$form = $ui->getForm('mymodule', 'frmMain');
$theme->setContent($form);
?>
```

### Handler Chaining

Handlers can delegate to sub-handlers. The main handler typically:
1. Sets up a navigation panel
2. Determines the next handler from the action chain
3. Invokes the next handler

```php
<?php
// File: modules/example/handlers/main.inc
$ui = $MIOLO->getUI();
$theme->clearContent();
$close = $MIOLO->getActionURL('example', 'main');

$panel = new MActionPanel('pnlMain', 'Example Module', '', $close,
    $ui->getImage($module, 'tools1.png'));
$panel->addAction('Students', $ui->getImage($module, 'user.png'),
    $module, 'main:student:find');
$panel->addAction('Courses', $ui->getImage($module, 'system.png'),
    $module, 'main:course:find');

// Execute the next handler in the action chain
$a = $context->shiftAction();
$handled = $MIOLO->invokeHandler($module, $a);

// If no sub-handler was executed, show the panel
if (!$handled) {
    $theme->insertContent($panel);
}
?>
```

### Sub-Handler Example (Object Router)

```php
<?php
// File: modules/example/handlers/course.inc
// Routes to specific course handlers
$a = $context->shiftAction();
$MIOLO->invokeHandler($module, $a);
?>
```

## Forms (WebForms)

Each page is a single HTML `<form>` tag. All controls must have distinct names/IDs.

Forms are stored as `<form_name>.class` in `modules/<module>/forms/`.

### Form Lifecycle

Three methods auto-execute on form instantiation (in this order):

1. **`createFields()`** -- Define form fields and action buttons
2. **`getFormFields()`** -- Called on submit; transfers browser values to form fields
3. **`onLoad()`** -- Initialization code

### Basic Form Example

```php
<?php
class frmMain extends MForm
{
    function __construct()
    {
        parent::__construct('Hello World!');
        $this->setWidth('400px');
        $this->eventHandler();
    }

    function createFields()
    {
        $fields = array(
            new MTextField('myMsg', '', 'Message', 30, 'Your message'),
            new MTextField('myName', '', 'Name', 20, 'Your name')
        );

        $buttons = array(
            new MButton('btnHello', 'Click Me!'),
            new MButton('btnReset', 'Reset'),
        );

        $this->setFields($fields);
        $this->setButtons($buttons);
    }

    function btnHello_click($sender)
    {
        $this->setFieldAttr('myMsg', 'visible', false);
        $this->setFieldAttr('myName', 'visible', false);
        $this->setFieldAttr('btnHello', 'visible', false);

        $this->addField(new MLabel('Hello World!'));
        $this->addField(new MLabel($this->myMsg->getValue()));
        $this->addField(new MLabel($this->getFieldValue('myName')));
    }
}
?>
```

### MCompoundForm

A compound form is divided into three areas:

```
┌──────────────────────────────────────┐
│  _info[]   -- MTextLabel attributes  │
├──────────────────────────────────────┤
│  _panel[]  -- MActionPanel icons     │
├──────────────────────────────────────┤
│  _form[]   -- MForm / MGrid content  │
└──────────────────────────────────────┘
```

```php
<?php
class frmCourse extends MCompoundForm
{
    protected $oid;
    protected $course;

    function __construct($objCourse)
    {
        $this->course = $objCourse;
        parent::__construct();
        $this->setTitle($this->course->name);
        $this->setIcon($this->manager->getUI()->getImage('example', 'icon.png'));
        $this->setClose($this->manager->getActionURL('example', 'main:course:find'));
    }

    function createFields()
    {
        global $module, $context;
        $course = $this->course;
        MUtil::setIfNull($this->oid, $course->idCourse);

        // Form area -- based on URL "form" parameter
        $ui = $this->manager->getUI();
        $form = MForm::getFormValue('form');
        switch ($form) {
            case 'data':
                $this->_form[0] = $ui->getForm($module, 'frmCourseData', $course, 'course');
                break;
            case 'delete':
                $this->_form[0] = $ui->getForm($module, 'frmCourseDel', $course, 'course');
                break;
        }

        // Panel area -- action icons
        $action = $context->action;
        $this->_panel[0] = $panel = new MActionPanel('pnlCompound', 'Options', '', false);
        $panel->addAction('Edit', $ui->getImage('example', 'edit.png'),
            'example', $action, $this->oid, array("form" => "data"));
        $panel->addAction('Delete', $ui->getImage('example', 'del.png'),
            'example', $action, $this->oid, array("form" => "delete"));

        // Info area -- object attributes
        $this->_info[0] = new MTextLabel('txtName', $course->name, 'Name');
    }
}
?>
```

## Event Handling

### Button Events

Button events follow the naming convention `<button_name>_click`:

```php
function createFields()
{
    $this->setButtons(array(new MButton('btnSave', 'Save')));
}

// Auto-called when btnSave is clicked
function btnSave_click($sender)
{
    // handle the event
}
```

### Custom Event Handlers

```php
$button = new MButton('btnHello', 'Click Me!');
$button->attachEventHandler('click', 'onBtnHelloClick');
```

For handlers outside a class:
```php
$button->attachEventHandler('click', 'onBtnHelloClick');
$theme->eventHandler();

function onBtnHelloClick($sender) {
    global $theme;
    $theme->appendContent(new MLabel('Hello World!'));
}
```

For handlers with parameters:
```php
$button->attachEventHandler('click', 'onLetterClick', $char);

function onLetterClick($sender, $param) {
    $letter = $param;  // receives $char
}
```

### Submit Events via URL

```
?event=btnPost:click
```

### JavaScript PostBack

```javascript
miolo.doPostBack(eventTarget, eventArgument, formSubmit)
```

### Checking First Load vs PostBack

```php
if ($this->page->isPostBack) {
    // form was submitted
} else {
    // first load
}
```

## State Management (ViewState)

Maintain values across round-trips using ViewState:

```php
// Save state
$this->page->setViewState('key', $value);

// Retrieve state
$value = $this->page->getViewState('key');
```

## Business Classes

All domain classes extend `MBusiness` and are automatically persistent:

```php
<?php
class BusinessExampleCourse extends MBusiness
{
    public $idCourse;
    public $name;
    public $students;

    function __construct($data = NULL)
    {
        parent::__construct('example', $data);  // 'example' = DB config name
        $this->name = '';
    }

    function getById($id)
    {
        $this->idCourse = $id;
        $this->retrieve();
        return $this;
    }

    function setData($data)
    {
        $this->idCourse = $data->idCourse;
        $this->name = $data->name;
    }

    function listAll()
    {
        $criteria = $this->getCriteria();
        $criteria->addOrderAttribute('name');
        return $criteria->retrieveAsQuery();
    }

    function listByName($name)
    {
        $criteria = $this->getCriteria();
        $criteria->addCriteria('name', 'LIKE', $name);
        $criteria->addOrderAttribute('name');
        return $criteria->retrieveAsQuery();
    }

    function getEnrolled()
    {
        $this->retrieveAssociation('students');
    }
}
?>
```

### Instantiating Business Objects

```php
// By constructor data (object)
$course = $MIOLO->getBusiness('example', 'course', $data);

// By ID (calls getById)
$course = $MIOLO->getBusiness('example', 'course', 42);

// Empty
$course = $MIOLO->getBusiness('example', 'course');
```

## Themes

A theme defines the HTML page layout as a container of elements (title, navigation bar, menus, content area, status bar).

### Theme File Locations

**Global theme:**
```
<miolo>/classes/ui/themes/<theme>/theme.class
<miolo>/classes/ui/themes/<theme>/*.css
<miolo>/classes/ui/themes/<theme>/images/*
```

**Module-specific theme:**
```
<miolo>/modules/<module>/ui/themes/<theme>/theme.class
```

### Theme Class Structure

```php
class ThemeSystem extends MTheme
{
    function __construct()
    {
        parent::__construct('system');
    }

    function init() { }
    function generate() { }
    function generateDefault() { }     // standard layout
    function generateLookup() { }      // lookup dialog layout
    function generatePopup() { }       // popup layout
    function generateDOMPdf() { }      // PDF layout
}
```

### Theme Content Methods

```php
$theme->setContent($control);     // replace content area
$theme->appendContent($control);  // append to content area
$theme->insertContent($control);  // insert at beginning
$theme->clearContent();           // clear content area
$theme->setAjaxContent($control); // set AJAX response content
```

## Logging

Three storage methods: **file**, **db**, **socket**.

Three log levels: **0** (none), **1** (errors only), **2** (messages + errors + SQL).

```php
$MIOLO->logSQL($sql, $force = false, $conf = '?');
$MIOLO->logError($error, $conf = 'miolo');
$MIOLO->logMessage($msg);
```

## Trace (Debug)

```php
$MIOLO->trace($msg, $file = '', $line = 0);
$MIOLO->trace("message", __FILE__, __LINE__);
$MIOLO->trace->traceStack();
```

## Internationalization

Use the `_M()` function to mark translatable strings:

```php
new MTextField('name', '', _M('Name'), 30, _M('Enter your name'));
```

Extract and compile translations:

```bash
bin/miolo.php translate extract
# ... translate the .po files in misc/i18n/ ...
bin/miolo.php translate generate
```
