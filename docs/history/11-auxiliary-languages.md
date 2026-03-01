# Auxiliary Languages: C++, Java, and PostScript

**When PHP wasn't enough, you reached for other languages. Not as
a choice — as a necessity.**

Modern PHP development is self-contained. Composer packages cover nearly
every need. If you need PDF generation, there's DOMPDF and mPDF. If you
need reports, there are libraries for that. If you need a CLI tool, PHP
has `symfony/console`. If you need image manipulation, there's Imagick
and Intervention.

In 1999, PHP was a thin scripting layer. It could generate HTML and talk
to databases. For anything else — printing documents, building GUI tools,
generating reports — you needed another language.

---

## PostScript: Writing Printer Instructions by Hand

### The Problem

UNIVATES needed to print academic transcripts (*historicos escolares*).
In 1999, there was no PDF library for PHP:

- **PDFlib** — commercial, expensive
- **FPDF** — not yet created (2001)
- **TCPDF** — not yet created (2002)
- **DOMPDF** — not yet created (2004)
- **wkhtmltopdf** — not yet created (2008)
- **Browser print-to-PDF** — not yet a thing

The only free path to a printed document was **PostScript** — the page
description language that every laser printer understood natively.

### The Solution: PSLib

**File:** `classes/pslib/pslib.class.php`

Vilson Gartner wrote a PHP library that generates PostScript bytecode
through string concatenation. Every element on the page is a hand-
calculated coordinate and a PostScript operator:

```php
class postscript
{
    public $string = "";  // accumulates all PS bytecode

    public function __construct($fname, $author, $title, $orientation, $fonts)
    {
        $this->string .= "%!PS-Adobe-3.0 \n";
        $this->string .= '%%Creator: ' . $author . " using PSLib\n";
        $this->string .= "%%DocumentMedia: A4 595 842 0 () ()\n";

        // ISOLatin1 encoding — essential for Portuguese accents
        $this->string .= "/FL [/Arial /Arial-Bold /Times /Helvetica ...] D \n";
        $this->string .= "/ReencodeISO { dup dup findfont dup length dict ... } D \n";
    }
}
```

Drawing a line:

```php
public function line($xfrom, $yfrom, $xto, $yto, $linewidth)
{
    $this->string .= $linewidth . " lw  ";        // set line width
    $this->string .= $xfrom . ' ' . $yfrom . " mv \n";  // moveto
    $this->string .= $xto   . ' ' . $yto   . " lt \n";  // lineto
    $this->string .= "sk \n";                      // stroke
}
```

Showing text with justification:

```php
public function show($text, $red, $green, $blue, $justify)
{
    switch ($justify) {
        case 'c': // center
            $this->string .= "( $text ) dup stringwidth pop 2 div neg 0 rmoveto s \n";
            break;
        case 'r': // right
            $this->string .= "( $text ) dup stringwidth pop neg 0 rmoveto s \n";
            break;
        default:  // left
            $this->string .= "( $text ) s \n";
    }
}
```

This is **programming in two languages at once**: PHP code generates
PostScript code. The PHP runs on the server, produces a `.ps` file,
and that file is sent to the printer (or viewed with GhostView).

### What Made It Hard

- Every position is an absolute coordinate in points (72 points per
  inch). There's no automatic layout, no line wrapping, no tables.
- Accented characters (essential for Portuguese) required implementing
  ISOLatin1 font re-encoding — a PostScript subroutine that redefines
  character mappings in the font
- Different printers interpreted PostScript slightly differently
- Debugging meant opening the `.ps` file in GhostView and visually
  inspecting where text appeared

### The Historical Significance

PSLib was the **first code in the entire project**. Before MIOLO, before
SAGU, before everything — there was a need to print transcripts. This
library, published on SourceForge and Codigo Livre (Brazil's early
open-source forge), was modeled after the commercial PDFlib API so
developers familiar with that library could use PSLib as a free drop-in.

It represents the fundamental philosophy of the project: if a tool
doesn't exist and you can't afford the commercial version, build it.

---

## Java: When PHP Can't Generate Reports

### The Problem

As MIOLO grew to serve dozens of universities, the reporting needs
outgrew PostScript. Institutions needed:

- PDF reports with dynamic data
- Export to Excel, CSV, HTML, RTF
- Complex layouts with charts and images
- Sub-reports and grouped data

PHP had no native report engine. But Java had **JasperReports** — a
mature, full-featured report generator.

### The Solution: Java as a Subprocess

**Files:** `classes/extensions/jasper/MJasper.java`,
`classes/extensions/jasper/compile.sh`,
`classes/ui/reports/mjasperreport.class.php`

MIOLO's approach: write a Java CLI runner, compile it with `javac`,
and invoke it from PHP as an external process via system calls.

#### The Java Runner

```java
public class MJasper
{
    public static void main(String[] args) throws Exception
    {
        String path       = args[0];
        String cmdParam   = args[1];  // "&"-separated key~value pairs
        String dbUser     = args[2];
        String dbPass     = args[3];
        String jdbcDriver = args[4];
        String jdbcDb     = args[5];

        // Load and compile the report template
        if (fileName.endsWith(".jrxml")) {
            JasperDesign design = JRXmlLoader.load(fileName);
            report = JasperCompileManager.compileReport(design);
        } else {
            report = (JasperReport) JRLoader.loadObjectFromFile(fileName);
        }

        // Fill with data from database
        impressao = JasperFillManager.fillReport(report, parametros, conn);

        // Export to requested format (PDF, XLS, HTML, CSV, RTF, ODT, TXT)
        if (fileType.equalsIgnoreCase("PDF")) {
            JRPdfExporter exporter = new JRPdfExporter();
            exporter.setParameter(JRExporterParameter.OUTPUT_FILE_NAME, fileOut);
            exporter.exportReport();
        }
    }
}
```

#### Compilation

```bash
#!/bin/bash
export LANG=pt_BR
javac -classpath $PWD/lib/deps/*:$PWD/lib/ext/*:$PWD/lib/jasperreports/*:$PWD/ MJasper.java
```

#### PHP Bridge

The PHP side constructs the command string with all necessary parameters
(report path, database credentials, JDBC driver, output format) and
invokes the Java process. The boundary is always a process: PHP
generates parameters, spawns a Java process, Java generates the report,
PHP serves the output file.

### The Type Bridge

PHP and Java have different type systems. To pass typed parameters,
MIOLO uses string prefixes:

| Prefix | PHP Type | Java Type |
|---|---|---|
| `int_` | integer | `java.lang.Integer` |
| `dbl_` | float | `java.lang.Double` |
| `tsp_` | string (datetime) | `java.sql.Timestamp` |
| `boo_` | boolean | `java.lang.Boolean` |
| `str_` | string | `java.lang.String` |

Parameters are serialized as `key~value` pairs separated by `&`:

```
int_year~2024&str_name~John&boo_active~true
```

The Java runner parses these prefixes and constructs the correct
Java objects for JasperReports parameter binding.

### Evolution: PHPJasper

Later versions replaced the custom Java runner with the PHPJasper
Composer library — which still invokes Java as an external process
under the hood:

```php
use PHPJasper\PHPJasper;

$jasper = new PHPJasper;
$options = [
    'format' => ['pdf'],
    'db_connection' => [
        'driver'   => 'postgres',
        'host'     => $dbHost,
        'database' => $dbName,
    ],
];
$jasper->process($input, $output, $options)->run();
```

---

## C++: When You Need a GUI

### The Problem

System administrators at universities needed to install and configure
MIOLO. In 2002, not everyone was comfortable with command-line tools.
PHP couldn't build desktop GUIs.

### The Solution: Qt3 Desktop Applications

**Files:** `etc/setup/setup.cpp`, `etc/MAD/main.cpp`

#### The Graphical Installer

```cpp
// MIOLO Installation Program - August/2002
// Author: Vilson Cristiano Gartner

#include "setup.h"
#include <qwidget.h>
#include <qapplication.h>
#include <qfiledialog.h>
#include <qmessagebox.h>
#include <qprogressbar.h>
```

A full Qt3 wizard application with:
- Welcome screen
- License agreement
- Installation path selection (with file browser dialog)
- Database configuration
- Apache configuration
- Progress bar during file copy
- Completion summary

#### The MAD (MIOLO Administration Desktop) Tool

```cpp
#include <qapplication.h>
#include "mainform.h"

int main(int argc, char **argv)
{
    qDebug("\nLoading MAD Tool...");
    QApplication a(argc, argv);
    MainForm w;
    w.show();
    a.connect(&a, SIGNAL(lastWindowClosed()), &a, SLOT(quit()));
    return a.exec();
}
```

A desktop administration tool with forms for configuring MIOLO
(database connections, module settings, theme selection).

#### The Build System

```makefile
# Generated by qmake (Qt 3.3.6)
CC       = gcc
CXX      = g++
CXXFLAGS = -pipe -Wall -W -O2 -D_REENTRANT -DQT_THREAD_SUPPORT
LIBS     = -L/usr/share/qt3/lib -L/usr/X11R6/lib -lqt-mt -lXext -lX11 -lm -lpthread
MOC      = /usr/share/qt3/bin/moc
UIC      = /usr/share/qt3/bin/uic
```

Qt3, X11, pthreads — the full C++ desktop toolkit stack of the era.

---

## The Language Ecosystem

MIOLO's auxiliary language usage reflects the gaps in PHP's ecosystem:

| Need | PHP Could Do It? | Auxiliary Language | Tool |
|---|---|---|---|
| Print documents | No (no PDF libs) | PostScript | PSLib |
| Complex reports | No | Java | JasperReports |
| GUI installer | No | C++ (Qt3) | setup.cpp |
| GUI admin tool | No | C++ (Qt3) | MAD |
| i18n extraction | Partially | Shell (bash) | xgettext scripts |
| Code migration | No | Shell (bash) | standardize.sh |
| Package building | No | Shell (bash) | create-miolo-package.sh |

### The Modern Equivalents

| 1999-2005 | 2020+ |
|---|---|
| PostScript generation in PHP | DOMPDF, mPDF, wkhtmltopdf |
| Java subprocess for reports | PHP report libraries, or still Jasper |
| C++ Qt3 GUI installer | `apt install`, Docker, web-based installers |
| Shell scripts for CI/CD | GitHub Actions, GitLab CI, Jenkins pipelines |
| Shell scripts for code migration | Rector, PHP-CS-Fixer, PHPStan |

---

## What This Tells Us

PHP in 1999 was a specialist tool — it did web pages and database
queries, and that was about it. For everything else, you reached for
another language. This multi-language approach was not unusual; it was
the standard. Java EE projects used Java for business logic and
JavaScript for the browser. .NET projects used C# and T-SQL. MIOLO
used PHP, PostScript, Java, C++, and shell scripts.

What's remarkable is that a small team at a Brazilian university
was comfortable working across all these languages and tools. The
PostScript library required understanding a stack-based programming
language. The Qt3 installer required C++ and the X11 window system.
The JasperReports integration required Java, JDBC, and report design.
This polyglot capability was essential — PHP alone wasn't enough to
build a complete institutional software platform.

Today, PHP's ecosystem has grown to fill most of these gaps. But the
instinct to reach for the right tool regardless of language — that's
a timeless engineering principle.
