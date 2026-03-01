# Package Management: Before Composer

**Need a library? Download the tarball. Read the source code. Hope it works.**

Today, adding a dependency to a PHP project means running
`composer require vendor/package`. Composer resolves version constraints,
downloads the code, generates an autoloader, and updates `composer.lock`.
The entire ecosystem — 400,000+ packages on Packagist — is one command away.

In 1999, none of this existed. Getting code into your project was a
manual, often social, process.

---

## What Didn't Exist

- **Composer** — first release 2012
- **Packagist** — launched 2012 alongside Composer
- **PEAR** — PHP Extension and Application Repository, the closest thing
  to a package manager. Created in 1999, it installed system-wide packages
  via `pear install`. But PEAR was centralized, required root access, had
  no per-project dependencies, and its library coverage was limited
- **npm** (2010), **pip** (2008), **Bundler** (2010) — none of these existed
  either; the concept of per-project dependency management was not yet
  mainstream in any language
- **Semantic versioning** — formalized in 2011
- **Lock files** — a Bundler innovation (2010), adopted by Composer

---

## How You Got Libraries in 2001

### 1. Find It (If You Could)

There was no Packagist, no GitHub, no centralized search. You found
libraries through:

- **Mailing lists** — someone would post "I wrote a PHP class for X"
- **Personal home pages** — developers hosted `.tar.gz` files on their
  `~user/` Apache directories
- **SourceForge** (1999) — the first major open-source hosting platform
- **Freshmeat** (1997) — a directory of open-source releases
- **PEAR** — if the library happened to be there
- **PHP Classes** (phpclasses.org, 1999) — a community directory
- **Word of mouth** — conferences, user groups, IRC channels

Documentation was often a `README.txt` in the tarball. Sometimes there was
a man page. Often there was nothing.

### 2. Download and Extract

```bash
wget http://somesite.com/library-0.3.tar.gz
tar xzf library-0.3.tar.gz
cp -r library-0.3/ /var/www/myproject/lib/
```

### 3. Include It

```php
require_once '/var/www/myproject/lib/library-0.3/main.php';
```

And hope that the library's internal includes used relative paths
correctly, that it didn't conflict with your existing classes, and that
it worked with your version of PHP.

### 4. Or Just Copy the Code

If the library was small enough — a single class file, a few functions —
you copied it directly into your project. MIOLO's `classes/contrib/`
directory is evidence of this pattern:

| File | Author | Source |
|---|---|---|
| `dbug.class.php` | Kwaku Otchere | dBug debugger |
| `EasyDownload.class.php` | Olavo Alexandrino | EasyDownload file handler |
| `mcsvdump.class.php` | Ivan Ariel Melgrati | IAM_CSVDump |
| `class.pdf.php` | Wayne Munro | ezPDF |
| `cpaint2.inc.php` | Paul Sullivan, Dominique Stender | CPaint2 AJAX toolkit |

These files were downloaded, sometimes modified, and committed directly
into the MIOLO repository. There was no `composer.json` pointing to an
external source. The code *was* the dependency.

### 5. Or Get It From the Author Personally

In the Brazilian free software community of 2001, "package management"
sometimes meant sending an email to the author, or meeting them at a
conference (FISL — Forum Internacional Software Livre — was the main
gathering), getting a copy of their code on a floppy disk or CD-R,
and integrating it by hand.

The barcode library in MIOLO (`classes/utils/barcode/`) was contributed
by Rudinei Pereira Dias from UNILASALLE — a university 120 km away.
The MsSQL driver included contributions from Clausius Duque G. Reis at
UFV (Universidade Federal de Vicosa) — 1,500 km away. These weren't
`composer require` operations; they were collaborations between people
at different institutions who shared code through direct contact.

---

## No INSTALL Instructions (or Rare Ones)

Most PHP libraries of the era had no installation documentation. You
were expected to:

1. Read the source code to understand its structure
2. Figure out which file to `include()`
3. Resolve any dependencies yourself
4. Adjust paths as needed for your project

MIOLO itself had an `INSTALL` file, but it was one of the few. The
instructions assumed you had already compiled PHP from source, configured
Apache, and set up PostgreSQL — the kind of setup that could take an
entire day.

Libraries that *did* have documentation typically provided a `README`
with a usage example and nothing else. No API docs, no PHPDoc, no
generated documentation sites.

---

## How MIOLO Managed Its Own Dependencies

### The `classes/contrib/` Pattern

Third-party code was vendored directly into the repository:

```
classes/contrib/
    dbug.class.php          # Copied from phpclasses.org
    EasyDownload.class.php  # Copied from author's website
classes/extensions/
    cpaint2.inc.php         # CPaint2 AJAX toolkit, modified
    mcsvdump.class.php      # CSV dump, modified
classes/ezpdf/
    class.pdf.php           # R&OS ezPDF, vendored wholesale
```

Modifications were made directly to the vendored files. There was no
concept of "don't modify vendor code" because there was no vendor
directory, no update mechanism, and no way to pull upstream changes.

### The PSLib Model: Build It Yourself

When no library existed for what you needed, you built it. MIOLO's
PostScript library (PSLib) was created because there was no free PDF
generation for PHP. The Jasper Reports integration was built because
PHP had no native report engine. The database abstraction layer was
written because PDO didn't exist.

This was the default mode of development: if a tool doesn't exist,
write it.

---

## Comparison: Then vs Now

| Aspect | 1999-2005 | 2012+ (Composer era) |
|---|---|---|
| Finding libraries | Mailing lists, SourceForge, word of mouth | Packagist search, GitHub |
| Installing | Download tarball, copy files | `composer require` |
| Version management | Hope the API didn't change | Semantic versioning + lock files |
| Updates | Re-download, manually diff | `composer update` |
| Dependency resolution | Manual — you figure it out | Automatic constraint solving |
| Autoloading | Manual `require_once` chains | PSR-4 autoload via `vendor/autoload.php` |
| Vendoring | Copy into your project tree | `vendor/` directory, `.gitignore`d |
| Documentation | README.txt if you're lucky | Packagist + README + generated API docs |
| Security advisories | None | `composer audit`, Roave advisories |

---

## What This Tells Us

The "dependency management" problem didn't appear with Composer — it was
always there. Teams like MIOLO's solved it through a combination of
vendoring, personal networks, and building from scratch. The cost was
enormous: every library required manual integration, manual updates, and
manual conflict resolution. But the code worked, shipped to production,
and ran for decades.

When Composer arrived in 2012, it didn't invent a new workflow — it
automated the workflow that teams had been doing by hand for fifteen years.
