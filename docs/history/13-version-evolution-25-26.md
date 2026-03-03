# Version Evolution: v2.5 and v2.6

**MIOLO continued evolving after v2.0, with v2.5 modernizing PHP conventions
and v2.6 adding mobile support and Keycloak authentication. The file count
jumped from 8,675 (v2.0) to 9,947 (v2.5) to 39,073 (v2.6) — though most
of v2.6's growth came from bundled Dojo Toolkit versions.**

This analysis is based on direct comparison of the `archive/v2.0`,
`archive/v2.5`, and `archive/v2.6` git branches. To inspect them:
`git checkout archive/v2.5` or `git checkout archive/v2.6`.

Source: SVN tags `2.5.879` and `2.6.879` from
`https://svn.solis.com.br/miolo/`, both tagged 2026-02-25 by Jenkins CI.

---

## At a Glance

| Metric | v2.0 | v2.5 | v2.6 |
|---|---|---|---|
| Total files | 8,675 | 9,947 | 39,073 |
| PHP class files | 444 | 675 | 720 |
| PHP inc files | 229 | 192 | 200 |
| JavaScript files | 4,318 | 3,849 | 22,483 |
| CSS files | 778 | 506 | 3,065 |
| Image assets | 1,909 | 2,846 | 5,810 |
| SQL files | 16 | 22 | 24 |
| Database engines | 7 | 7 | 7 |
| Application modules | 8 | 11 | 12 |
| UI themes | 6 | 8 | 6 |
| MIOLO version define | `Miolo 2` | `Miolo 2.5` | `Miolo 2.6` |
| SVN tag count | 879 | 879 | 879 |

---

## v2.0 to v2.5: PHP Modernization

### File Extension Convention

The most visible change: `.class` and `.inc` files became `.class.php`
and `.inc.php`. This addressed PHP autoloader compatibility and IDE
support — tools that expected `.php` extensions now worked out of the box.

```
v2.0: classes/miolo.class           → v2.5: classes/miolo.class.php
v2.0: modules/admin/handlers/main.inc → v2.5: modules/admin/handlers/main.inc.php
```

### New Modules

| Module | Purpose |
|---|---|
| `example` | Consolidated examples (replaced `helloworld`, `hangman`) |
| `generator` | Code scaffolding/generation tool |
| `persistence` | Dedicated persistence demo module |

### Extension Changes

**Added:**
- **DomPDF** — HTML-to-PDF rendering (replaced/supplemented EzPDF)
- **phpWsdl** — SOAP web service description language support

**Removed:**
- **Doctrine DBAL** — the 143-file database abstraction layer was dropped,
  suggesting MIOLO's native database layer was considered sufficient

### Theme Evolution

v2.0 themes: clean, kenobi, lemon, miolo, miolo2, system
v2.5 themes: blue, clean, lemon, miolo, miolo2, modern, mystica, system

New themes (`blue`, `modern`, `mystica`) replaced `kenobi`. The theme count
grew from 6 to 8.

### JavaScript Cleanup

JavaScript file count actually *decreased* from 4,318 to 3,849. The old
`dojoroot` was supplemented with `dojoroot-1.2.3` (a pinned version), and
some legacy scripts were cleaned up. The ActiveWidgets, HTMLArea, and Tigra
libraries from v2.0 were removed.

---

## v2.5 to v2.6: Mobile and Authentication

### Mobile Device Support

v2.6's most significant architectural addition was mobile device detection
and mobile-optimized rendering:

- **`Mobile_Detect.php`** — third-party library for user-agent detection
- **`MIOLO::IS_MOBILE_SESSION_ID`** — session-level mobile flag
- **`mobileexample` module** — demo mobile application
- **`mobile` theme** — dedicated mobile-optimized theme
- **`MCalendarMobileField`** — mobile-specific calendar input control

This was v2.6's answer to the mobile web revolution — rather than adopting
responsive design (Bootstrap, etc.), MIOLO used server-side user-agent
detection to serve different themes.

### Keycloak Authentication

v2.6 integrated **Keycloak** (keycloak-js-adapter v14.0.0) for
identity and access management:

```
html/scripts/keycloak/keycloak.js
html/scripts/keycloak/keycloak.min.js
html/scripts/keycloak/keycloak-authz.js
html/scripts/keycloaklogin.js
```

This is significant — Keycloak provides OAuth 2.0 / OpenID Connect
authentication, a major step up from MIOLO's original form-based auth
and LDAP integration.

### New UI Controls

| Control | Purpose |
|---|---|
| `MChart` | Chart/graph rendering |
| `MDropdownMenu` | Dropdown navigation menus |
| `MEventCalendar` | Event-based calendar display |
| `MGrider` | Enhanced grid component |
| `MModularCalendar` | Modular calendar widget |
| `MHtmlElement` | Generic HTML element wrapper |
| `MSubdetailValidators` | Sub-detail form validation |
| `MTimeTextField` | Time input field |

### New Model Classes

```
classes/model/mcustomfield.class.php    — Custom field definitions
classes/model/mcustomvalue.class.php    — Custom field values
```

These suggest a move toward user-configurable forms — fields defined
at runtime rather than code-time.

### Minified JavaScript

v2.6 introduced minified versions of all MIOLO JavaScript files:

```
m_ajax.js      → m_ajax.min.js
m_common.js    → m_common.min.js
m_grid.js      → m_grid.min.js
m_validate.js  → m_validate.min.js
...
```

### Removed: phpWsdl

The SOAP/WSDL extension added in v2.5 was removed in v2.6, suggesting
the industry shift from SOAP to REST APIs.

### The 4x File Count Explosion

v2.6 jumped from 9,947 to 39,073 files — a 4x increase. This is almost
entirely explained by bundled Dojo Toolkit versions:

| Library | v2.5 | v2.6 |
|---|---|---|
| dojoroot (original) | Yes | Yes |
| dojoroot-1.2.3 | Yes | — |
| dojoroot-1.7.2 | — | Yes |
| dojoroot-1.10.0 | — | Yes |
| dojominbuild | — | Yes |

The Dojo internationalization files alone (CLDR locale data, editor plugin
translations) account for thousands of files. The actual MIOLO PHP class
count grew modestly: 675 → 720 (6.7%).

### Theme Consolidation

v2.5 themes: blue, clean, lemon, miolo, miolo2, modern, mystica, system
v2.6 themes: blue, miolo, miolo2, mobile, modern, system

Themes `clean`, `lemon`, and `mystica` were removed. The `mobile` theme
was added. Net change: 8 → 6 themes.

---

## SVN Branch Variants

Beyond the main releases, the SVN repository contains institutional forks:

| Branch | Description |
|---|---|
| `2.0-univates/` | UNIVATES-specific customizations |
| `2.5-php8/` | PHP 8 compatibility work |
| `2.5-ufjf/` | UFJF (Universidade Federal de Juiz de Fora) fork |
| `2.6-fermilab/` | Fermilab (FNAL) fork — international physics lab |
| `php7.2/` | PHP 7.2 compatibility branch |

The Fermilab branch is particularly notable — it shows MIOLO was adopted
by an international research institution outside Brazil.

---

## Architecture Continuity

Despite the version jumps, the core architecture remained stable from
v2.0 through v2.6:

- **Singleton pattern**: `MIOLO::getInstance()` unchanged
- **Database engines**: Same 7 engines (Postgres, MySQL, SQLite, Oracle 8,
  Firebird, MSSQL, ODBC) in all three versions
- **MVC pattern**: Same handler/form/business class structure
- **XML configuration**: Same `<conf>` XML format
- **Painter pattern**: Same MBasePainter/MHTMLPainter separation
- **Persistence layer**: Same PersistentObject/PersistentManager ORM

What changed was the ecosystem around that core: modern auth (Keycloak),
mobile support, updated JavaScript libraries, and better PHP conventions.

---

## Full Version Timeline

| Version | Files | Key Change |
|---|---|---|
| v1.0 | 688 | PHP 4 original |
| v2.0 | 8,675 | Complete PHP 5 rewrite |
| v2.5 | 9,947 | .php extensions, DomPDF, code generator |
| v2.6 | 39,073 | Mobile support, Keycloak auth, Dojo updates |

Each version branch has 879 SVN tags — representing roughly weekly
releases over a 17-year period. The framework was actively maintained
through its entire lifecycle, with the most recent tags dated
February 25, 2026.
