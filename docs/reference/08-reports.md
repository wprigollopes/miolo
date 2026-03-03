# Reports & PDF Generation

[Back to Index](index.md)

## Overview

MIOLO supports PDF report generation through two libraries:
- **ezPDF** (R&OS Ltd) -- Direct PDF construction
- **DOMPDF** -- HTML-to-PDF conversion

### Class Hierarchy

```
MReport
├── MCrystalReport
├── MEzPDFReport           # ezPDF wrapper
└── MJasperReport

CPdf (ezPDF low-level)
└── MCPdf
CEzPdf (ezPDF high-level)
└── MCEzCPdf

MGrid
└── MPDFReport             # Grid-layout PDF report

MForm
└── MDOMPDFReport          # DOMPDF-based report
```

---

## MEzPDFReport

Wrapper for the ezPDF library.

```php
$report = new MEzPDFReport($type = '2', $orientation = 'portrait');
// $type: '1' = MCPdf (low-level), '2' = MCEzCPdf (high-level)
// $orientation: 'portrait' or 'landscape'
```

### Key Methods

```php
$report->setFont('Helvetica.afm');    // or 'tahoma.afm', 'Courier.afm'
$report->newPage();                    // start new page
$pdf = $report->getPdf();             // access underlying PDF object
$output = $report->getOutput();       // get PDF content as string
$report->execute();                    // generate, save to disk, redirect to browser
```

---

## MPDFReport (Grid-Layout Reports)

Extends `MGrid` to create tabular PDF reports.

```php
$columns = array(
    new MPDFReportColumn('name', 'Name', 'left', false, 200),
    new MPDFReportColumn('email', 'Email', 'left', false, 200),
    new MPDFReportColumn('phone', 'Phone', 'center', false, 100),
);

$report = new MPDFReport($data, $columns, $pageLength = 1);
$report->setOption('showLines', 1);
$report->setOption('fontSize', 10);
$report->setWidth(100);  // percentage of page width
$report->execute();
```

### Report Options

| Option | Values | Default | Description |
|--------|--------|---------|-------------|
| `showLines` | 0, 1, 2 | 0 | 0=no borders, 1=borders, 2=borders + row lines |
| `showHeadings` | 0, 1 | 1 | Show column headers |
| `shaded` | 0, 1, 2 | 0 | 0=none, 1=alternate rows, 2=all rows |
| `shadeCol` | (r,g,b) | (0.8,0.8,0.8) | Primary shade color |
| `shadeCol2` | (r,g,b) | (0.7,0.7,0.7) | Alternate shade color |
| `fontSize` | number | 10 | Data font size |
| `textCol` | (r,g,b) | (0,0,0) | Text color |
| `titleFontSize` | number | 14 | Title font size |
| `rowGap` | number | 2 | Vertical gap between rows |
| `colGap` | number | 5 | Horizontal gap between columns |
| `lineCol` | (r,g,b) | (0,0,0) | Line color |
| `xPos` | left/right/center/coord | center | Horizontal position |
| `xOrientation` | left/right/center | center | Horizontal orientation |
| `width` | number | 0 | Exact table width |
| `maxWidth` | number | 596 | Maximum width |

### Column Options

```php
$col = new MPDFReportColumn('name', 'Name', 'left', false, 200);
// Options per column:
// 'innerLineThickness' => 1
// 'outerLineThickness' => 1
// 'protectRows' => 1
```

### Overridable Methods

```php
class MyReport extends MPDFReport
{
    function generateReportHeader()   { /* report header (first page) */ }
    function generatePageHeader()     { /* every page header */ }
    function generatePageTitle()      { /* every page title */ }
    function generatePageFooter()     { /* every page footer */ }
    function generateEmptyMsg()       { /* message when no data */ }
}
```

### Triggers

```php
$report->setTrigger('BeforeNewPage', $class, $method, $param);
$report->setTrigger('AfterNewPage', $class, $method, $param);
```

### Page Control

```php
$report->pageBreak();       // force page break
$report->clearPageBreak();  // inhibit automatic page break
```

---

## Low-Level PDF API (Cpdf)

Unit: 1/72 inch = 1 point. A4 = 595.28 x 841.89 points. Origin (0,0) = bottom-left.

### Text

```php
$pdf = $report->getPdf();
$cpdf = $pdf->pdf;  // access Cpdf object

$cpdf->selectFont('Helvetica.afm');
$cpdf->addText(50, 700, 12, 'Hello World');

// Text with word wrap (returns overflow text)
$overflow = $cpdf->addTextWrap(50, 700, 500, 12, $longText, 'left');

// Font metrics
$height = $cpdf->getFontHeight(12);
$width  = $cpdf->getTextWidth(12, 'Hello');
```

### Drawing

```php
$cpdf->line(50, 50, 550, 50);           // line
$cpdf->rectangle(50, 50, 500, 300);     // rectangle
$cpdf->setColor(0.8, 0.2, 0.2);        // fill color (RGB 0-1)
$cpdf->setStrokeColor(0, 0, 0);        // stroke color
$cpdf->setLineStyle(1, '', '', array(3, 3)); // dashed line
```

### Images

```php
$cpdf->addJpegFromFile('photo.jpg', $x, $y, $width);
$cpdf->addPngFromFile('logo.png', $x, $y, $width, $height);
```

### State Management

```php
$cpdf->saveState();
// ... modify colors, fonts, etc.
$cpdf->restoreState();  // revert to saved state
```

## Higher-Level API (CezPdf)

```php
$pdf = $report->getPdf();
$ez = $pdf->pdf;  // access CezPdf object

// Margins
$ez->ezSetCmMargins(2, 2, 2, 2);  // centimeters
$ez->ezSetMargins(56, 56, 56, 56); // points

// Text
$ez->ezText('Hello World', 14, array('justification' => 'center'));

// Vertical positioning
$ez->ezSetDy(-20);              // move down 20 points
$ez->ezSetY(700);               // set absolute Y position

// Page numbers
$ez->ezStartPageNumbers(300, 20, 10, 'center', '{PAGENUM} of {TOTALPAGENUM}');

// New page
$ez->ezNewPage();

// Images
$ez->ezImage('logo.png', 5, 200, 'none', 'center');

// Tables
$data = array(
    array('Name' => 'Alice', 'Age' => 30),
    array('Name' => 'Bob', 'Age' => 25),
);
$ez->ezTable($data, '', 'People', array(
    'showHeadings' => 1,
    'shaded' => 1,
));
```

### PDF Objects (Headers/Footers)

```php
$id = $cpdf->openObject();
// ... draw header/footer content
$cpdf->closeObject();
$cpdf->addObject($id, 'all');  // 'add', 'all', 'odd', 'even', 'next'

// Stop object from appearing on future pages
$cpdf->stopObject($id);
```

---

## DOMPDF Integration

Converts HTML/CSS to PDF. Mostly CSS2.1 compliant.

### MDOMPDF

```php
$dompdf = new MDOMPDF($paper = 'a4', $orientation = 'portrait');
$dompdf->setInput($htmlString, $params);
$dompdf->execute();  // generate PDF, save, redirect to browser
```

### MDOMPDFReport

Extends `MForm`. Form controls are rendered as PDF instead of HTML.

```php
class MyDOMReport extends MDOMPDFReport
{
    function __construct()
    {
        parent::__construct('portrait', 'a4');
    }

    function createFields()
    {
        $this->addField(new MLabel('Report Title'));
        // ... add controls that will be rendered as PDF
    }

    function generate()
    {
        // renders form controls as PDF
        parent::generate();
    }
}
```

### Direct DOMPDF Usage

```php
$dompdf = new DOMPDF();
$dompdf->load_html('<h1>Hello</h1><p>World</p>');
$dompdf->set_paper('a4', 'portrait');
$dompdf->render();
$dompdf->stream('report.pdf', array(
    'Attachment' => 0,     // 0 = display inline, 1 = force download
    'compress' => 1,
));
```
