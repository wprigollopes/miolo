# UI Controls (Widgets)

[Back to Index](index.md)

## Control Class Hierarchy

All UI controls inherit from `MComponent` -> `MControl`. The hierarchy is organized into functional groups:

### Layout / Container Controls

```
MControl
├── MDiv (content, class, attributes)
│   ├── MSpacer (space)
│   ├── MHr
│   ├── MBoxTitle (cssClass, caption, close, icon)
│   └── MBox (caption, close, icon)
├── MSpan
├── MAreaContainer
├── MContent
│   └── MFileContent
├── MContentHeader
└── MIFrame
```

### Form Controls

```
MControl
└── MForm (title, action, close, icon)
    ├── MCompoundForm         # Info + Panel + Form areas
    ├── MCSSForm              # CSS-positioned form
    ├── MCSSPForm             # Absolute-positioned form
    ├── MIndexedForm
    ├── MTabbedForm           # Tabbed pages
    └── MTabbedForm2
```

### Input Controls

```
MFormControl (name, value, label, color, hint)
├── MTextField (name, value, label, size, hint, validator)
│   ├── MPasswordField
│   ├── MHiddenField
│   ├── MMultiLineField (name, value, label, size, rows, cols)
│   ├── MHtmlArea
│   ├── MFileField
│   ├── MCalendarField (name, value, label, size, hint, type)
│   ├── MCurrencyField
│   ├── MLookupField
│   ├── MMultiTextField2 (name, value, label, fields, width, buttons, layout)
│   └── MMultiTextField3
├── MButton (name, label, action, image)
│   ├── MInputButton
│   ├── MButtonFind
│   └── MButtonWindow
├── MChoiceControl
│   ├── MCheckBox (name, value, label, checked, text, hint)
│   └── MRadioButton
└── MImage (name, label, location, attrs)
    └── MImageFormLabel
```

### Label Controls

```
MFormControl
└── MBaseLabel (name, value, label, color, hint)
    ├── MPageComment (text)        # HTML comment
    ├── MSeparator (text, margin)  # Horizontal line
    ├── MLabel (text, color)
    ├── MFieldLabel (id, text)     # <label> tag
    ├── MTextHeader (name, level, text, color)  # <h1>-<h6>
    ├── MText (name, text, color)
    │   └── MTextLabel (name, text, label, color)
    └── MRawText (text)            # No CSS formatting
```

### Link Controls

```
MFormControl
└── MLink (name, label, href, text, target)
    ├── MButtonClose
    ├── MLinkBack
    ├── MOpenWindow
    ├── MGridHeaderLink
    ├── MLinkButton (name, label, action)   # POST on click
    ├── MActionHyperLink (name, label, module, action, item, args)
    └── MImageLink (name, label, action, location, attrs)
        ├── MImageLinkLabel
        └── MImageButton                    # Image that does POST
```

### List Controls

```
MListControl
├── MSelection (name, value, label, options, showValues, hint, size)
│   └── MComboBox
├── MMultiSelection (name, values, label, options)
├── MMultiSelectionField
├── MOrderedList
└── MUnOrderedList
```

### Container / Group Controls

```
MFormControl
└── MContainer (name, controls, disposition)
    ├── MVContainer                         # Vertical
    ├── MHContainer                         # Horizontal
    └── MBaseGroup (name, caption, controls, disposition, border)
        ├── MCheckBoxGroup (name, label, options, hint, disposition)
        ├── MRadioButtonGroup (name, label, options, default, hint, disposition)
        └── MLinkButtonGroup

MBasePanel
├── MPanel (name, caption, controls, close, icon)
└── MActionPanel (name, caption, controls, close, icon, iconType)
```

### Grid Controls

```
MBaseGrid
├── MGridColumn (title, align, nowrap, width, visible, options, order, filter)
│   ├── MGridControl (control, title, align)
│   └── MGridHyperlink (title, href, width)
└── MGrid (data, columns, href, pageLength, index, name)
    ├── MDataGrid (query, columns, href, pageLength)     # From MQuery
    ├── MObjectGrid (array, columns, href, pageLength)   # From object array
    └── MPDFReport (data, columns, pageLength)            # PDF report
```

### Validator Controls

```
MValidator
├── MRequiredValidator (field, max)
├── MMASKValidator (field, label, mask, type)
├── MEmailValidator
├── MPasswordValidator
├── MCEPValidator          # Brazilian ZIP
├── MCPFValidator          # Brazilian CPF
├── MCNPJValidator         # Brazilian CNPJ
├── MPHONEValidator
├── MTIMEValidator
├── MDATEDMYValidator      # DD/MM/YYYY
├── MDATEYMDValidator      # YYYY/MM/DD
├── MCompareValidator (field, label, operator, value, datatype)
├── MRangeValidator (field, label, min, max, datatype)
└── MRegExpValidator (field, label, regexp)
    └── MIntegerValidator
```

---

## MControl (Base Class)

All visible controls inherit from `MControl`.

### Form Mode Constants

| Constant | Value | Layout |
|----------|-------|--------|
| `FORM_MODE_WHOLE_ROW` | 0 | Control occupies entire form row |
| `FORM_MODE_SHOW_SIDE` | 1 | Caption side-by-side |
| `FORM_MODE_SHOW_ABOVE` | 2 | Caption above control |
| `FORM_MODE_SHOW_NBSP` | 3 | Caption with spaces beside |

### Key Methods

```php
$control->setClass($cssClass);
$control->setWidth($value);
$control->setHeight($value);
$control->setColor($value);
$control->setEnabled($state);
$control->setReadOnly($status);
$control->setVisibility($value);
$control->setPosition($left, $top, $position = 'absolute');
$control->setFormMode($mode);
$control->setCaption($caption);
$control->addStyle($name, $value);
$control->addStyleFile($styleFile);
$control->addAttribute($name, $value);
$control->addControl($childControl);
$control->attachEventHandler($name, $handler, $param = NULL);
$control->eventHandler();
$control->generate();  // returns HTML
```

---

## Key Controls Reference

### MButton

```php
new MButton($name, $label, $action = NULL, $image = NULL)
```

**Action values:**
- `'SUBMIT'` -- POST form data
- `'RESET'` -- Reset form
- `'PRINT'` -- Print page
- `'PDF'` -- Generate PDF
- `'RETURN'` -- Go back
- `'NONE'` -- No action
- URL string -- Navigate to URL
- JavaScript code -- Execute JS

```php
$btn = new MButton('btnSave', 'Save');               // Submit button
$btn = new MButton('btnPrint', 'Print', 'PRINT');     // Print button
$btn = new MButton('btnGo', 'Go', 'http://...');      // Navigate
$btn = new MButton('btnAlert', 'Alert', "alert('!')"); // JavaScript
```

### MTextField

```php
new MTextField($name, $value, $label, $size = 10, $hint = '', $validator = NULL)
```

**Input masking:**
```php
$field = new MTextField('phone', '', 'Phone', 15);
$field->addMask('(##) ####-####', 'optional', 'Invalid phone');
```

Mask characters: `#` = [0-9], `a` = [a-z0-9], `A` = [A-Z0-9], `l` = [a-z], `L` = [A-Z]

### MSelection

```php
new MSelection($name, $value, $label, $options, $showValues = false, $hint = '', $size = '')
```

```php
$options = array('Option A', 'Option B', 'Option C');
$sel = new MSelection('color', '', 'Color', $options);
$sel->setAutoSubmit(true);  // auto-submit on change
```

### MCalendarField

```php
new MCalendarField($name, $value, $label, $size = 20, $hint = '', $type = 'calendar-win2k-1')
```

### MGrid

```php
new MGrid($data, $columns, $href, $pageLength = 15, $index = 0, $name = '', $useSelecteds = true)
```

- `$data` -- 2D array
- `$columns` -- array of `MGridColumn` objects
- `$href` -- URL for the grid page
- `$pageLength` -- rows per page (0 = all)
- `$index` -- column used as row identifier

**Adding actions:**
```php
$grid->addActionUpdate($href);           // Edit icon
$grid->addActionDelete($href);           // Delete icon
$grid->addActionSelect();                // Checkbox column
$grid->addActionIcon('View', 'view.png', $href);
$grid->addActionText('Details', 'Details', $href);
```

**Filters:**
```php
$grid->setFilter(true);
$grid->addFilterText(0, 'Name');
$grid->addFilterSelection(1, 'Status', $options);
```

**Accessing selected rows:**
```php
$selected = $grid->selecteds;       // current page
$allSelected = $grid->allSelecteds; // all pages
```

### MDataGrid

Uses an `MQuery` as data source:

```php
$query = $business->listAll();
$columns = array(
    new MDataGridColumn('name', 'Name', 'left', false, '60%', true, null, true),
    new MDataGridColumn('email', 'Email', 'left', false, '40%'),
);
$href = $MIOLO->getActionURL('mymodule', 'main:item:find');
$grid = new MDataGrid($query, $columns, $href, 15);
$grid->setLinkType('hyperlink');  // or 'linkbutton'
```

**MDataGridHyperlink** -- column rendered as a link:
```php
// #0# is replaced by column 0 value for each row
$href = $MIOLO->getActionURL('example', 'course:main', '#0#', array('form' => 'data'));
new MDataGridHyperlink('name', 'Courses', $href, '100%', true, null, true);
```

### MObjectGrid

Uses an array of objects:

```php
$objects = $criteria->retrieveAsCursor();
$columns = array(
    new MObjectGridColumn('name', 'Name'),
    new MObjectGridColumn('email', 'Email'),
);
$grid = new MObjectGrid($objects, $columns, $href, 15);
```

### MPanel

```php
new MPanel($name, $caption, $controls = NULL, $close = '', $icon = '')
```

```php
$panel = new MPanel('pnlInfo', 'Information');
$panel->addControl($label1, '200px', 'left');
$panel->addControl($label2, '', 'clear');  // new line
```

### MActionPanel

```php
new MActionPanel($name, $caption, $controls, $close, $icon, $iconType = 'large')
```

```php
$panel = new MActionPanel('pnlActions', 'Actions');
$panel->addAction('Students', $ui->getImage($module, 'user.png'),
    $module, 'main:student:find');
$panel->addAction('Courses', $ui->getImage($module, 'system.png'),
    $module, 'main:course:find');
$panel->addBreak();  // line break between icons
$panel->setIconType('small');  // or 'large'

// Permission-controlled action
$panel->addUserAction('COURSE_EDIT', 1, 'Edit Course',
    $ui->getImage($module, 'edit.png'), $module, 'main:course:edit');
```

### MTabbedForm

```php
$tabbed = new MTabbedForm('tabs');
$tabbed->addPage($formGeneral);   // MForm objects
$tabbed->addPage($formDetails);
$tabbed->setCurrentPage(0);
$tabbed->setPainterMethod('javascript');  // or 'html'
```

### MTreeMenu

```php
new MTreeMenu($name, $template, $action, $target)
```

Uses Tigra tree menu JavaScript. `#` in action URL is replaced by the tree element number.

---

## Validators

All validators follow the pattern:

```php
new M<Type>Validator($field, $label = '', ..., $type = 'optional', $msgerr = '')
```

The `$type` parameter: `'required'` (must be filled + must pass validation) or `'optional'` (validate only if filled).

```php
$validators = array(
    new MRequiredValidator('name'),
    new MEmailValidator('email', 'Email'),
    new MCPFValidator('cpf', 'CPF'),
    new MDATEDMYValidator('birthDate', 'Birth Date'),
    new MCompareValidator('age', 'Age', '>=', 18, 'n'),
    new MRangeValidator('score', 'Score', 0, 100, 'n'),
    new MRegExpValidator('code', 'Code', '/^[A-Z]{3}\d{4}$/'),
);
$this->setValidators($validators);
```

### Validation Methods

```php
$this->validate(array('name', 'email'));  // validate specific fields
$this->validateAll();                      // validate all fields are filled
$this->validateRequiredFields();           // validate fields marked required=true
```
