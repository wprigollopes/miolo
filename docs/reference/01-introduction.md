# Introduction & Architecture

[Back to Index](index.md)

## What is MIOLO?

MIOLO is a framework for building web-accessible information systems written in PHP. It uses
JavaScript and OOP (Object-Oriented Programming) concepts, generating HTML pages and PDF files.
With a modular, component-based design and layered architecture, MIOLO acts as the "kernel"
for all created systems. Multiple systems can be easily integrated, functioning as modules of
a more complex system.

**Prerequisites:** Knowledge of PHP programming and OOP.

## Architecture

MIOLO adopts a layered architecture enabling MVC (Model-View-Controller) pattern implementation.

```
┌─────────────────────────────────────────────────┐
│              User Interface (UI)                │  View
│   Controls, Forms, Themes, Menus, Reports       │
├─────────────────────────────────────────────────┤
│                  Handlers                       │  Controller
│   Request processing, execution flow            │
├─────────────────────────────────────────────────┤
│              MIOLO (Facade)                     │  Integration
│   Singleton integrating all layers              │
├─────────────────────────────────────────────────┤
│                Business                         │  Model
│   Domain classes, business rules (MBusiness)    │
├─────────────────────────────────────────────────┤
│                Database                         │  Resource
│   DAO layer + Object Persistence layer          │
├─────────────────────────────────────────────────┤
│            Utils and Services                   │  Resource
│   Session, Auth, Logging, i18n                  │
└─────────────────────────────────────────────────┘
```

### Layer Details

| Layer | Role | MVC | Description |
|-------|------|-----|-------------|
| **MIOLO** | Integration | -- | Main class (Singleton + Facade). Exposes methods integrating all layers. |
| **UI** | Presentation | View | Controls rendering HTML, PDF generation, JavaScript. Forms, menus, reports in each module's directory. |
| **Handlers** | Integration | Controller | Process data from the client. Define execution flow and implement use cases. Located in each module's `handlers/` directory. |
| **Business** | Business | Model | Domain classes with business rules. Used by UI and handler layers. |
| **Database** | Resource | -- | Abstracts database access: DAO layer (DBMS-independent) and Object Persistence layer. |
| **Utils/Services** | Resource | -- | Session handling, authentication, logging, i18n, and OS-level utilities. |

## Directory Structure

```
<miolo>/                          # Base installation directory
├── classes/
│   ├── contrib/                  # Third-party classes
│   ├── database/                 # DAO classes
│   ├── doc/                      # Documentation generation
│   ├── etc/                      # Auxiliary files (autoload.xml)
│   ├── extensions/               # Framework extensions
│   ├── ezpdf/                    # ezPDF library (PDF generation)
│   ├── flow/                     # Request execution flow
│   ├── model/                    # Business layer base classes
│   ├── persistence/              # Object persistence classes
│   ├── pslib/                    # PostScript generation
│   ├── security/                 # Authentication, crypto
│   ├── services/                 # Utility/general services
│   ├── ui/
│   │   ├── controls/             # UI control classes
│   │   ├── painter/              # HTML rendering classes
│   │   ├── report/               # PDF report classes
│   │   └── themes/               # Global themes
│   │       ├── miolo2/
│   │       ├── system/
│   │       ├── clean/
│   │       └── ...
│   └── utils/                    # Utility classes
│
├── docs/                         # Documentation
│
├── etc/
│   ├── miolo.conf                # Main configuration file
│   ├── passwd.conf               # Database password storage
│   └── mkrono.conf               # Mkrono library config
│
├── html/
│   ├── index.html                # Entry file (frameset)
│   ├── index.php                 # Front Controller
│   ├── downloads/
│   ├── images/
│   ├── reports/
│   └── scripts/                  # JavaScript files
│
├── locale/                       # Internationalization
│
├── modules/
│   ├── admin/                    # MAD (administration) module
│   ├── common/                   # Common module
│   ├── tutorial/                 # Tutorial module
│   └── <your-modules>/
│
└── var/
    ├── db/                       # SQLite databases
    ├── log/                      # Log files
    ├── report/                   # Generated PDF reports
    └── trace/                    # Debug trace files
```

### Module Directory Structure

Each module follows a standard layout:

```
modules/<module>/
├── classes/                      # Business/domain classes
│   └── map/                      # XML persistence mappings
├── forms/                        # Form classes (MForm subclasses)
│   └── <object>/                 # Subdirectory per domain object
├── grids/                        # Grid definitions
├── handlers/                     # Handler classes
│   ├── handler.class             # Module handler (extends MHandler)
│   ├── main.inc                  # Main handler (entry point)
│   └── <object>/                 # Subdirectory per domain object
│       ├── find.inc
│       ├── new.inc
│       └── main.inc
├── menus/                        # Menu definitions
├── reports/                      # Report definitions
├── sql/                          # SQL scripts
├── inc/                          # Include files
├── etc/
│   └── module.conf               # Module configuration
└── html/
    ├── images/
    └── files/
```

## Key Files

| File | Description |
|------|-------------|
| `html/index.html` | Entry file accessed by the web server. Creates a frameset that loads `index.php`. Prevents framework URLs from displaying in the browser. |
| `html/index.php` | Front Controller. Instantiates the MIOLO object and executes `HandlerRequest`. |
| `etc/miolo.conf` | Main XML configuration file for environment settings. |
| `classes/support.inc` | Global framework functions. |
| `classes/miolo.class` | Main MIOLO class (Singleton + Facade). |

## Design Patterns Used

| Pattern | Where | Purpose |
|---------|-------|---------|
| **Singleton** | `MIOLO` class | Single framework instance |
| **Facade** | `MIOLO` class | Unified interface to all subsystems |
| **Front Controller** | `index.php` | Single entry point for all requests |
| **Factory** | `MUI` class | Creates forms, grids, reports by name |
| **MVC** | Architecture | Separation of concerns across layers |
| **Data Access Objects** | Database layer | DBMS-independent data access |
| **Persistent Object** | Persistence layer | Transparent object-relational mapping |

## Supported Databases

PostgreSQL, MySQL, Oracle, Firebird, SQLite, MSSQL, ODBC
