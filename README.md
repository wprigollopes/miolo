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
- **Henrique Gravina** — Contributed Mac OS X compatibility documentation
  and authored the MIOLO architecture presentation.
- **Jamiel Spezia** — Long-term maintainer (9+ years). JSON fixes, PHP 8
  migration, jQuery plugins, deprecated dependency removal.

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
