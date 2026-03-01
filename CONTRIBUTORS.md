# Miolo Framework — Contributors & Project History

> This document was compiled from SVN commit logs (https://svn.solis.com.br/miolo,
> 9,538 revisions), `@author`/`@contributors`/`@maintainers` attributions in
> source code comments, and publicly available sources. MIOLO is a ~27-year-old
> PHP framework; its origins predate the SVN repository by several years.

---

## About MIOLO

MIOLO (Portuguese for the soft interior of bread) is a PHP object-oriented
framework for rapid web application development. It was created as the
transactional and presentation-layer foundation for **SAGU** (Sistema Aberto
de Gestão Unificada — Open Unified Management System), an academic
administration system, and **Gnuteca**, a library automation system compliant
with international standards (MARC, CDS/ISIS). The decision to build MIOLO as
a standalone framework came after a visit by **Rasmus Lerdorf** (creator of
PHP) to UNIVATES, which inspired the team to rethink SAGU's architecture
around a reusable framework layer.

### The World MIOLO Was Born Into

To appreciate what MIOLO represents, one must understand what building web
applications looked like in the late 1990s. There were **no frameworks** —
not in PHP, not in Java (Struts arrived in 2000), not anywhere. PHP itself
was in its infancy: PHP 3 had just been released in 1998, bringing basic
object support for the first time. PHP 4 wouldn't arrive until 2000.
Writing a web application meant writing everything from scratch — session
management, form handling, database abstraction, HTML rendering — all by
hand, in plain procedural code.

The browser landscape was a battlefield. **Netscape Navigator 4** and
**Internet Explorer 4/5** rendered HTML and CSS differently in nearly every
way imaginable. JavaScript was primitive and unreliable — most serious web
applications avoided it entirely, relying on full page reloads for every
user interaction. There was no XMLHttpRequest (AJAX wouldn't be coined until
2005), no DOM standard worth trusting, no jQuery, no React, no Node.js.
"Dynamic HTML" meant using `<blink>` tags and animated GIFs.

**There was no source control** as we know it today. CVS existed but was
rare outside large organizations. Subversion wouldn't be released until
2000. Git was a decade away (2005). Teams coordinated by copying files
over FTP or shared network drives. Deployments were manual — you edited
files on the production server directly, or uploaded them via FTP and
hoped nothing broke. There was no CI/CD, no staging environments, no
rollback strategy beyond "restore yesterday's backup."

**There was no cloud.** Amazon Web Services launched in 2006. If you
needed a server, you bought physical hardware, installed Linux from
floppy disks or CDs, configured Apache by hand, and colocated it at
a data center — or ran it under your desk. Scaling meant buying another
machine.

Finding documentation was an adventure. **Google didn't exist yet**
(it launched in September 1998). Yahoo! was a curated directory, not a
search engine. AltaVista and Excite were the best options, and they
were mediocre. If you needed to learn how something worked, you bought
a book — O'Reilly's *Programming PHP* (1st edition, 2002) and the
*PHP Manual* (a single downloadable HTML file) were the primary
references. Stack Overflow wouldn't exist until 2008. Most knowledge
lived on mailing lists, Usenet newsgroups, and personal home pages.

Internet access itself was scarce and slow. In Brazil in the late 1990s,
most connections were **dial-up at 28.8 or 33.6 kbps** — a 56 kbps
modem was top-of-the-line and rarely achieved full speed. You connected
through an ISP, your phone line was busy while online, and you paid by
the minute. Downloading a Linux distribution meant leaving the modem
connected overnight for days, hoping the connection wouldn't drop.
Broadband was virtually nonexistent outside major capitals.

Being a software developer was a deeply **niche, nerdy pursuit**. The
web was still seen as a curiosity by most businesses. In southern Brazil,
at a community university in a small city (Lajeado, population ~60,000),
choosing to build mission-critical academic systems in PHP on Linux —
replacing a proprietary system — was a radical bet.

### PSLib: The Beginning of the Beginning

The very first piece of code in this repository predates MIOLO, SAGU,
and everything else: **PSLib** (CopyLeft 1999), a PHP library for
generating PostScript files. To understand why it exists, consider a
mundane but unavoidable problem: UNIVATES needed to **print academic
transcripts** (*históricos escolares*) — the official documents that
every university must produce for its students.

In 1999, there was no way to generate printable documents from a PHP
web application. PDF was an Adobe proprietary format; PDFlib (the first
PHP PDF extension) was commercial and expensive. There were no report
generators, no wkhtmltopdf, no browser print-to-PDF, no LaTeX bindings
for PHP. The only path available to a university in southern Brazil with
no budget for proprietary tools was to talk directly to the printer.

**PostScript** — the page description language created by Adobe in 1984
— is essentially a programming language for printers. Every laser printer
of that era understood it natively. To print a transcript, Vilson Gärtner
had to write code that generated raw printer instructions: move the
cursor to coordinates (72, 750), set the font to Helvetica-Bold at 14
points, draw the text "UNIVATES — Centro Universitário", draw a
horizontal line from (50, 740) to (545, 740) with 0.5pt stroke width,
move down 20 points, set the font to Times-Roman at 10 points, show
the student's name, iterate through their course history printing each
row of grades into a precisely positioned table — every single element
on the page was a hand-calculated coordinate and a PostScript operator.
Accented characters (essential for Portuguese: ã, é, ç, ô) required
implementing ISOLatin1 font re-encoding by hand in PostScript.

The first release of PSLib (September 2000) had just 17 functions with
names like `PS_open`, `PS_moveto`, `PS_show_xy`, and `PS_line` — the
absolute minimum needed to position text and draw lines on a page. The
bundled example that "actually originated PSLib" (as the README states)
was `historico.php`: the academic transcript generator. Version 0.03
(December 2000) converted it from procedural functions into a PHP class
— one of the earliest examples of OOP in the project. By version 0.4
(July 2002), it supported circles, arcs, RGB colours, text alignment,
and a colour palette reference file (`palette.ps`) that developers would
open in a PostScript viewer to pick colour values.

PSLib was published on SourceForge and Código Livre (Brazil's early
open-source hosting), modelled after the proprietary PDFlib API so that
developers familiar with that commercial library could use PSLib as a
free drop-in. It attracted external contributors: Paulo Mallmann
(circles), Jay Haugen (colour/alignment), Joel Leon (alignment), Jose
Paulo Silva (colour), and Ericson C. Smith (class conversion).

This is where it all started: a university that needed to print student
transcripts, a developer who couldn't afford proprietary tools, and
the stubborn determination to write documents in raw printer
instructions. PSLib proved that PHP could be used for serious
institutional work. The experience of building it — abstracting low-level
operations into a reusable class library — became the design philosophy
that would later define MIOLO itself.

The fact that MIOLO emerged from this context, grew into a full-featured
framework used by dozens of universities, and spawned an internationally
recognized cooperative is a testament to the vision and determination of
its creators.

Both SAGU and Gnuteca are hosted on Brazil's **Software Público Brasileiro**
(SPB) portal, coordinated by the Ministry of Planning. SAGU is used by
dozens of Brazilian universities, and Gnuteca has been promoted internationally
through UNESCO workshops in South America. MIOLO ships with official
**Ubuntu .deb packages** (created since 2012) and the SOLIS deployment
infrastructure runs on Ubuntu servers — the natural choice given SOLIS's
status as Canonical's first certified Ubuntu support partner in Brazil (2009).

### References

- [EOF — SOLIS, a Brazilian Free Software Cooperative (Linux Journal, 2003)](https://www.linuxjournal.com/article/7081)
- [Free Software Carnival: Latin America Takes to FLOSS (Linux Journal, 2003)](https://www.linuxjournal.com/article/6915)
- [SAGU: Open-Source Unified Management System for Educational Institutions (USENIX ALS'01)](https://www.usenix.org/conference/als-01/sagu-open-source-unified-management-system-educational-institutions)
- [SAGU, Free Software for Academic Administration (Linux Gazette #72)](https://linuxgazette.net/issue72/brod.html)
- [Software Público Brasileiro launches SAGU and Gnuteca (SERPRO)](https://www.serpro.gov.br/menu/noticias/noticias-antigas/software-publico-brasileiro-lanca-dois-programas-para-area-educacional)
- [SOLIS — P2P Foundation](https://wiki.p2pfoundation.net/SOLIS)
- [Canonical certifica SOLIS para suporte ao Ubuntu no Brasil (BR-Linux, 2009)](https://br-linux.org/wparchive/2009/canonical-certifica-solis-para-suporte-ao-ubuntu-no-brasil.php)
- [Coluna do Cesar Brod — SOLIS: 10 anos! (Dicas-L, 2013)](http://www.dicas-l.com.br/brod/brod_201303112119.php)
- [Free distance-learning course for MIOLO developers (SPB, 2012)](https://softwarepublico.gov.br/social/spb/noticias/curso-gratuito-a-distancia-para-capacitar-desenvolvedores-miolo)

---

## Timeline

```
1997        UNIVATES Centro Universitário (Lajeado, RS, Brazil), a community-
            owned institution, begins adopting free software.

1999        UNIVATES standardizes on free software for administrative use.
            Cesar Brod, a technology consultant and Linux user since 1993,
            joins as IT Manager. The university needs to replace a proprietary
            academic management system that can't handle growing enrollment.
            Vilson C. Gärtner begins work on pslib (PostScript library), the
            earliest dated code in the repository (CopyLeft 1999).

2000        SAGU (Sistema Aberto de Gestão Unificada) goes into production
            at UNIVATES in July. Built in under 6 months by three developers
            using PHP and MySQL (later migrated to PostgreSQL). UNIVATES
            saves ~$200,000 USD in software licenses.

2001        Thomas Spriestersbach (interact2000) and Vilson C. Gärtner
            co-create the first MIOLO UI controls (page navigator, status
            bar, theme toolkit) at UNIVATES. MIOLO is conceived as a reusable
            framework layer after Rasmus Lerdorf (creator of PHP) visits
            UNIVATES and inspires the team to rethink SAGU's architecture.
            Cesar Brod presents SAGU at USENIX ALS'01 (Oakland, CA).
            Gnuteca library automation system also begins development.

2002        Vilson conducts MIOLO training at UFJF (Universidade Federal de
            Juiz de Fora, MG). Ely Edison Matos and his team begin
            contributing heavily, creating a "MIOLO-UFJF" variant.
            Gnuteca is promoted through UNESCO workshops in South America.

2003        SOLIS — Cooperativa de Soluções Livres is founded on January
            2003 (officially inaugurated May 12, 2003) with support from
            UNIVATES. SOLIS is the first cooperative in the world dedicated
            exclusively to free/open-source software development and
            integration. Founded by members including Cesar Brod (first
            administrative council, Vice President), Josi Petter (History/
            Cooperativism background), Professor Eloni Salvi, and Fábio
            Wiebbelling. Starts with undergraduate students from
            UNIVATES. UFJF begins using MIOLO for its corporate applications.

~2004       Work begins on merging MIOLO and MIOLO-UFJF into MIOLO 2,
            introducing Controls/Widgets, theme rendering, and a persistence
            layer. Ely Edison Matos leads much of the implementation.
            MIOLO development transfers from UNIVATES to SOLIS.

2006        SVN repository created (r1, March 8). Vilson imports the existing
            codebase. SAGU2 version tagged. Early commits from Vilson and
            Nasair Junior da Silva.

2007        Thomas Spriestersbach, Armando Taffarel Neto, and Ely Edison Matos
            join the SVN repository. Jamiel Spezia contributes patches.
            Fernando Kochhann builds the code generator module.

2008-2009   Active development: 427 commits. Vilson adds MMessage, Dojo
            integration, Firebug logging, and login improvements.

2009        Canonical certifies SOLIS as the first company authorized to
            provide official Ubuntu support in Brazil. Fábio Filho,
            Canonical's Latin America director, describes the contract as
            "a strong boost to help Canonical meet the growing demand for
            services in the country." UNIVATES servers are migrated to
            Ubuntu with official SOLIS-provided support.

2010        SOLIS named among Brazil's best IT companies to work for
            (Computerworld). Daniel Hartmann joins and becomes the most
            prolific contributor (948 commits). Major UI controls layer
            expansion. Alexandre Heitor Schmidt contributes toolbar and
            Jasper improvements. Ely Edison Matos continues core work.

2011        571 commits. MIOLO UI layer reaches maturity: validators, forms,
            dialogs, grids, charts, CAPTCHA, menus, and more.

2012        MIOLO 2.6 branch created. Daniel Hartmann creates the first
            official Ubuntu .deb packages for MIOLO 2.6 deployment.
            Eduardo Bonfandini adds jQuery, grider, subdetail, and message
            components. Moisés Heberle begins contributing (395 commits
            through 2015). Free distance-learning MIOLO developer course
            offered at FISL 13 and online.

2013        SOLIS celebrates 10th anniversary — has grown to 50 cooperative
            members from 20 cities, serving 100+ clients across 10+
            Brazilian states. Many founding members now hold master's and
            doctoral degrees. Jonas Rosa joins (2,506 commits through 2016,
            primarily release management). Augusto begins long tenure 
            (401 commits through 2026). William Prigol Lopes contributes
            FullCalendar and ci/cd work.

2014-2015   Peak development years: 2,603 commits. Jader Osvino Fiegenbaum
            authors the entire modules/base business layer. Armando Taffarel
            Neto contributes lightbox, menus, test framework, and CLI tools.

2016        Jenkins CI/CD introduced (3,501 automated commits through 2026).
            Luís Felipe Wermann joins (238 commits through 2018). Fabiano
            Tomasini begins contributing (64 commits through 2020).

2017-2018   MIOLO 2.6 branch stabilizes. Jonas Diel contributes password
            encryption, JasperReport integration, and IE blocking. Mateus
            Azevedo performs PHP 7.2 migration work.

2019        Jhonatan contributes PHP 7.2/Ubuntu 18 compatibility fixes.

2020-2021   Sandro Roberto adds Chrome compatibility, custom fields, and
            portal adjustments (32 commits). Lucas Horn begins contributing
            (51 commits through 2026). Jamiel Spezia performs PHP 8
            adjustments.

2022-2023   Lauro contributes GED signatures, CKEditor security, custom
            field types (26 commits). Jamiel performs further PHP 8 migration.

2024        Tiago Gossmann performs comprehensive PHP 8 migration (11 commits).
            Jader creates new PHP branch. Ruan Signori begins contributing.

2025-2026   Active maintenance: Marcos Gregori, Daniel Corbellini, Vitorino,
            Lucas Horn, Augusto, and Ruan continue development. Latest
            revision: r9538 (February 25, 2026). Current version: 2.6.879.
```

---

## Contributors

### Founders & Architects

#### Vilson Cristiano Gärtner
- **SVN username:** `vilson`
- **Email:** vgartner@univates.br, vilson@solis.coop.br, vgartner@gmail.com
- **Affiliation:** UNIVATES Centro Universitário → SOLIS Cooperativa
- **SVN commits:** 370 (2006-03-08 → 2009-09-28)
- **Role:** Project creator and coordinator (vilson@miolo.org.br)
- **Key contributions:**
  - Created the original MIOLO framework (earliest code dated 1999)
  - Authored the INSTALL guide, signed as "MIOLO Project Coordinator"
  - pslib PostScript library (CopyLeft 1999-2002)
  - Core UI controls: page navigator, status bar, theme toolkit, MMessage
  - MsSQL database driver (co-authored with Thomas Spriestersbach)
  - Web services front-end, autocomplete handler
  - MUtil utility class
  - Dojo integration, Firebug logging support
  - Login system, configuration management
- **Files (code comments):** ~40+ files across `classes/`, `html/`, `modules/`

#### Thomas Spriestersbach
- **SVN username:** *(pre-dates SVN; no direct SVN commits found)*
- **Email:** ts@interact2000.com.br
- **Affiliation:** interact2000
- **Role:** Co-founder
- **Key contributions:**
  - Co-created earliest UI controls with Vilson (2001)
  - Page navigator, status bar, theme toolkit, MPrompt
  - MsSQL database driver (connection + query)
  - MTree utility class
  - MUtil utility class (co-author)
  - Autocomplete handlers (admin, admin_ldap)
  - Barcode library maintainer
- **Files (code comments):** ~15+ files in `classes/ui/controls/`, `classes/database/mssql/`, `classes/utils/`

#### Ely Edison Matos
- **SVN username:** `ely.matos`
- **Email:** ely.matos@ufjf.edu.br
- **Affiliation:** UFJF (Universidade Federal de Juiz de Fora)
- **SVN commits:** 237 (2007-09-28 → 2010-09-07)
- **Role:** MIOLO 2 architect, external academic contributor
- **Key contributions:**
  - Led the MIOLO 2 rewrite merging MIOLO-UFJF and official versions
  - AJAX layer (MAjax class)
  - CPaint canvas/paint layer
  - Core control base classes (MControl, MForm, MBaseForm)
  - File upload field (MFileField)
  - Utility classes: date inversion, var dump, tree arrays, value formatting, quoted-printable encoding
  - Persistence layer, grid IE8 compatibility, PDF support
- **Files (code comments):** ~11+ files in `classes/ui/`, `classes/utils/`

#### Cesar Brod
- **Email:** cesar@brod.com.br
- **Affiliation:** UNIVATES (IT Manager) → SOLIS (Vice President, first administrative council)
- **Role:** IT leader, project coordinator, evangelist
- **Key contributions:**
  - IT Manager at UNIVATES; initiated the free software strategy that led to SAGU, Gnuteca, and MIOLO
  - Co-founded SOLIS in January 2003 as the world's first free software cooperative
  - Presented SAGU at USENIX ALS'01 (Oakland, CA, November 2001)
  - Coordinated the Latin American leg of a Finnish study on FLOSS significance
  - Linux user since 1993; coordinated Rio Grande do Sul's Free Software Project
  - Note: Cesar's contributions are organizational and strategic — he does not appear in SVN commit logs or code comments, but his leadership made MIOLO, SAGU, and Gnuteca possible

---

### Core Developers

#### Daniel Hartmann
- **SVN username:** `daniel`
- **Email:** daniel@solis.coop.br
- **Affiliation:** SOLIS Cooperativa
- **SVN commits:** 948 (2010-06-30 → 2012-08-10)
- **Role:** Most prolific single-period contributor; primary author of the modern UI layer
- **Key contributions:**
  - Virtually the entire UI controls layer (~75+ files): validators (CPF, CNPJ, email, date, mask, phone, password, float, integer, etc.), forms, dialogs, grids, charts, CAPTCHA, menus, context menus, special grids, fieldsets, dual lists, lookups, editors, popups, expand divs, step-by-step forms, float/integer/timestamp fields
  - Web services class
  - Database exception handling
  - Unit testing framework
  - MIOLO console (CLI)
  - MIOLO updater utility
  - Sort utility
  - Code generator module (SAGUGenerate, MIOLOGenerate, DBInfo)
  - Admin module (DB dump)
  - Example module (browser, validators, editor, subdetail, dual list, grider, datagrid)
  - Mobile example module
  - MIOLO 2.6 Ubuntu packaging
- **Files (code comments):** ~80+ files across entire codebase

#### Armando Taffarel Neto
- **SVN username:** `taffarel`
- **Email:** taffarel@solis.coop.br
- **Affiliation:** SOLIS Cooperativa
- **SVN commits:** 222 (2007-09-28 → 2015-10-21)
- **Role:** Author and maintainer
- **Key contributions:**
  - UI menus (static menu, menu item, context menu)
  - Special grids and grid action select
  - Lightbox component
  - Step component
  - Unit test suite framework
  - MIOLO CLI admin tool
  - Ubuntu package maintenance
  - SQLite support
- **Files (code comments):** ~10+ files as author, maintainer across many UI controls

#### Eduardo Bonfandini
- **SVN username:** `eduardo`
- **Email:** eduardo@solis.coop.br
- **Affiliation:** SOLIS Cooperativa
- **SVN commits:** 6 (2012-09-01 → 2012-09-17)
- **Role:** Component developer
- **Key contributions:**
  - CAPTCHA field component
  - MMessage notification system
  - MGrider advanced grid component
  - MSubDetail (sub-form detail component)
  - Base module: bJavascript, bBaseDeDados, bCatalogo
  - jQuery integration (themes modern and blue)
  - Example grider form
- **Files (code comments):** ~8 files in `classes/ui/controls/`, `modules/base/classes/`, `modules/example/`

#### Jader Osvino Fiegenbaum
- **SVN username:** `jader`
- **Email:** jader@solis.coop.br
- **Affiliation:** SOLIS Cooperativa
- **SVN commits:** 32 (2016-03-23 → 2024-05-28)
- **Role:** Base module architect, long-term maintainer
- **Key contributions:**
  - Entire `modules/base/classes/` business layer: bUtil, bJavascript, bBarraDeFerramentas (toolbar), bFormRegistration, bFormSearch, bBooleano, bType, bCatalogo, bBaseDeDados
  - PHP 7.4 / PostgreSQL 12 sync adjustments
  - Log4j library removal (security)
  - Custom field support in forms
  - Sequence update methods
  - PHP branch management
- **Files (code comments):** ~9 files in `modules/base/classes/`

#### Fernando Kochhann
- **SVN username:** `fernando`
- **Email:** fernando@solis.coop.br
- **Affiliation:** SOLIS Cooperativa
- **SVN commits:** 5 (2010-03-02 → 2012-03-22)
- **Role:** Generator module author, early UNIVATES contributor
- **Key contributions:**
  - Code generator module: MIOLOGenerate (database introspection → MIOLO source generation), DBInfo
  - User model insert/update methods (base and admin modules)
  - Wizindent PHP code indenter (modified)
  - UNIVATES-specific adaptations
- **Files (code comments):** ~5 files in `modules/generator/`, `modules/base/db/`, `modules/admin/db/`

#### Alexandre Heitor Schmidt
- **SVN username:** `alexsmith`
- **Email:** alexsmith@solis.coop.br
- **Affiliation:** SOLIS Cooperativa
- **SVN commits:** 9 (2010-07-14 → 2011-08-25)
- **Role:** Component developer
- **Key contributions:**
  - MTimeField (time field UI control)
  - Database dump download (admin module)
  - MToolbar getter/setter improvements and button generation refactoring
  - Jasper report compilation (Java 1.6)
  - i18n extraction script
  - PHP 5.3 compatibility (SimpleXML, grid controls)
  - Encoding fixes, JavaScript reserved word fixes
- **Files (code comments):** ~2 files as author; SVN touches many more

#### Jonas Guilherme Dahmer
- **SVN username:** `jonas` *(not to be confused with `jonas_rosa` or `jonas_diel`)*
- **Email:** jonas@solis.coop.br
- **Affiliation:** SOLIS Cooperativa
- **SVN commits:** 14 (2012-09-19 → 2013-04-24)
- **Role:** Component developer
- **Key contributions:**
  - MExpandDiv (collapsible/expandable section)
  - MStepByStepForm (wizard-style multi-step form)
  - MIOLO 2 compatibility components
  - SAGU-MIOLO integration
  - MChart from MIOLO 2 (ported to trunk)
  - Calendar class additions
- **Files (code comments):** ~2 files as author

#### Nasair Junior da Silva
- **SVN username:** `njunior`
- **Affiliation:** SOLIS Cooperativa
- **SVN commits:** 102 (2006-03-25 → 2007-09-03)
- **Role:** Early developer (second SVN committer after Vilson)
- **Key contributions:**
  - Grid header display (header attribute usage)
  - Grid footer adjustments
  - MGrid improvements (added header attribute)
  - Lookup bug fixes
  - Object value handling in controls
  - Configuration-based path resolution (replacing hardcoded backslashes)
  - Transaction control for MySQL
  - Bug fixes reported by other developers (Jamiel, Daniel Hartmann)

#### Moisés Heberle
- **SVN username:** `moises`
- **Email:** moises@solis.coop.br
- **Affiliation:** SOLIS Cooperativa
- **SVN commits:** 395 (2012-09-11 → 2015-11-05)
- **Role:** High-volume contributor
- **Key contributions:**
  - MPermsMiolo optimization (reduced SQL queries in checkAccess)
  - MFileField onchange improvements
  - Grid image fixes
  - Extensive UI/UX adjustments across components
  - Ticket-driven feature work and bug fixes
- **Files:** Primarily modifications across existing components

---

### Extended Contributors

#### Jonas Rosa
- **SVN username:** `jonas_rosa`
- **SVN commits:** 2,506 (2013-10-03 → 2016-04-29)
- **Role:** Release manager
- **Key contributions:** Highest-volume human contributor. Primarily tag creation and version management across branches 2.0, 2.5, and 2.6. Managed the release pipeline from version ~2.0.01 through ~2.6.316.

#### Augusto
- **SVN username:** `augusto`
- **SVN commits:** 401 (2013-08-30 → 2026-01-29)
- **Role:** Long-term maintainer (13+ years active)
- **Key contributions:** MSubDetail improvements, webhook routes (ENotas Gateway), report management, portal profiles, AI analysis integration (Claude), file upload management, SEO meta tags, SSO Moodle plugins, custom fields.

#### Luís Felipe Wermann
- **SVN username:** `luis_felipe`
- **Email:** *(uses moises@solis.coop.br in some code)*
- **Affiliation:** SOLIS / Gnuteca3 Development Team
- **SVN commits:** 238 (2015-10-21 → 2018-05-15)
- **Role:** Developer
- **Key contributions:** CKEditor image upload, MSubDetailValidators (MIOLO 2.6), Avaliação Institucional (institutional evaluation) interface, extensive UI work.

#### Jamiel Spezia
- **SVN username:** `jamiel`
- **SVN commits:** 186 (2014-01-17 → 2023-04-13)
- **Role:** Long-term maintainer (9+ years active)
- **Key contributions:** JSON extension fix (multibyte strings), PHP 8 migration work, report column formatting, jQuery floating scrollbar plugin, deprecated dependency removal. Referenced in early commits (2007) for email-driven feature requests.

#### Jonas Diel
- **SVN username:** `jonas_diel`
- **SVN commits:** 72 (2017-02-21 → 2020-09-14)
- **Role:** Developer
- **Key contributions:** New password encryption methods (bcrypt, password_hash), JasperReport portal integration, Internet Explorer blocking, MySQL encoding fixes, iReport adjustments, custom field actionURL, MioloLoginAttempt class.

#### Mateus Azevedo
- **SVN username:** `mateus_azevedo`
- **SVN commits:** 27 (2015-02-24 → 2018-11-12)
- **Role:** Migration specialist
- **Key contributions:** PHP 7.2 migration — resolving notices, warnings, "Declaration should be compatible" issues, and deprecated function calls across the codebase.

#### Fabiano Tomasini
- **SVN username:** `fabiano`
- **SVN commits:** 64 (2016-06-13 → 2020-12-14)
- **Affiliation:** SOLIS Cooperativa
- **Role:** Developer
- **Key contributions:** Pedagogical module, Dojo script minification/compression, CSS alignment fixes, .map file requirement removal.

#### Sandro Roberto
- **SVN username:** `sandroroberto`
- **SVN commits:** 32 (2020-07-29 → 2021-09-23)
- **Role:** Developer
- **Key contributions:** Chrome 93+ compatibility, field dictionary portal compatibility, custom field personalization, date sorting in grids, form construction adjustments.

#### Lucas Horn
- **SVN username:** `lucas_horn`
- **SVN commits:** 51 (2021-06-16 → 2026-01-22)
- **Role:** Active developer
- **Key contributions:** Document source configuration, public webhook routes, alphanumeric CNPJ mask/validation, certificate validity retrieval, custom field enhancements for course sections, choice list width configuration.

#### William Prigol Lopes
- **SVN username:** `william`
- **SVN commits:** 24 (2013-03-27 → 2013-12-30)
- **Role:** Developer
- **Key contributions:** FullCalendar component adjustments, institutional evaluation integration, tag creation for evaluation releases, CI/CD pipelines support with Jenkins.

#### Artur
- **SVN username:** `artur`
- **SVN commits:** 14 (2012-11-28 → 2013-03-01)
- **Affiliation:** SOLIS Cooperativa
- **Role:** Developer
- **Key contributions:** Jasper report MIOLO 2.6 compatibility fixes (port tag removal), MSQL bugfix, report directory reorganization.

#### Lauro
- **SVN username:** `lauro`
- **SVN commits:** 26 (2022-06-23 → 2023-10-18)
- **Role:** Developer
- **Key contributions:** GED internal signatures, CKEditor document upload validation (PDF, DOC, DOCX, ODT, ZIP, RAR), Content-Security-Policy header scripts, custom field types (upload, editor), iReport sEscolha field.

#### Jhonatan
- **SVN username:** `jhonatan`
- **SVN commits:** 16 (2019-01-09 → 2019-05-22)
- **Role:** Developer
- **Key contributions:** PHP 7.2 / Ubuntu 18 migration, browser incompatibility detection/alerts, upload file path fixes, MSQL order by injection in setQuery, report export format configuration.

#### Tiago Gossmann
- **SVN username:** `tiago_gossmann`
- **SVN commits:** 11 (2024-05-08 → 2024-08-12)
- **Role:** Migration specialist
- **Key contributions:** Comprehensive PHP 8 migration across the codebase, MIOLO documentation.

#### Luís Augusto Weber Mercado
- **Email:** luis_augusto@solis.com.br
- **Affiliation:** SOLIS Cooperativa
- **Role:** Component developer
- **Key contributions:** LaSalle client authentication form (frmLoginLaSalle, created 2012-06-20).

#### Luiz Gilberto Gregory Filho
- **Role:** Component developer
- **Key contributions:** MIOLO console class (CLI runner, co-authored with Daniel Hartmann).

#### Daniel Afonso Heisler
- **Role:** Component developer
- **Key contributions:** MToolbar, MToolbarButton, and MImageLabel UI controls.

---

### Recent Contributors (2024-2026)

| Username | Commits | Period | Key Work |
|---|---|---|---|
| `ruan` (Ruan Signori) | 10 | 2024-11 → 2025-10 | CSV import fixes, CLI error handling, Asaas webhook |
| `vitorino` | 8 | 2024-11 → 2026-02 | CNPJ validation, SQL null handling, sync error reporting |
| `marcos_gregori` (Marcos Gregori) | 7 | 2025-11 → 2026-02 | Custom field admin hints, report sync, validator trim |
| `daniel_corbellini` (Daniel Corbellini) | 3 | 2025-10 → 2026-01 | CKEditor filename sanitization, MSQL fix, group functions |
| `gabriel_lopes` (Gabriel Lopes) | 1 | 2025-03 | User database save |

---

### External Academic Contributors

#### Clausius Duque G. Reis
- **Email:** clausius@ufv.br
- **Affiliation:** UFV (Universidade Federal de Viçosa)
- **Role:** Contributor to MsSQL database driver (connection + query), appears in CVS `$Id` tags.

#### Rudinei Pereira Dias
- **Email:** rudinei@lasalle.tche.br
- **Affiliation:** UNILASALLE (Centro Universitário La Salle)
- **Role:** Original author of the barcode generation library.

#### Henrique B. Gravina
- **Role:** Contributor
- **Key contributions:** Mac OS X compatibility notes for the INSTALL guide (PHP `variables_order` fix, Mampstack vs Mamp).

#### Leonardo Gravina
- **Role:** Educator
- **Key contributions:** Authored the Framework Miolo SlideShare presentation covering architecture, OOP design patterns, and a goal-tracking case study.

---

### Third-Party Library Authors

These are authors of bundled third-party libraries, not Miolo team members:

| Author | Email | Library | File |
|---|---|---|---|
| Simon Wade | simonvwade@yahoo.com | CurrencyFormatter | `classes/utils/mcurrencyformatter.class.php` |
| Kwaku Otchere | ospinto@hotmail.com | dBug debugger | `classes/contrib/dbug.class.php` |
| Olavo Alexandrino | oalexandrino@yahoo.com.br | EasyDownload | `classes/contrib/EasyDownload.class.php` |
| Iván Ariel Melgrati | phpclasses@imelgrat.mailshell.com | IAM_CSVDump | `classes/extensions/mcsvdump.class.php` |
| Wayne Munro | pdf@ros.co.nz | ezPDF | `classes/ezpdf/class.pdf.php` |
| Paul Sullivan | wiley14@gmail.com | CPaint2 AJAX | `classes/ui/majax.class.php` |
| Dominique Stender | dstender@st-webdevelopment.de | CPaint2 AJAX | `classes/extensions/cpaint2.inc.php` |

---

### CI/CD

#### Jenkins
- **SVN username:** `jenkins`
- **SVN commits:** 3,501 (2016-05-16 → 2026-02-25)
- **Role:** Automated release pipeline. Creates version tags across branches 2.0, 2.5, and 2.6. All commits reference ticket #24595.

---

## Organizations

| Organization | Period | Role |
|---|---|---|
| **UNIVATES** (Centro Universitário, Lajeado/RS) | 1997-2003 | Birthplace of MIOLO and SAGU; free software user since 1997, standardized 1999; CopyLeft holder of early code; saved ~$200k USD in licenses |
| **interact2000** | 2001-2003 | Thomas Spriestersbach's company; co-created early framework |
| **UFJF** (Universidade Federal de Juiz de Fora) | 2002-2010 | Created MIOLO-UFJF variant; Ely Edison Matos led MIOLO 2 architecture; has dedicated SVN branch (`2.5-ufjf`) |
| **SOLIS** (Cooperativa de Soluções Livres) | 2003-2026 | **First cooperative in the world dedicated exclusively to free/open-source software** [1][2][3]. Primary maintainer from 2003 onward. First Canonical-certified Ubuntu support partner in Brazil (2009) [4]. Named among Brazil's best IT companies to work for (Computerworld, 2010). 50+ cooperative members from 20+ cities by 2013. Spinoffs: Sol7, Adianti, ITS |
| **UNILASALLE** (Centro Universitário La Salle) | 2001-2012 | Contributed barcode library; LaSalle-specific login form |
| **UFV** (Universidade Federal de Viçosa) | 2001-2002 | Contributed to MsSQL database driver |
| **UNESCO** | 2002+ | Promoted Gnuteca (built on MIOLO) through workshops in South America |

### SOLIS References

1. [EOF — SOLIS, a Brazilian Free Software Cooperative (Linux Journal, 2003)](https://www.linuxjournal.com/article/7081)
2. [SOLIS — P2P Foundation](https://wiki.p2pfoundation.net/SOLIS)
3. [Coluna do Cesar Brod — SOLIS: 10 anos! (Dicas-L, 2013)](http://www.dicas-l.com.br/brod/brod_201303112119.php)
4. [Canonical certifica SOLIS para suporte ao Ubuntu no Brasil (BR-Linux, 2009)](https://br-linux.org/wparchive/2009/canonical-certifica-solis-para-suporte-ao-ubuntu-no-brasil.php)

---

## Statistics

- **Total SVN revisions:** 9,538
- **Total unique committers:** 31 (30 humans + 1 CI bot)
- **Repository age:** 20 years (2006-03-08 → 2026-02-25)
- **Code predates SVN:** Earliest dated code is from 1999
- **Peak years:** 2014 (1,009 commits) and 2015 (1,594 commits)
- **Current version:** 2.6.879
- **Active branches:** trunk, 2.0, 2.5, 2.6 (+ specialized: php7.2, 2.5-php8, 2.0-univates, 2.5-ufjf)

### Commits by Year

| Year | Commits | Notable Events |
|---|---|---|
| 2006 | 336 | SVN created, initial import |
| 2007 | 45 | Taffarel, Ely join |
| 2008 | 261 | Active framework development |
| 2009 | 166 | MMessage, Dojo integration |
| 2010 | 210 | Daniel Hartmann joins |
| 2011 | 571 | Major UI expansion |
| 2012 | 386 | MIOLO 2.6 branch, jQuery |
| 2013 | 318 | Jonas Rosa joins, SAGU integration |
| 2014 | 1,009 | Peak development begins |
| 2015 | 1,594 | All-time peak |
| 2016 | 613 | Jenkins CI introduced |
| 2017 | 1,350 | Heavy release management |
| 2018 | 715 | PHP 7.2 migration |
| 2019 | 307 | Password security overhaul |
| 2020 | 219 | PHP 7.4 / PostgreSQL 12 |
| 2021 | 294 | PHP 8 early work |
| 2022 | 219 | Custom fields era |
| 2023 | 363 | PHP 8 migration, GED |
| 2024 | 314 | PHP 8 comprehensive migration |
| 2025 | 178 | Maintenance and features |
| 2026 | 70 | Active (as of Feb 25) |