# Frontend Rendering: Server-Side Components Before React

**Building a screen felt like PHP-GTK or Visual Studio — not like writing
a web page.**

Today's frontend is JavaScript-driven: React components, Vue templates,
Svelte files, CSS-in-JS, virtual DOM diffing, client-side routing, state
management. The browser does most of the work. The server provides an API.

In 1999, the browser was the enemy. JavaScript was unreliable. CSS was
primitive. Every interaction required a full page reload. The server
rendered everything, and the browser was just a display.

---

## What Didn't Exist

### JavaScript Ecosystem
- **jQuery** — 2006
- **Angular** — 2010
- **React** — 2013
- **Vue** — 2014
- **Node.js** — 2009
- **Webpack/Vite** — 2012/2020
- **TypeScript** — 2012
- **ES6 (arrow functions, classes, modules)** — 2015
- **Fetch API** — 2015 (before that, `XMLHttpRequest` — coined "AJAX" in 2005)

### CSS
- **Flexbox** — 2009 (stable ~2014)
- **CSS Grid** — 2017
- **Media queries** — 2012
- **CSS variables** — 2017
- **Sass/Less** — 2006/2009
- **Bootstrap** — 2011
- **Tailwind** — 2017

### What Was Available
- **HTML 4** with `<table>` for layout
- **CSS 1** (1996) — fonts, colors, margins, basic selectors
- **JavaScript** — `alert()`, `onsubmit`, unreliable across browsers
- **Netscape Navigator 4** and **Internet Explorer 5** — which rendered
  the same CSS completely differently

---

## MIOLO's Server-Side Component Model

### The Object-Oriented UI Hierarchy

MIOLO built a complete **component tree** in PHP. You didn't write HTML —
you composed objects:

```
MComponent (base)
  +-- MControl (visible element)
        |-- MDiv, MSpan, MLabel
        |-- MTextField, MCalendarField, MSelection
        |-- MForm, MBaseForm
        |-- MGrid, MDataGrid
        |-- MToolbar, MToolbarButton
        |-- MDialog, MPrompt
        |-- MMenu, MContextMenu
        +-- 70+ more controls
```

#### MComponent: The Root of Everything

**File:** `classes/ui/controls/mcomponent.class.php`

```php
abstract class MComponent
{
    public $manager; // = MIOLO::getInstance()
    public $page;    // = $manager->getPage()

    public function __construct($name = NULL)
    {
        $this->manager = MIOLO::getInstance();
        $this->page = $this->manager->getPage();
        $this->className = strtolower(get_class($this));
    }
}
```

Every UI element holds a reference to the global framework singleton
and the current page — a pattern that modern frameworks solve with
dependency injection or context providers.

#### MControl: The Visible Element

**File:** `classes/ui/controls/mcontrol.class.php`

```php
abstract class MControl extends MComponent
{
    public $id, $style, $attrs, $caption, $hint;
    public $readonly, $visible, $inner;
}
```

Every visible element has `id`, `style`, `hint`, `caption`, `readonly`,
`visible`, and `inner` (child content). This is a complete component
model — React's props serve the same purpose.

### Building a Screen: Then vs Now

#### MIOLO (2001): PHP Object Composition

```php
// Creating a form with fields — no HTML written anywhere
$fields = array();
$fields[] = new MTextField('name', 'Name:', 30);
$fields[] = new MTextField('email', 'Email:', 40);
$fields[] = new MCalendarField('birthdate', 'Birth Date:');
$fields[] = new MSelection('role', array('admin', 'user'), 'Role:');

$this->addFields($fields);

$buttons = array();
$buttons[] = new MButton('save', 'Save', ':action:save');
$buttons[] = new MButton('cancel', 'Cancel', ':action:cancel');
$this->setButtons($buttons);
```

#### React (2013): JSX Component Composition

```jsx
function UserForm() {
    return (
        <form>
            <TextField name="name" label="Name:" maxLength={30} />
            <TextField name="email" label="Email:" maxLength={40} />
            <DatePicker name="birthdate" label="Birth Date:" />
            <Select name="role" options={['admin', 'user']} label="Role:" />
            <Button type="submit">Save</Button>
            <Button onClick={cancel}>Cancel</Button>
        </form>
    );
}
```

The *structure* is remarkably similar: declare components, set properties,
compose them into a container. The difference is where the rendering
happens (server vs browser) and how state changes propagate (full page
reload vs virtual DOM diff).

---

## MDataGrid: The Ancestor of DataTables

**File:** `classes/ui/controls/mdatagrid.class.php`

`MDataGrid` is a server-side rendered data table with built-in pagination,
sorting, filtering, and action columns. It binds directly to a database
query object.

```php
// Creating a data grid — the server does all the work
$columns = array(
    new MDataGridColumn('name', 'Name', 'left', true),
    new MDataGridColumn('email', 'Email', 'left', true),
    new MDataGridColumn('created_at', 'Created', 'center', true),
);

$query = $db->getQuery("SELECT name, email, created_at FROM users");
$grid = new MDataGrid($query, $columns, 'user_action', 15); // 15 rows per page
```

### How Pagination Worked

**File:** `classes/ui/controls/mgridnavigator.class.php`

The navigator renders `First | Prev | 1 2 3 ... | Next | Last` as
HTML links. Each click triggers a full GET request with `&pn_page=N`:

```php
// Pagination via URL parameters — every page is a full HTTP request
$pageLinks[$p] = new MLinkButton('', $pg,
    "$this->action&pn_page=$o&gridName=" . urlencode($this->grid->name));
```

Page state is stored in PHP session:

```php
$this->setPageNumber($this->page->getViewState("pn_page", $this->grid->name));
```

### How Sorting Worked

Clicking a column header triggered a GET request with `&orderby=column_name`.
The server re-ran the SQL query with an `ORDER BY` clause:

```php
$this->orderby = $this->page->request('orderby');
if ($this->ordered = isset($this->orderby)) {
    $this->query->setOrder($this->orderby);
    $state->set('orderby', $this->orderby, $this->name);
}
```

### Comparison: MDataGrid (2001) vs DataTables (2007) vs React Table (2016)

| Feature | MIOLO MDataGrid | jQuery DataTables | React Table (TanStack) |
|---|---|---|---|
| Rendering | Server-side HTML, full page reload | Client-side DOM manipulation | Virtual DOM (React) |
| Data source | PHP query object bound at construction | JSON array or AJAX endpoint | `useQuery` hook, any async source |
| Sorting | GET param, server re-queries, full reload | In-memory JS sort or server-side | State-driven, client or server |
| Pagination | GET param, server fetches page slice | Client-side slice or AJAX | Controlled state, cursor/offset |
| Filtering | Server-side `LIKE` queries | Client-side regex or server AJAX | Filter functions or server API |
| Column definition | PHP objects with field + SQL index | JS objects `{ data: 'name', title: 'Name' }` | Column helper with accessors |
| State persistence | PHP session (`$state->set`) | URL params or localStorage | React state / URL search params |
| Row selection | Server-side checkbox + form POST | Client-side event handlers | Controlled selection state |
| Export | None built-in (manual) | Buttons plugin (CSV, Excel, PDF) | Headless — you build it |
| Row actions | Action columns with URL links | Custom render functions | Custom cell renderers |
| Responsive | No (fixed tables) | Responsive plugin | Headless — CSS-driven |

---

## The Theme System: Frontend Object Concept

**File:** `classes/ui/controls/mtheme.class.php`

MIOLO's theme system treats the entire page as a composition of named
**elements** (slots) that a theme fills with HTML:

```php
// Theme template (html/themes/blue/template/default.php)
echo $theme->generateElement('menus');
echo $theme->generateElement('navigation');
echo $theme->generateElement('content');

$theme->setElement('bottom', new MStatusBar());
echo $theme->generateElement('bottom');
```

Each theme is a self-contained set of:
- **PHP rendering classes** that control how controls become HTML
- **CSS files** for styling
- **Image assets** for icons, backgrounds, borders
- **JavaScript** for behavior

Switching the entire application's look means changing one config value:

```php
$MIOLOCONF['theme']['name'] = 'blue'; // or 'modern', 'default', etc.
```

### The Painter Pattern

**File:** `classes/ui/painter/mhtmlpainter.class.php`

The painter converts control objects into HTML strings — a **Visitor
pattern** that separates component logic from rendering:

```php
public function div($control)
{
    return "\n<div" . $this->getId() . $this->getClass()
        . $control->getAttributes() . ">"
        . $control->getInnerToString() . "</div>";
}

public function span($control)
{
    return "<span" . $this->getId() . $this->getClass()
        . $control->getAttributes() . ">"
        . $control->getInnerToString() . "</span>";
}
```

This is the same concept as React's rendering pipeline — components
describe *what* should be rendered, and the renderer decides *how*.
MIOLO's painter outputs HTML strings; React's reconciler outputs DOM
operations. The abstraction is identical.

### The PostBack Pattern

**File:** `classes/ui/controls/mform.class.php`

Forms detect whether they're handling a POST submission — a pattern
borrowed from ASP.NET WebForms (2002), but implemented independently:

```php
$this->isPostBack = (MIOLO::_REQUEST($this->manager->formSubmit . '__ISPOSTBACK') != '');

if ($this->page->isPostBack) {
    $this->getFormFields(); // restore field values from POST data
}
```

Every form interaction — save, search, filter, paginate — was a POST
or GET request that reloaded the entire page. The server restored the
form state from the submitted data, processed the action, re-rendered
the entire component tree, and sent a complete HTML page back.

---

## JavaScript Evolution in MIOLO

### Phase 1: No JavaScript (1999-2005)

Pure server-side rendering. Every interaction is a form POST or
a link click that reloads the page.

### Phase 2: Dojo Toolkit Integration (2008-2012)

**File:** `html/scripts/m_ajax.js`

MIOLO adopted the Dojo Toolkit for AJAX and widget functionality.
Custom AJAX was built on top of Dojo's `dojo.declare`:

```javascript
// m_ajax.js — Dojo-based AJAX handler for partial page updates
dojo.declare("Miolo.Ajax", null, {
    update: function(result, ioArgs) {
        // Replace target element content with server response
        miolo.getElementById(this.updateElement).textContent = result;
        miolo.stopShowLoading();
    }
});
```

This enabled partial page updates without full reloads — the first
step toward modern single-page application behavior.

### Phase 3: jQuery Integration (2012+)

jQuery was added alongside Dojo, primarily for plugins:
- Floating scrollbar
- FullCalendar
- Custom grid interactions

The codebase shows the transition period where both Dojo and jQuery
coexist — a common pattern in long-lived applications.

---

## What This Tells Us

MIOLO's frontend architecture is a time capsule of the transition from
"the server renders everything" to "the browser handles interaction."
The component model — compose objects, set properties, let the framework
render HTML — is the same pattern that React, Vue, and Angular use today.
The difference is where that code runs: on the server in PHP (2001) or
in the browser in JavaScript (2013+).

The `MDataGrid` is the most vivid example. Its feature set (sorting,
pagination, filtering, column definitions, row actions) is identical to
jQuery DataTables or TanStack Table. The implementation is different —
every sort click hits the server, re-queries the database, and sends
back a complete HTML page — but the developer experience of "configure
a grid and let the framework handle it" is the same.
