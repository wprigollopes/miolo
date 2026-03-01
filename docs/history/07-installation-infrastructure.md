# Installation & Infrastructure: From Compiling PHP to `apt install`

**Installing a PHP application in 1999 meant compiling the language itself,
configuring the web server by hand, setting up the database from source,
and copying files manually. There was no `apt install php`, no Docker, no
Ansible.**

---

## Compiling PHP

In 1999, installing PHP meant:

```bash
# Download the tarball
wget http://www.php.net/distributions/php-3.0.18.tar.gz
tar xzf php-3.0.18.tar.gz
cd php-3.0.18

# Configure with the extensions you need — each one a compile flag
./configure \
    --with-apache=/usr/local/apache \
    --with-pgsql=/usr/local/pgsql \
    --with-gd \
    --enable-track-vars \
    --enable-force-cgi-redirect

# Build and install
make
make install
```

Every extension was a compile-time decision. Need PostgreSQL support?
`--with-pgsql`. Need image manipulation? `--with-gd` (and first compile
libgd from source). Need MySQL? `--with-mysql` (and compile MySQL first).
JSON? It didn't exist as a PHP extension until 2004.

If you forgot an extension, you recompiled. If you needed a new version,
you recompiled. There was no `pecl install`, no `apt install php-pgsql`.

---

## The Linux Distribution Landscape

### The Wild West (1997-2003)

When MIOLO was born, Linux distributions had not standardized where
things went:

| Distribution | Era | PHP/Apache paths | Package manager |
|---|---|---|---|
| **Slackware** | 1993+ | Wherever you compiled them | `installpkg` (tarballs) |
| **Red Hat** | 1995+ | `/usr/lib/httpd/`, `/etc/httpd/` | RPM |
| **Conectiva** | 1997-2005 | Brazilian distro, RPM-based, own paths | `apt-rpm` |
| **Debian** | 1996+ | `/etc/apache/`, `/usr/lib/php/` | `apt-get` (early) |
| **Mandrake** | 1998+ | Own conventions | RPM + `urpmi` |

Each distribution put Apache in a different directory. PHP's `php.ini`
lived in different places. Extension directories varied. There was no
convention for where web applications should be installed.

MIOLO's install script and configuration had to account for all of this.

### The Ubuntu Revolution (2004+)

Ubuntu (first release: October 2004) standardized the Linux desktop and
server experience. By the time Ubuntu gained widespread server adoption
(~2008), you could install a complete LAMP stack with:

```bash
sudo apt-get install apache2 php5 libapache2-mod-php5 php5-pgsql postgresql
```

This was revolutionary. What used to take a day of compiling now took
two minutes.

SOLIS became Canonical's first certified Ubuntu support partner in Brazil
(2009), and MIOLO's deployment infrastructure moved to Ubuntu. By 2012,
Daniel Hartmann created official `.deb` packages for MIOLO:

**File:** `package/ubuntu/create-miolo-package.sh`

```bash
svn export https://svn.solis.coop.br/miolo/branches/2.6 miolo
rm -r miolo/package
cp -a miolo miolo_aux
find miolo_aux -name .svn | xargs rm -r
dpkg -b miolo_aux miolo_2.6_i386.deb
```

From compiling PHP by hand to `dpkg -i miolo.deb` — a fifteen-year journey.

---

## Database Installation

### PostgreSQL

PostgreSQL's installation journey mirrors PHP's:

| Version | Year | Installation |
|---|---|---|
| 6.5 | 1999 | Compile from source. `./configure && make && make install`. Create data directory with `initdb`. Start manually. |
| 7.x | 2000-2005 | RPM/DEB packages start appearing, but versions lag behind. Many still compile from source for latest features. |
| 8.x | 2005-2010 | Distribution packages become reliable. `apt-get install postgresql-8.4`. |
| 9.x+ | 2010+ | `apt install postgresql`. Official APT repository for latest versions. |

In 1999, installing PostgreSQL meant:

```bash
./configure --prefix=/usr/local/pgsql
make
make install
adduser postgres
mkdir /usr/local/pgsql/data
chown postgres /usr/local/pgsql/data
su - postgres -c '/usr/local/pgsql/bin/initdb -D /usr/local/pgsql/data'
su - postgres -c '/usr/local/pgsql/bin/pg_ctl start -D /usr/local/pgsql/data'
```

Then you had to create users, databases, configure `pg_hba.conf` for
authentication — all by hand, consulting the PostgreSQL documentation
(which was, thankfully, excellent even in 1999).

Different servers at different institutions ran different PostgreSQL
versions, with different configurations, different path layouts, and
different authentication setups. MIOLO's database configuration had
to be flexible enough to handle all of them.

---

## Apache Configuration

Apache was the only web server (Nginx wouldn't appear until 2004).
Configuration was done by editing `httpd.conf` — a monolithic file
that controlled everything.

**File:** `etc/install/apache_suggestion`

MIOLO shipped a suggested VirtualHost configuration:

```apacheconf
<VirtualHost *>
    DocumentRoot /usr/local/miolo2-000/html
    <Directory "/usr/local/miolo2-000/html">
        Options FollowSymLinks
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>
```

There was no `a2ensite`, no `sites-available/sites-enabled` pattern
(that's a Debian/Ubuntu convention). You edited the Apache config file
directly and restarted the service.

---

## MIOLO's Installation Methods

### Method 1: The Shell Script Installer (2001)

**File:** `etc/install/install.sh`

A TUI (Text User Interface) installer using `dialog` for terminal menus
or `Xdialog` for graphical X11 menus:

```bash
DIALOG="dialog"
[ -n "$DISPLAY" ] && [ -x ./Xdialog ] && DIALOG="./Xdialog"

INSTALL_PATH="/usr/local/miolo2"
CONFIG_PATH="/usr/local/miolo2/etc/"
```

The installer:
1. Detected whether PostgreSQL or MySQL was installed
2. Verified Apache was running (`pidof httpd`)
3. Copied files to `/usr/local/miolo2/`
4. Created the database and ran seed scripts
5. Generated the configuration file

It supported both English and Portuguese interfaces and had to be run
as root.

### Method 2: The Qt3 GUI Installer (2002)

**File:** `etc/setup/setup.cpp`

A full graphical installer written in C++ with Qt 3:

```cpp
// MIOLO Installation Program - August/2002
// Author: Vilson Cristiano Gartner
#include "setup.h"
#include <qapplication.h>
#include <qfiledialog.h>
#include <qprogressbar.h>
```

This was a desktop wizard application — the kind of installer you'd
expect from a Windows program, but built for Linux with Qt. It shows
how seriously the team took making MIOLO accessible to system
administrators who might not be comfortable with command-line tools.

### Method 3: Manual Copy (Always)

The most common installation method was always: copy the files, edit the
configuration, create the database manually.

```bash
cp -r miolo/ /usr/local/miolo2/
vi /usr/local/miolo2/etc/miolo/miolo.conf
psql -U postgres -c "CREATE DATABASE myapp"
psql -U postgres myapp < /usr/local/miolo2/etc/sql/schema.sql
```

---

## The Configuration File

**File:** `etc/miolo/miolo.conf-dist`

MIOLO's configuration is a plain PHP array — not INI, not XML, not YAML.
It's `include()`d at startup:

```php
$MIOLOCONF['home']['miolo']   = '/home/$usuario/public_html/miolo/classes';
$MIOLOCONF['home']['modules'] = '/home/$usuario/public_html/miolo/modules';
$MIOLOCONF['home']['logs']    = '/home/$usuario/public_html/miolo/logs';

$MIOLOCONF['options']['debug'] = false;

$MIOLOCONF['DB']['common']['system']   = '';
$MIOLOCONF['DB']['common']['host']     = '';

$MIOLOCONF['logs']['level']   = 2;      // 0=off, 1=errors, 2=sql+errors
$MIOLOCONF['logs']['handler'] = 'file'; // file|socket|db|screen

// IP-based dump control
$MIOLOCONF['options']['dump']['peer'] = array('127.0.0.1', '192.168.0.40');

// Chain into module config and startup
include $MIOLOCONF['home']['etc'] . '/' . $module . '.conf';
include $MIOLOCONF['home']['miolo'] . '/startup.inc';
```

This predates `.env` files (dotenv, 2012), YAML configs (Symfony 2, 2011),
and the 12-factor app methodology (2011) by a decade.

---

## CI/CD: Shell Scripts Before Jenkins

Before Jenkins (2004), GitHub Actions (2019), or any CI/CD platform,
continuous integration meant shell scripts.

### The Standardization Script

**File:** `misc/standardize.sh`

A `sed` + `find` script that mass-migrated PHP source code conventions:

```bash
# Convert function names to lowercase first letter
for i in $(find -name \*.php -exec grep "function [A-Z][a-z]" {} -rl \;); do
    sed -i "s/function \([A-Z]\)\([a-z]\)/function \l\1\2/" $i
done

# Convert 'var' to 'public' (PHP 4 → PHP 5 migration)
for i in $(find -name \*.php); do
    sed -i "s/^\( *\)var /\1public /" $i
done

# Rename file extensions
for i in $(find -name \*.inc); do mv $i $i.php; done
```

This is a migration tool — the kind of thing that PHP-CS-Fixer (2012)
or Rector (2018) does today, but built as a shell one-liner.

### The i18n Extraction Script

**File:** `misc/i18n/extract_strings.sh`

Uses `xgettext` to extract translatable strings from PHP source code:

```bash
xgettext --from-code=ISO-8859-1 --keyword='_M:1' -Lphp -f files.txt -o $OUT
```

### The Debian Package Builder

**File:** `package/ubuntu/create-miolo-package.sh`

SVN export → clean `.svn` directories → `dpkg -b` — a complete build
pipeline in a shell script.

### Jenkins + Phing (2009+)

By the end of 2009, SOLIS adopted **Jenkins** (then called Hudson)
combined with **Phing** — PHP's build tool inspired by Apache Ant — to
automate the deployment pipeline. This was a transformative moment:
installations that previously took **weeks** of manual work — compiling
PHP, configuring Apache, setting up PostgreSQL, copying files, running
SQL seeds, adjusting permissions — were reduced to **hours**.

The key insight was aligning MIOLO's deployment with **Ubuntu's
conventions**. Instead of fighting the diversity of Linux distributions
and their different file layouts, the Jenkins+Phing pipeline standardized
on Ubuntu's package structure, default paths (`/etc/apache2/`,
`/usr/share/php/`, `/var/log/`), and service management. This coincided
with SOLIS becoming Canonical's first certified Ubuntu support partner
in Brazil (2009) — the deployment infrastructure and the partnership
reinforced each other.

Phing handled the PHP-specific build steps (generating configuration
files, running database migrations, setting file permissions, compiling
assets), while Jenkins orchestrated the pipeline and managed triggers.
By 2016, the `jenkins` SVN user had accumulated 3,501 automated commits
— primarily version tagging across the 2.0, 2.5, and 2.6 branches.

The evolution was clear:

| Era | Deployment method | Time to deploy |
|---|---|---|
| 1999-2005 | Manual: compile, copy, configure by hand | Days to weeks |
| 2005-2009 | Shell scripts + manual steps | Hours to a day |
| 2009-2016 | Jenkins + Phing, Ubuntu-standardized | Hours |
| 2016+ | Jenkins automated pipeline + `.deb` packages | Minutes |

### Code Quality on the Jenkins Dashboard

The Jenkins setup wasn't just a deployment tool — it was an early
**code quality dashboard**. SOLIS integrated static analysis and metrics
tools directly into the Jenkins panel, years before platforms like
SonarQube Cloud (2013) or GitHub code scanning (2020) made this
mainstream:

- **PHPLOC** — measured codebase size: lines of code, number of classes,
  methods per class, average complexity. Gave the team visibility into
  codebase growth and structural health over time.
- **PHP_CodeSniffer (PHPCS)** — enforced coding standards. Flagged
  inconsistent formatting, naming violations, and style deviations
  across 250+ files maintained by 30+ contributors over two decades.
- **PHPMD (PHP Mess Detector)** — detected code smells: unused
  variables, overly complex methods, excessive class coupling. The kind
  of issues that accumulate silently in a long-lived codebase.
- **phpcpd (Copy/Paste Detector)** — identified duplicated code blocks.
  In a codebase where five database drivers share similar patterns,
  duplication detection was essential for knowing when to refactor.
- **pdepend** — dependency analysis and software metrics (cyclomatic
  complexity, afferent/efferent coupling, instability index).

Jenkins rendered the output of these tools as **trend graphs and build
reports** — clickable dashboards where developers could see, at a
glance, whether the codebase was getting better or worse. Each commit
triggered a full analysis run, and the results were visible to the
entire team.

This was the same workflow that modern platforms provide out of the box:

| SOLIS Jenkins (2009-2013) | Modern equivalent |
|---|---|
| PHPLOC in Jenkins | SonarQube metrics, GitHub code frequency |
| PHPCS in Jenkins | GitHub Actions + PHPCS, pre-commit hooks |
| PHPMD in Jenkins | SonarQube rules, PHPStan, Psalm |
| phpcpd in Jenkins | SonarQube duplication detection |
| pdepend in Jenkins | Deptrac, PHPStan, SonarQube |

The one tool that was *not* adopted at the time was **PHPUnit** —
automated testing was not part of the pipeline. The quality dashboard
focused on static analysis and metrics rather than test coverage. This
was common for the era; many PHP teams adopted code quality tools before
building test suites.

The important thing is not that these tools existed — most were available
by 2009-2010 — but that a small cooperative in southern Brazil was
running them in an automated pipeline and using the dashboards for
decision-making. This was early adoption of the "continuous inspection"
practice that the DevOps movement would later formalize.

This was "CI/CD before CI/CD was a buzzword." GitHub Actions (2019),
GitLab CI (2015), and the modern DevOps movement formalized what teams
like SOLIS were already doing with Jenkins, Phing, and code quality
tooling.

---

## Comparison: Then vs Now

| Aspect | 1999-2005 | 2010+ |
|---|---|---|
| Install PHP | `./configure && make && make install` | `apt install php` |
| Install extensions | Compile flags | `apt install php-pgsql` |
| Install database | Compile from source | `apt install postgresql` |
| Web server config | Edit `httpd.conf` manually | `a2ensite`, nginx conf.d |
| App deployment | Copy files via FTP/SCP | Git pull, Composer install, Docker |
| Configuration | PHP array, `include()`d | `.env` files, YAML, environment vars |
| CI/CD | Shell scripts, cron jobs | Jenkins+Phing (2009), GitHub Actions, GitLab CI |
| Code quality | Manual review, no metrics | Jenkins+PHPLOC/PHPCS/PHPMD (2009), SonarQube, GitHub scanning |
| Packaging | Manual copy | `.deb` packages, Docker images, Helm |
| Rollback | Restore yesterday's backup | `git revert`, blue-green deploy |
| Monitoring | `tail -f error.log` | Prometheus, Grafana, Datadog |

---

## What This Tells Us

The infrastructure evolution from "compile everything from source" to
"`docker compose up`" is arguably the biggest change in software
development over the past 25 years. MIOLO lived through the entire
arc: compiling PHP and PostgreSQL by hand, navigating incompatible Linux
distributions, writing shell scripts for automation, adopting `.deb`
packages, and finally running on standardized Ubuntu servers.

The fact that MIOLO shipped a Qt3 GUI installer in 2002 speaks to the
ambition of the project — they weren't building a hobby tool, they were
building infrastructure for universities that needed professional
installation experiences.
