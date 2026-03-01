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

MIOLO's name comes from the Portuguese word for the soft interior of
bread — *miolo*. It was created at **UNIVATES**, a community university
in Lajeado, Rio Grande do Sul, Brazil (population ~60,000), as the
foundation for **SAGU**, an academic management system that replaced
expensive proprietary software.

### What the world looked like in 1999

There were **no PHP frameworks**. PHP 3 had just been released. There was
no Google (it launched in September 1998), no Stack Overflow, no AWS, no
Git. Internet access was dial-up at 28.8 kbps — 56 kbps if you were
lucky. You connected through an ISP, your phone line was busy while you
were online, and you paid by the minute. Documentation lived in books and
mailing lists. Browsers (Netscape Navigator 4, Internet Explorer 5)
couldn't agree on how to render anything. JavaScript was unreliable, so
serious applications relied on full page reloads. Deployments were done
via FTP. Source control meant copying files to a shared folder.

**There were no package managers.** PEAR (PHP Extension and Application
Repository) was the only thing remotely close — a centralized channel for
installing PHP libraries, but nothing like what Composer would become in
2012. If you needed a library, you downloaded a tarball, extracted it, and
hoped the paths worked. Installing PHP itself meant compiling it from
source with `./configure && make && make install`, carefully enabling each
extension you needed as a compile flag — because **extensions were rare
and not bundled**. JSON didn't exist as a PHP extension until 2004 (and
wasn't bundled until PHP 5.2 in 2006). There was no `json_encode()` — if
you needed structured data exchange, you used **XML and SOAP**, the only
standardized web service pattern of the era. REST wouldn't be described
until Roy Fielding's dissertation in 2000, and RESTful APIs didn't become
mainstream until years later.

**Every database driver had to be written from scratch.** There was no
PDO (it arrived in PHP 5.1, 2005). If your application needed to talk to
PostgreSQL, MySQL, Oracle, and SQLite, you wrote four separate
abstraction layers — each with its own connection logic, query syntax
quirks, error handling, and result set iteration. MIOLO did exactly this
(see `classes/database/`). PostgreSQL was at version 6.5 and MySQL at
3.23 — both young, limited, and missing features that developers take for
granted today. There were **no migration tools** — schema changes were
applied by hand with SQL scripts, and you tracked what had been run with
notes or naming conventions.

**There was no caching layer.** Memcached wouldn't be created until 2003.
Redis didn't exist until 2009. APCu, OPcache — none of it. Every page
request hit the database, every time. And the web server landscape was
**Apache alone** — Nginx wouldn't appear until 2004. Apache served
everything: static files, PHP scripts through `mod_php`, and any dynamic
content your application needed, all from a single process model that
was not designed for the concurrency demands of modern web traffic.

**Page rendering was pure HTML and CSS — and barely that.** There was no
Bootstrap (2011), no Tailwind, no Material Design, no component libraries,
no design systems, no frontend build tools, no Sass, no Less. CSS itself
was primitive: no flexbox (2009), no grid (2017), no media queries (2012),
no variables, no transitions. If you wanted a two-column layout, you used
HTML `<table>` elements — because that was the only reliable way to make
it work across Netscape and Internet Explorer. Rounded corners meant
slicing images in Photoshop and assembling them in a 9-cell table. Drop
shadows meant more sliced images. Consistent fonts meant hoping the user
had them installed. Every pixel of layout was a negotiation with browsers
that disagreed on box models, margin collapsing, and float behavior.

MIOLO invented its own **theme system** (see `classes/ui/controls/mtheme.class.php`)
years before theming became a standard concept. Each theme was a
self-contained set of PHP rendering classes, CSS files, and image assets
that controlled how every UI component — forms, grids, menus, toolbars,
dialogs — was rendered to HTML. Switching the entire application's look
meant changing one configuration value. The framework generated all HTML
server-side through PHP objects (`MDiv`, `MForm`, `MGrid`, `MToolbar`),
so developers never wrote raw HTML — they composed UI components in PHP
and the theme decided how to render them. This was the pattern that
frameworks like JSF, Vaadin, and later server-side component systems
would adopt, but MIOLO was doing it at the turn of the millennium with
nothing but `echo` statements and string concatenation.

The very first piece of code in this repository — **PSLib** (1999) — is
a PHP library for generating PostScript files. The university needed to
print academic transcripts, and there were no PDF libraries for PHP.
Vilson Gärtner had to write raw printer instructions: move cursor to
coordinates, set font, draw text, draw lines — every element on the page
was a hand-calculated coordinate and a PostScript operator. Accented
characters (essential for Portuguese) required implementing ISOLatin1
font re-encoding by hand. This is where it all started: writing documents
in printer instructions because there was no other way.

From that foundation, MIOLO grew into a full object-oriented framework
with database abstraction, form handling, session management, theming,
AJAX support, report generation, and a complete UI controls layer. It
powered **SAGU** (academic management, used by dozens of Brazilian
universities), **Gnuteca** (library automation, promoted through UNESCO
workshops), and many other institutional applications.

In 2003, the developers founded **SOLIS** — the first cooperative in the
world dedicated exclusively to free and open-source software. SOLIS
became Canonical's first certified Ubuntu support partner in Brazil in
2009.

**Read the full story:** [CONTRIBUTORS.md](CONTRIBUTORS.md) contains a
comprehensive project history compiled from SVN logs, code comments, and
public sources — including a detailed timeline from 1997 to 2026, profiles
of all 31 contributors, and references to external publications.

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
  SAGU at USENIX ALS'01. The organizational force that made it all
  possible.
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
- [Software Publico Brasileiro — SAGU and Gnuteca (SERPRO)](https://www.serpro.gov.br/menu/noticias/noticias-antigas/software-publico-brasileiro-lanca-dois-programas-para-area-educacional)

---

## License

MIOLO was originally released under the **GNU General Public License v2**
(see [COPYING](COPYING)). Some components (PSLib) are licensed under
**LGPL v2**. Modules developed after 2005 under SOLIS carry a proprietary
license (see individual file headers). Third-party libraries retain their
original licenses.
