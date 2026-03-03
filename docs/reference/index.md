# MIOLO Framework Documentation

> Comprehensive reference documentation for the MIOLO PHP web application framework,
> compiled from the official guides (v2.0 -- v2.5).

## Table of Contents

### Core Documentation

1. **[Introduction & Architecture](01-introduction.md)**
   Overview, layered architecture (MVC), directory structure, key files

2. **[Installation & Configuration](02-configuration.md)**
   Apache setup, `miolo.conf` reference, module configuration, CLI utility (`bin/miolo.php`)

3. **[Development Guide](03-development.md)**
   Modules, handlers, forms, events, page lifecycle, URL structure, global variables, themes

### Component References

4. **[UI Controls (Widgets)](04-ui-controls.md)**
   Complete widget class hierarchy, themes, form controls, grids, panels, validators

5. **[Database Access (DAO)](05-database.md)**
   `MDatabase`, `MSQL`, `MQuery`, transactions, pagination, filtering -- with code examples

6. **[Object Persistence](06-persistence.md)**
   XML mapping, associations, `RetrieveCriteria` queries, value converters, cursors, inheritance

7. **[AJAX, Dialogs & Windows](07-ajax-dialogs-windows.md)**
   AJAX with CPAINT/Prototype, `MFormDialog`, `MWindow`, client-side JavaScript API

8. **[Reports & PDF Generation](08-reports.md)**
   `MEzPDFReport`, `MPDFReport`, DOMPDF integration, report options, low-level PDF API

### Quick References

9. **[API Reference](09-api-reference.md)**
   Method signatures for all major classes in table format

10. **[Tutorials](10-tutorials.md)**
    Hello World, Hangman game, complete CRUD module -- step-by-step

11. **[Internationalization (i18n)](11-i18n.md)**
    GNU gettext integration, `_M()` function, `.po`/`.mo` translation files, adding languages

---

## About MIOLO

MIOLO is a framework for building web-accessible information systems, written in PHP. It uses
object-oriented programming concepts to generate HTML pages and PDF files. With a modular,
component-based design and layered architecture, MIOLO acts as the "kernel" for application
systems. Multiple systems can be integrated as modules of a larger platform.

**Core features:**
- UI controls written in PHP, rendered as HTML
- User authentication and access permission control
- Database access abstraction layer (DAO) supporting PostgreSQL, MySQL, Oracle, Firebird, SQLite, MSSQL
- Transparent object persistence layer with XML mapping
- Session and state management
- Log and trace/debug mechanisms
- Event-driven WebForm model
- Form input validation
- Theme-based layout customization with CSS
- PDF report generation (ezPDF and DOMPDF)
- AJAX support

**Authors:** Vilson Gartner (vilson@miolo.org.br), Ely Edison Matos (ely.matos@ufjf.edu.br)

**Organizations:** Universidade Federal de Juiz de Fora (UFJF), Solis -- Cooperativa de Solucoes Livres

---

## Source Documents

This documentation was compiled from the following original documents:

| Document | Format | Description |
|----------|--------|-------------|
| MIOLO 2.5 UserGuide | ODT/PDF | Comprehensive narrative guide (v0.1, 2008) |
| MIOLO 2.5 Fundamentals | ODT/PDF | Architecture and concepts |
| MIOLO 2.5 Quick Reference | ODT | API method signatures |
| MIOLO 2.0 Reference Guide | DOC | Detailed class-by-class reference (v0.1, 2005) |
| MIOLO 2.0 Components | ODT | DAO, Persistence, AJAX, DOMPDF with code examples |
| Hello World Tutorial | DOC/PDF | Beginner step-by-step tutorial |
| Hangman Tutorial | DOC/PDF | Intermediate tutorial with state management |
| Example Module Tutorial | DOC/PDF | Complete CRUD module walkthrough |
| Installation Manual (pt-BR) | ODT/PDF | Installation, configuration, CLI tool (Solis, 2012) |
| Course Material (Mar 2009) | PDF | Module creation tutorial (PostgreSQL) |
