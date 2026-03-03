# Tutorials

[Back to Index](index.md)

## Tutorial 1: Hello World

A step-by-step introduction to the MIOLO framework, from minimal to functional.

### Step 1: Create the Module Structure

```
modules/helloworld/
├── etc/
├── handlers/
└── forms/
```

### Step 2: Module Configuration

File: `modules/helloworld/etc/module.conf`

```xml
<?xml version="1.0" encoding="ISO-8859-1" standalone="yes"?>
<conf>
    <options>
        <startup>helloworld</startup>
    </options>
    <theme>
        <module>miolo</module>
        <main>clean</main>
        <lookup></lookup>
        <title>Miolo - Hello World!</title>
    </theme>
    <login>
        <check>0</check>
    </login>
</conf>
```

The `clean` theme renders only the content area (no header, menus, or status bar).
Setting `login.check` to 0 disables authentication.

### Step 3: Handler Class

File: `modules/helloworld/handlers/handler.class`

```php
<?php
class HandlerHelloWorld extends MHandler
{
}
?>
```

Every module must have a handler class named `Handler<ModuleName>` extending `MHandler`.

### Step 4: Main Handler (Minimal)

File: `modules/helloworld/handlers/main.inc`

```php
<?php
$theme->setContent(new MLabel('Hello World!'));
?>
```

### Step 5: Run It

```
http://miolo.domain/index.php/helloworld
```

### Step 6: Add a Button with Event

```php
<?php
// handlers/main.inc
$button = new MButton('btnHello', 'Click Me!');
$button->attachEventHandler('click', 'onBtnHelloClick');
$theme->setContent($button);
$theme->eventHandler();

function onBtnHelloClick($sender)
{
    global $theme;
    $theme->appendContent(new MLabel('Hello World!'));
}
?>
```

**Key concepts:**
- `attachEventHandler('click', 'functionName')` associates a function to the button's click event
- `$theme->eventHandler()` must be called **after** placing controls in the theme
- Event handler functions receive `$sender` (the control that fired the event)

### Step 7: Use a Form

File: `modules/helloworld/handlers/main.inc`

```php
<?php
$ui = $MIOLO->getUI();
$form = $ui->getForm('helloworld', 'frmMain');
$theme->setContent($form);
?>
```

File: `modules/helloworld/forms/frmMain.class`

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
            new MTextField('myMsg', '', 'Message', 30, 'Your message to the world'),
            new MTextField('myName', '', 'Name', 20, 'Your name'),
        );
        $this->setFields($fields);

        $buttons = array(
            new MButton('btnHello', 'Click Me!'),
        );
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
        $this->addButton(new MButton('btnBack', 'Back!'));
    }
}
?>
```

**Key concepts:**
- `createFields()` is called automatically by the form constructor
- `setFields()` / `setButtons()` define the form areas
- Event handlers use the naming convention `<buttonName>_click`
- `$this->eventHandler()` in the constructor enables event processing
- Forms are loaded via `$ui->getForm($module, $formName)`

---

## Tutorial 2: Hangman Game

An intermediate tutorial demonstrating state management, dynamic controls, and complex event handling.

### Module Structure

```
modules/hangman/
├── etc/
│   └── module.conf
│   └── words.txt          # word list
├── handlers/
│   ├── handler.class
│   └── main.inc
├── forms/
│   └── frmMain.class
└── html/
    └── images/
        └── hangman.png
```

### Form: frmMain.class

```php
<?php
class frmMain extends MForm
{
    const EASY   = 10;
    const MEDIUM = 5;
    const HARD   = 3;

    function __construct()
    {
        parent::__construct('Hangman Game!');
        $this->setIcon($this->manager->getUI()->getImage('hangman', 'hangman.png'));
        $this->setWidth('470px');
        $this->eventHandler();
    }

    function createFields()
    {
        // Difficulty level selection
        $options = array(
            new MRadioButton('level', '1', '', true, 'Easy (10 errors allowed)'),
            new MRadioButton('level', '2', '', false, 'Medium (5 errors allowed)'),
            new MRadioButton('level', '3', '', false, 'Hard (3 errors allowed)'),
        );

        // Letter buttons (A-Z)
        $link = $this->manager->getCurrentURL();
        $guesses = $this->getGuessLetters();
        for ($letter = 65; $letter <= 90; ++$letter) {
            $char = chr($letter);
            $letters[] = $button = new MLinkButton("guess$char", $char, $link);
            $button->attachEventHandler('click', 'onLetterClick', $char);
            if (strpos($guesses, $char) !== false) {
                $button->color = 'gray';
            }
        }

        // Group controls into panels
        $level = array(
            new MRadioButtonGroup('levelGroup', '', $options, '1', 'vertical'),
            new MButton('btnPlay', 'Play!'),
        );
        $guess = array(
            new MTextHeader('choice', '1', 'Choose a letter:'),
            new MText('guessWord', ''),
            new MSpacer('20px'),
            new MText('guessErrors', ''),
            new MLinkButtonGroup('letters', '', $letters, 'horizontal', 'none'),
            new MLinkButton('giveUp', 'Give up?', $link),
        );
        $finished = array(
            new MTextHeader('finished', '1', ''),
            new MText('word', ''),
            new MSpacer('20px'),
            new MLinkButton('again', 'Play again?', $link),
        );

        $fields = array(
            new MLabel('Guess the word! If you exceed the error limit, you lose!'),
            new MBaseGroup('choiceGroup', 'Choose difficulty', $level, 'vertical', 'css'),
            new MBaseGroup('guessGroup', '', $guess, 'vertical', 'css'),
            new MBaseGroup('finishedGroup', '', $finished, 'vertical', 'css'),
        );
        $this->setFields($fields);
        $this->btnPlay->attachEventHandler('click', 'onBtnPlayClick');
        $this->giveUp->attachEventHandler('click', 'onFinished', 'lose');
        $this->setStatus('level');
        $this->defaultButton = false;
    }

    // --- State Management via ViewState ---

    function setLevel($level)
    {
        $map = array('1' => self::EASY, '2' => self::MEDIUM, '3' => self::HARD);
        $this->page->setViewState('level', $map[$level]);
    }
    function getLevel()       { return $this->page->getViewState('level'); }
    function setErrors($e)    { $this->page->setViewState('errors', $e); }
    function getErrors()      { return $this->page->getViewState('errors'); }
    function setWord($w)      { $this->page->setViewState('secretword', $w); }
    function getWord()        { return $this->page->getViewState('secretword'); }
    function setGuessWord($w) { $this->page->setViewState('guessword', $w); /* ... */ }
    function getGuessWord()   { return $this->page->getViewState('guessword'); }
    function setGuessLetters($l) { $this->page->setViewState('guessletters', $l); }
    function getGuessLetters()   { return $this->page->getViewState('guessletters'); }

    function setStatus($status)
    {
        $this->setFieldAttr('choiceGroup', 'visible', ($status == 'level'));
        $this->setFieldAttr('guessGroup', 'visible', ($status == 'play'));
        $this->setFieldAttr('finishedGroup', 'visible', ($status == 'finished'));
    }

    // --- Event Handlers ---

    function onBtnPlayClick($sender)
    {
        $level = $this->levelGroup->getValue();
        $this->setLevel($level);
        // Load random word from file
        $file = $this->manager->getModulePath('hangman', 'etc/words.txt');
        $words = preg_split("/[\s,]+/", file_get_contents($file));
        do {
            $word = strtoupper($words[rand(0, count($words) - 1)]);
        } while (strlen($word) < 5 || !preg_match('/^[a-z]*$/i', $word));

        $this->setGuessWord(str_repeat('_', strlen($word)));
        $this->setErrors(0);
        $this->setWord($word);
        $this->setGuessLetters('');
        $this->setStatus('play');
    }

    function onLetterClick($sender, $param)
    {
        $letter = $param;
        $this->setGuessLetters($this->getGuessLetters() . $letter);
        $word = $this->getWord();
        $guessWord = $this->getGuessWord();

        $pos = 0;
        $success = false;
        while (($pos = strpos($word, $letter, $pos)) !== false) {
            $guessWord[$pos] = $letter;
            $success = true;
            $pos++;
        }
        $this->setGuessWord($guessWord);

        if ($success) {
            if ($guessWord === $word) {
                $this->onFinished(null, 'win');
            }
        } else {
            $errors = $this->getErrors() + 1;
            $this->setErrors($errors);
            if ($errors >= $this->getLevel()) {
                $this->onFinished(null, 'lose');
            }
        }
        $this->setStatus('play');
    }

    function onFinished($sender, $param)
    {
        $this->setGuessLetters('');
        $this->setFieldValue('word', 'The word was: ' . $this->getWord());
        $this->setFieldValue('finished', ($param == 'lose') ? 'You lost!' : 'You won!');
        $this->setStatus('finished');
    }
}
?>
```

**Key concepts demonstrated:**
- `setViewState` / `getViewState` for maintaining state across HTTP round-trips
- `attachEventHandler` with parameters
- Dynamic control visibility with `setFieldAttr`
- `MLinkButton` for POST-based links
- `MRadioButtonGroup` for option selection
- Game logic entirely server-side

---

## Tutorial 3: Complete CRUD Module

A full module with database access, business classes, persistence mapping, search, create, edit, and delete.

### Data Model

```sql
CREATE TABLE Course (
    IdCourse   INTEGER NOT NULL PRIMARY KEY,
    Name       VARCHAR(100)
);

CREATE TABLE Student (
    IdStudent  INTEGER NOT NULL PRIMARY KEY,
    Name       VARCHAR(100),
    Gender     CHAR(1),
    Phone      CHAR(20),
    IdCourse   INTEGER REFERENCES Course
);
```

### Module Configuration

File: `modules/example/etc/module.conf`

```xml
<?xml version="1.0" encoding="ISO-8859-1" standalone="yes"?>
<conf>
    <options><startup>example</startup></options>
    <db>
        <example>
            <system>sqlite</system>
            <host>localhost</host>
            <name><miolo>/modules/example/sql/data.db</name>
            <user>miolo</user>
            <password>miolo</password>
        </example>
    </db>
    <login><check>0</check></login>
</conf>
```

### Business Class

File: `modules/example/classes/curso.class`

```php
<?php
class BusinessExampleCourse extends MBusiness
{
    public $idCourse;
    public $name;
    public $students;

    function __construct($data = NULL)
    {
        parent::__construct('example', $data);
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

    function getEnrolledStudents()
    {
        $this->retrieveAssociation('students');
    }
}
?>
```

### Persistence Mapping

File: `modules/example/classes/map/curso.xml`

```xml
<?xml version="1.0" encoding="ISO-8859-1" standalone="yes"?>
<map>
    <moduleName>example</moduleName>
    <className>curso</className>
    <tableName>Course</tableName>
    <databaseName>example</databaseName>
    <attribute>
        <attributeName>idCourse</attributeName>
        <columnName>IdCourse</columnName>
        <key>primary</key>
        <idgenerator>seq_course</idgenerator>
    </attribute>
    <attribute>
        <attributeName>name</attributeName>
        <columnName>Name</columnName>
    </attribute>
    <attribute>
        <attributeName>students</attributeName>
    </attribute>
    <association>
        <toClassModule>example</toClassModule>
        <toClassName>aluno</toClassName>
        <cardinality>oneToMany</cardinality>
        <target>students</target>
        <inverse>true</inverse>
        <entry>
            <fromAttribute>idCourse</fromAttribute>
            <toAttribute>idCourse</toAttribute>
        </entry>
    </association>
</map>
```

### Handler Structure

```
handlers/
├── handler.class          # class HandlerExample extends MHandler
├── main.inc               # Main panel with action icons
├── curso.inc              # Routes to curso/* handlers
└── curso/
    ├── find.inc           # Search courses
    ├── new.inc            # Create new course
    └── main.inc           # View/edit/delete course (MCompoundForm)
```

### Main Handler

File: `modules/example/handlers/main.inc`

```php
<?php
$ui = $MIOLO->getUI();
$theme->clearContent();
$close = $MIOLO->getActionURL('example', 'main');

$panel = new MActionPanel('pnlExample', 'Example Module', '', $close,
    $ui->getImage($module, 'tools1.png'));
$panel->addAction('Students', $ui->getImage($module, 'user.png'),
    $module, 'main:student:find');
$panel->addAction('Courses', $ui->getImage($module, 'system.png'),
    $module, 'main:course:find');

$a = $context->shiftAction();
$handled = $MIOLO->invokeHandler($module, $a);

if (!$handled) {
    $theme->insertContent($panel);
}
?>
```

### New Course Form

File: `modules/example/forms/curso/frmCourseNew.class`

```php
<?php
class frmCourseNew extends MForm
{
    function __construct()
    {
        parent::__construct('New Course');
        $this->eventHandler();
    }

    function createFields()
    {
        $fields = array(
            new MTextField('name', '', 'Name', 25, 'Max 30 characters'),
        );
        $this->setFields($fields);

        $buttons = array(
            new MButton('btnNew', 'Create Course'),
        );
        $this->setButtons($buttons);

        $validators = array(
            new MRequiredValidator('name'),
        );
        $this->setValidators($validators);
    }

    function btnNew_click()
    {
        global $MIOLO, $page, $module;

        $data = $this->getData();
        $course = $MIOLO->getBusiness($module, 'course');
        $course->setData($data);

        try {
            $course->save();
            $go = $MIOLO->getActionURL('example', 'main:course', $course->idCourse);
            $page->redirect($go);
        } catch (Exception $e) {
            $this->addError($e->getMessage());
        }
    }
}
?>
```

### Search Grid

File: `modules/example/grids/gridCourses.class`

```php
<?php
class gridCourses extends MDataGrid
{
    function __construct()
    {
        global $MIOLO;

        $hrefCourse = $MIOLO->getActionURL('example', 'course:main', '#0#',
            array('form' => 'data'));

        $columns = array(
            new MDataGridHyperlink('name', 'Courses', $hrefCourse, '100%',
                true, null, true),
        );

        $filter = MForm::getFormValue('course') . '%';
        $objCourse = $MIOLO->getBusiness('example', 'course');
        $query = $objCourse->listByName(strtoupper("'$filter'"));
        $hrefGrid = $MIOLO->getActionURL($module, $self);

        parent::__construct($query, $columns, $hrefGrid, 15);
        $this->setLinkType('hyperlink');
    }
}
?>
```

### Typical Workflow

1. User accesses `http://host/index.php/example`
2. `main.inc` shows action panel with "Students" and "Courses" icons
3. Clicking "Courses" navigates to `main:course:find`
4. `frmCourseFind` shows a search field and `gridCourses` with results
5. Clicking a course navigates to `main:course:main&item=<id>`
6. `frmCourse` (MCompoundForm) shows course info, edit/delete actions
7. Clicking "Edit" loads `frmCourseData`; "Delete" loads `frmCourseDel`
8. "New Course" navigates to `main:course:new` showing `frmCourseNew`
