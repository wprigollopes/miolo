# MIOLO Framework

**One of the first PHP frameworks ever built.** Born in 1999 at a small
university in southern Brazil, years before the world had heard of Rails,
Django, Symfony, or Laravel.

---

## Why This Repository Exists

This code sat in a Subversion repository at `svn.solis.com.br/miolo` for
20 years — 9,538 revisions, 31 contributors, spanning from 2006 to 2026,
with roots going back to 1999. It was never on GitHub. Most of the
comments were in Portuguese. Many of the people who built it have moved
on. The story was at risk of being lost.

I decided to recover the MIOLO source code and make it public because
this project deserves to be remembered. Not as a museum piece, but as a
living record of what a small group of developers accomplished under
conditions that modern programmers would find hard to imagine — and as
a codebase that, with care, can still be brought into the present.

This is an effort to **rescue the history**, **translate the codebase**
from Portuguese to English so the international community can read it,
and **modernize the framework** so it can run on current PHP versions.
It is a work in progress.

---

## The Story

MIOLO's name comes from the Portuguese word *miolo* — the soft interior
of bread, but also meaning *intelligence* and *what sits at the center
of everything*. It was created at **UNIVATES**, a community university
in Lajeado, Rio Grande do Sul, Brazil (population ~60,000), as the
foundation for **SAGU**, an academic management system that replaced
expensive proprietary software.

There is a direct connection to the very origins of PHP itself.
[**Rasmus Lerdorf**](https://github.com/rlerdorf), the creator of PHP,
had lived and worked in Porto Alegre — just 120 km from Lajeado — as a
Unix programmer from 1991 to 1993. In 2001, Rasmus visited Lajeado and
spent two weeks working with Vilson Gärtner and Thomas Sprietersbach,
helping conceptualize the main functions of what would become MIOLO. As Cesar Brod
[wrote](https://linuxgazette.net/issue72/brod.html): *"After a visit by
Mr. Rasmus Lerdorf to our University, we decided we would stick to
PostgreSQL and created a new transactional/presentation base for SAGU
and all of our free software projects, called MIOLO."*

### What the world looked like in 1999

There were **no PHP frameworks**. PHP 3 had just been released. There was
no Google (it launched in September 1998), no Stack Overflow, no AWS, no
Git. Internet access was dial-up at 28.8 kbps — 56 kbps if you were
lucky. You connected through an ISP, your phone line was busy while you
were online, and you paid by the minute. Deploying meant FTP. Source
control meant copying files to a shared folder.

There were no package managers, no PDO, no caching layers, no frontend
frameworks, no CSS layout tools, no security standards. PHP extensions
had to be compiled from source. Every database driver was written from
scratch. JavaScript was unreliable. Page rendering was pure server-side
HTML with `<table>` layouts. MIOLO was built in this world — and built
solutions to problems that the PHP ecosystem wouldn't formally address
for another decade.

> **Deep dive:** The [Historical Curiosities](docs/history/) section
> explores each of these challenges in detail, with code examples from
> the MIOLO codebase and comparisons with modern equivalents.

The very first piece of code in this repository — **PSLib** (1999) — is
a PHP library for generating PostScript files. The university needed to
print academic transcripts, and there were no PDF libraries for PHP.
Vilson Gärtner had to write raw printer instructions: move cursor to
coordinates, set font, draw text, draw lines — every element on the page
was a hand-calculated coordinate and a PostScript operator. This is where
it all started: writing documents in printer instructions because there
was no other way.

From that foundation, MIOLO grew into a full object-oriented framework
with database abstraction, form handling, session management, theming,
AJAX support, report generation, and a complete UI controls layer.
**Gnuteca** — a library automation system compliant with international
standards, promoted through UNESCO workshops — was the first major
project built on MIOLO. Then came the rewrite of **SAGU** (academic
management, used by dozens of Brazilian universities), **Fred** (a web
content manager whose name was suggested by Jon "maddog" Hall), and many
other institutional applications. **Peter Salus**, the Unix historian
and author of *A Quarter Century of Unix*, reviewed the university's
free software texts — a small sign that word was getting out.

In 2003, the developers founded **SOLIS** — the first cooperative in the
world dedicated exclusively to free and open-source software. The name
was suggested by Marcelo Malheiros; the first logo was designed by
Thomas Sprietersbach. By 2013, SOLIS had grown to 50 cooperatives from
20 cities, serving over 100 clients across ten Brazilian states, and was
named one of the best IT companies to work for by Computerworld in 2010.
SOLIS became Canonical's first certified Ubuntu support partner in
Brazil in 2009.

**Read the full story:** [CONTRIBUTORS.md](CONTRIBUTORS.md) contains a
comprehensive project history compiled from SVN logs, code comments, and
public sources — including a detailed timeline from 1997 to 2026, profiles
of all 31 contributors, and references to external publications.

---

## Historical Curiosities

MIOLO is a time capsule of early PHP development. These documents explore
how specific challenges were solved in 1999-2005, with code examples from
the actual codebase and comparisons with modern tools and frameworks.

| # | Topic | What It Covers |
|---|---|---|
| [01](docs/history/01-debugging.md) | **Debugging** | `var_dump` as the only tool, MDump vs Laravel's `dd()`, IP-gated debug output, the `/tmp/var_dump` pattern, Firebug bridge |
| [02](docs/history/02-logging.md) | **Logging** | Custom MLog with 4 handlers (file, socket, DB, screen) before Monolog existed, MTrace execution tracing, MProfile performance profiling |
| [03](docs/history/03-package-management.md) | **Package Management** | No Composer, no Packagist — finding libraries on mailing lists, downloading tarballs, vendoring code, getting copies from authors at conferences |
| [04](docs/history/04-database.md) | **Database** | Writing 5 separate database drivers from scratch (PostgreSQL, MySQL, Oracle, SQLite, MsSQL) before PDO existed, comparison with modern PDO and Eloquent |
| [05](docs/history/05-frontend-rendering.md) | **Frontend Rendering** | Server-side component model before React, MDataGrid vs DataTables/React Table, theme system, PostBack pattern, JavaScript evolution from zero to Dojo to jQuery |
| [06](docs/history/06-autoloading.md) | **Autoloading** | XML class manifests, manual `include()` chains, the `uses()` method with file-size tracking — before `spl_autoload_register()` and PSR-4 |
| [07](docs/history/07-installation-infrastructure.md) | **Installation & Infrastructure** | Compiling PHP from source, the Linux distro landscape (Slackware to Ubuntu), PostgreSQL versions, Apache config, CI/CD as shell scripts |
| [08](docs/history/08-design-patterns.md) | **Design Patterns** | Singleton, Factory, Template Method, Decorator, MVC, Service Locator — implemented in PHP 3/4 before most PHP developers had heard of them |
| [09](docs/history/09-internet-connectivity.md) | **Internet & Connectivity** | 56 kbps dial-up, 4-hour browser downloads, per-minute charges, how bandwidth constraints shaped architecture |
| [10](docs/history/10-ahead-of-their-time.md) | **Ahead of Their Time** | ORM before Doctrine, components before React, middleware before Laravel, fluent queries before Fowler named them, CSRF protection before OWASP |
| [11](docs/history/11-auxiliary-languages.md) | **Auxiliary Languages** | PostScript for printing, Java (JasperReports) for reports, C++ (Qt3) for GUI tools — when PHP wasn't enough |

---

## Modernization Status

This is an active modernization effort. The work so far:

- **SVN to Git migration** — Full history preserved from 9,538 SVN revisions
- **Portuguese to English translation** — Comments, variable names, method
  names, class names, and documentation translated across 250+ files
  ([branch: `feature/translate-pt-to-en`](../../tree/feature/translate-pt-to-en))
- **Security hardening** — Hardcoded credentials removed from config files
  and SQL seeds, git history scrubbed
- **Composer integration** — PSR-4 autoloading, dependency management
- **PHP 8.1+ compatibility** — Ongoing migration from PHP 5.x/7.x patterns

### What's ahead

- Complete PHP 8.x strict type compatibility
- Replace deprecated patterns (magic quotes, `each()`, implicit nullable)
- Add automated tests
- Modernize the UI layer (currently Dojo 1.x based)
- Documentation for developers who want to understand the architecture

---

## Documentation

### Framework Reference

Comprehensive technical documentation converted from the original legacy guides
(DOC, ODT, PDF) into markdown. Covers architecture, configuration, development
patterns, UI controls, database access, persistence, AJAX, reports, and API
signatures.

**[docs/reference/](docs/reference/index.md)** — Full table of contents:

| # | Topic |
|---|-------|
| [01](docs/reference/01-introduction.md) | Introduction & Architecture |
| [02](docs/reference/02-configuration.md) | Installation & Configuration |
| [03](docs/reference/03-development.md) | Development Guide |
| [04](docs/reference/04-ui-controls.md) | UI Controls (Widgets) |
| [05](docs/reference/05-database.md) | Database Access (DAO) |
| [06](docs/reference/06-persistence.md) | Object Persistence |
| [07](docs/reference/07-ajax-dialogs-windows.md) | AJAX, Dialogs & Windows |
| [08](docs/reference/08-reports.md) | Reports & PDF Generation |
| [09](docs/reference/09-api-reference.md) | API Reference |
| [10](docs/reference/10-tutorials.md) | Tutorials |
| [11](docs/reference/11-i18n.md) | Internationalization (i18n) |

### Internationalization

MIOLO uses GNU gettext for i18n. The `_M()` function wraps all translatable
strings, with `.po`/`.mo` catalogs organized per module and language under
`locale/`. See the [i18n reference](docs/reference/11-i18n.md) for full details,
or the [locale README](locale/README.md) for a quick-start tutorial.

### Legacy Source Documents

The reference documentation above was compiled from the original guides shipped
with MIOLO, authored by Vilson Gärtner, Ely Edison Matos, and the SOLIS team:

- **MIOLO 2.5 UserGuide** (ODT/PDF) — Comprehensive narrative guide (v0.1, 2008)
- **MIOLO 2.5 Fundamentals** (ODT/PDF) — Architecture and concepts
- **MIOLO 2.5 Quick Reference** (ODT) — API method signatures
- **MIOLO 2.0 Reference Guide** (DOC) — Class-by-class reference (v0.1, 2005)
- **MIOLO 2.0 Components** (ODT) — DAO, Persistence, AJAX, DOMPDF
- **Hello World / Hangman / Example Module Tutorials** (DOC/PDF)
- **Installation Manual** (ODT/PDF, pt-BR) — CLI tool and setup (Solis, 2012)
- **Course Material** (PDF, Mar 2009) — Module creation with PostgreSQL

---

## Project Structure

```
classes/            Core framework (MVC, database, UI controls, security, utils)
  database/         Database abstraction (PostgreSQL, MySQL, Oracle, SQLite, MsSQL)
  pslib/            PSLib — PostScript generation library (1999, the oldest code)
  security/         Authentication (DB, LDAP, AD)
  ui/controls/      70+ UI components (forms, grids, validators, dialogs, menus)
  utils/            Utility classes (date, string, browser detection, currency)
modules/
  admin/            User/group/permission management
  base/             Business layer (types, forms, toolbars, database sync)
  example/          Example applications and tutorials
html/               Static assets, themes, JavaScript libraries
locale/             Internationalization (gettext .po/.pot files)
etc/                Configuration templates
```

---

## People

MIOLO was built by dozens of people over 27 years. Some of them are on
GitHub:

- [**Vilson Cristiano Gärtner**](https://github.com/vgartner) — Creator
  and original coordinator. Wrote PSLib, the core framework, and led the
  project from 1999 through 2009.
- [**Cesar Brod**](https://github.com/cesarbrod) — IT Manager at UNIVATES
  who initiated the free software strategy. Co-founded SOLIS. Presented
  SAGU at USENIX ALS'01. Told the SOLIS story in
  [Linux Journal](https://www.linuxjournal.com/article/7081) and
  [Linux Gazette](https://linuxgazette.net/issue72/brod.html). The
  organizational force that made it all possible.
- [**Alexandre Heitor Schmidt**](https://github.com/alexsmithbr) — Core
  developer at SOLIS. Time field, Jasper reports, i18n scripts, PHP 5.3
  compatibility, toolbar improvements.
- [**Henrique Gravina**](https://github.com/Henriquegravina) — Contributed
  Mac OS X compatibility documentation and authored the MIOLO architecture
  presentation.
- **Jamiel Spezia** — Long-term maintainer (9+ years) and current CEO of
  SOLIS (2025). JSON fixes, PHP 8 migration, jQuery plugins, deprecated
  dependency removal.

This is a 27-year-old software project that is still in development —
I certainly forgot a lot of names. If you contributed to MIOLO and don't
see yourself here, just get in touch and I will add you.

See [CONTRIBUTORS.md](CONTRIBUTORS.md) for the complete list of 31
contributors with their roles, commit histories, and key contributions.

---

## About This Recovery

This repository was created by [**William Prigol Lopes**](https://github.com/wprigollopes),
a former SOLIS contributor (2013), as a personal effort to preserve and
modernize the MIOLO framework. The original SVN repository remains at
`svn.solis.com.br/miolo`.

The translation, modernization, and historical research were done with
the assistance of Claude (Anthropic), as documented in the commit history.

---

## References

- [EOF — SOLIS, a Brazilian Free Software Cooperative (Linux Journal, 2003)](https://www.linuxjournal.com/article/7081)
- [SAGU: Open-Source Unified Management System (USENIX ALS'01)](https://www.usenix.org/conference/als-01/sagu-open-source-unified-management-system-educational-institutions)
- [SAGU, Free Software for Academic Administration (Linux Gazette #72)](https://linuxgazette.net/issue72/brod.html)
- [Canonical certifica SOLIS para suporte ao Ubuntu no Brasil (BR-Linux, 2009)](https://br-linux.org/wparchive/2009/canonical-certifica-solis-para-suporte-ao-ubuntu-no-brasil.php)
- [SOLIS: 10 anos! (Dicas-L, 2013)](http://www.dicas-l.com.br/brod/brod_201303112119.php)
- [Iconic Linux debate sparks an open source career (Opensource.com, 2015)](https://opensource.com/life/15/10/my-linux-story-cesar-brod)
- [Software Publico Brasileiro — SAGU and Gnuteca (SERPRO)](https://www.serpro.gov.br/menu/noticias/noticias-antigas/software-publico-brasileiro-lanca-dois-programas-para-area-educacional)

---

## License

MIOLO was originally released under the **GNU General Public License v2**
(see [COPYING](COPYING)). Some components (PSLib) are licensed under
**LGPL v2**. Modules developed after 2005 under SOLIS carry a proprietary
license (see individual file headers). Third-party libraries retain their
original licenses.
