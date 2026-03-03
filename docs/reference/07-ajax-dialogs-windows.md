# AJAX, Dialogs & Windows

[Back to Index](index.md)

## AJAX

MIOLO implements AJAX via CPAINT and Prototype libraries.

### Server-Side: AJAX Forms

```php
class frmAjaxExample extends MFormAJAX
{
    function createFields()
    {
        // Define an AJAX object in JavaScript
        $this->page->addJsCode("
            var ajaxSelection = new Miolo.Ajax({
                updateElement: 'secondSelection',
                response_type: 'TEXT',
                remote_method: 'ajaxGetOptions',
                parameters: function() {
                    var sel = miolo.getElementById('firstSelection');
                    return sel.options[sel.selectedIndex].text;
                }
            });
        ");

        $fields = array(
            new MSelection('firstSelection', '', 'Category', $categories),
            new MButton('btnSel', '[Select]', 'ajaxSelection.call();'),
        );
        $this->setFields($fields);
    }

    // Server-side method called via AJAX
    function ajaxGetOptions($arg)
    {
        $options = $this->loadOptionsFor($arg);
        $sel = new MSelection('secondSelection', '', 'Options', $options);
        $this->manager->getTheme()->setAjaxContent($sel);
    }
}
```

### Client-Side: Miolo.Ajax Object

```javascript
var ajaxObject = new Miolo.Ajax({
    updateElement: 'elementId',        // HTML element to auto-update (optional)
    url: 'http://...',                 // URL to call (optional, defaults to form action)
    response_type: 'TEXT',             // TEXT | XML | JSON | OBJECT (required)
    remote_method: 'phpMethodName',    // PHP method to execute (required)
    parameters: function() {           // Parameters to send (optional)
        return { key: 'value' };
    },
    callback_function: function(result, xmlText) {  // Custom handler (optional)
        // process response
    }
});

ajaxObject.call();  // trigger the AJAX call
```

**Response types:**
- `TEXT` -- Server returns HTML via `$theme->setAjaxContent($control)`
- `XML` -- Server builds XML via cpaint `add_node()` / `set_data()`
- `JSON` -- Server returns JSON
- `OBJECT` -- Server returns structured object

### Server-Side XML Response

```php
function ajaxGetStudents($arg)
{
    $result_node = $this->cp->add_node('students');
    $query->moveFirst();
    while (!$query->eof) {
        $name_node = $result_node->add_node('name');
        $name_node->set_data($query->fields('name'));
        $query->moveNext();
    }
}
```

Generated XML:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<ajaxResponse>
    <students>
        <name>Alice</name>
        <name>Bob</name>
    </students>
</ajaxResponse>
```

### AjaxHandler (Performance Optimization)

Handlers prefixed with `_` skip the main handler chain, reducing overhead:

```php
<?php
// File: handlers/_ajaxhandler.inc
MIOLO::Import('extensions::cpaint2.inc.php', 'cpaint');
$cp = new cpaint();

$page->addScript('x/x_core.js');
$page->addScript('x/x_dom.js');
$page->addScript('cpaint/cpaint2.inc.js');
$page->addScript('m_ajax.js');

$cp->register('ajax_sample');

if (($page->request('cpaint_function')) != '') {
    $theme->clearContent();
    $page->generateMethod = 'generateAJAX';
    $page->cpaint = $cp;
    $cp->start('ISO-8859-1');
}

function ajax_sample($arg)
{
    global $theme;
    $sample = new MRawText("<br>Received: $arg");
    $theme->setAjaxContent($sample);
}
?>
```

**Calling from a form:**
```php
$urlAjax = $this->manager->getActionURL('tutorial', '_ajaxhandler');
new MButton('btnAjax', '[Call Ajax]',
    "ajaxHandler.url = '$urlAjax'; ajaxHandler.call();");
```

### MButtonAJAX (Simplified)

```php
new MButtonAjax($name, $label, $updateElement, $parameter, $method)
```

```php
// When clicked: calls 'ajaxShowImage' server method,
// passes value of 'selImageName', updates 'imageArea'
new MButtonAjax('btnAjax', '[Show Image]', 'imageArea', 'selImageName', 'ajaxShowImage');
```

---

## Dialogs

Dialogs display forms in modal/non-modal dialog boxes within the page using IFrames.

### Class Hierarchy

**Server:** `MForm` -> `MFormAjax` -> `MFormDialog`

**Client:** `Miolo.Dialog` (m_dialog.js), `Miolo.IFrame` (m_iframe.js)

### Creating a Dialog

```php
// Create dialog link
$url = $MIOLO->getActionURL('mymodule', 'main:editform');
$dialog = new MDialog('dlgEdit', $url, $modal = true, $top = 100, $left = 200);

// Get the JavaScript link to open it
$jsLink = $dialog->getLink($params = array(), $reload = false);
```

### MFormDialog

Base class for forms displayed in dialogs:

```php
class frmEditDialog extends MFormDialog
{
    function __construct()
    {
        parent::__construct('Edit Item');
        $this->eventHandler();
    }

    function createFields()
    {
        $fields = array(
            new MTextField('name', '', 'Name', 30),
        );
        $buttons = array(
            new MButton('btnSave', 'Save'),
            new MButton('btnClose', 'Close', $this->linkClose),
        );
        $this->setFields($fields);
        $this->setButtons($buttons);
    }

    function btnSave_click($sender)
    {
        // Save data...

        // Set value in parent window/dialog
        $this->setParentFieldValue('fieldName', $newValue);

        // Close the dialog
        $this->close();
    }
}
```

### Dialog Properties

| Property | Description |
|----------|-------------|
| `$this->linkClose` | JS to close dialog (reuses same object on reopen) |
| `$this->linkFree` | JS to close and free dialog (creates new object on reopen) |
| `$this->commands` | JS commands executed on dialog's `onLoad` event |

### Dialog Methods

```php
$this->close();                                  // close dialog
$this->setParentFieldValue('field', $value);     // set field in parent
$this->getParentFieldValue('parentField', 'myField');  // get from parent
$this->setPosition($top, $left);                 // reposition
```

### Client-Side IFrame API

```javascript
miolo.iFrame.dialogs['dlgEdit'].close();
miolo.iFrame.dialogs['dlgEdit'].free();
miolo.iFrame.dialogs['dlgEdit'].show();
miolo.iFrame.dialogs['dlgEdit'].hide();
miolo.iFrame.dialogs['dlgEdit'].getField('fieldName');
miolo.iFrame.dialogs['dlgEdit'].parentField('field', 'value');
miolo.iFrame.dialogs['dlgEdit'].setPosition(100, 200);
```

---

## Windows

`MWindow` simulates pop-up windows within the browser. Based on Prototype Window library by
Sebastien Gruhier.

### Creating a Window

```php
$urlWindow = $MIOLO->getActionURL('tutorial', 'main:windows:window');
$win = new MWindow('winMain', array(
    'url'         => $urlWindow,
    'title'       => 'My Window',
    'top'         => 50,
    'left'        => 50,
    'width'       => 400,
    'height'      => 300,
    'resizable'   => 'true',
    'minimizable' => 'false',
    'maximizable' => 'false',
    'closable'    => 'true',
    'draggable'   => 'true',
    'opacity'     => 1,
));
$win->setStatusBar(new MLabel('Status Bar'));
```

### Window Options

| Option | Default | Description |
|--------|---------|-------------|
| `title` | `''` | Window title |
| `url` | `''` | URL for content |
| `top` | 50 | Top position (px) |
| `left` | 50 | Left position (px) |
| `width` | 400 | Width (px) |
| `height` | 100 | Height (px) |
| `resizable` | `'false'` | Can resize |
| `minimizable` | `'false'` | Can minimize |
| `maximizable` | `'false'` | Can maximize |
| `closable` | `'true'` | Can close |
| `draggable` | `'true'` | Can drag |
| `opacity` | 1 | Opacity (0-1) |

### Opening a Window

```php
$ui = $MIOLO->getUI();
$link = $ui->getWindow('winMain', $modal = true, $reload = false);
// Returns JavaScript code to open the window
```

### Interacting with Window Content

```php
// Change a field value in a window's form
new MLink('lnk', 'Change Value',
    "javascript:miolo.getWindow('winModal').field('txtField', 'new value')");

// Close button inside a window form
new MButton('btnClose', 'Close', $this->getCloseWindow());
```
