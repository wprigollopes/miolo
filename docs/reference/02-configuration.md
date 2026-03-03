# Installation & Configuration

[Back to Index](index.md)

## Installation

### Prerequisites

- Apache web server
- PHP 5.x+
- A supported database (PostgreSQL, MySQL, Oracle, SQLite, etc.)

### From Repository (SVN)

```bash
cd /home/user/projects
svn co https://svn.solis.coop.br/miolo/branches/2.6 miolo-2.6
```

### Apache Virtual Host

```apache
<VirtualHost *:80>
    ServerAdmin admin@miolo.org.br
    ServerName miolo.org.br
    DocumentRoot /home/user/projects/miolo-2.6/html
    <Directory /home/user/projects/miolo-2.6/html>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride None
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>
```

### Initial Configuration

```bash
cd miolo-2.6
bin/miolo.php configure http://miolo.org.br
```

---

## Main Configuration: `miolo.conf`

The main configuration file is XML-based, located at `<miolo>/etc/miolo.conf`.

### `<home>` -- File and URL Locations

```xml
<home>
    <miolo>/usr/local/miolo</miolo>
    <classes>/usr/local/miolo/classes</classes>
    <modules>/usr/local/miolo/modules</modules>
    <etc>/usr/local/miolo/etc</etc>
    <logs>/usr/local/miolo/var/log</logs>
    <trace>/usr/local/miolo/var/trace</trace>
    <db>/usr/local/miolo/var/db</db>
    <html>/usr/local/miolo/html</html>
    <themes>/usr/local/miolo/classes/ui/themes</themes>
    <reports>/usr/local/miolo/var/reports</reports>
    <images>/usr/local/miolo/html/images</images>
    <url>http://miolo.domain.com</url>
    <url_themes>/themes</url_themes>
    <url_reports>/reports</url_reports>
    <module.themes>/ui/themes</module.themes>
    <module.html>/html</module.html>
    <module.images>/html/images</module.images>
    <url_jasper>http://127.0.0.1:8080/MioloJasper/Report</url_jasper>
</home>
```

### `<namespace>` -- Path Aliases

```xml
<namespace>
    <core>/classes</core>
    <service>/classes/services</service>
    <ui>/classes/ui</ui>
    <themes>/ui/themes</themes>
    <extensions>/classes/extensions</extensions>
    <controls>/ui/controls</controls>
    <database>/classes/database</database>
    <util>/classes/util</util>
    <modules>/modules</modules>
</namespace>
```

### `<theme>` -- Visual Theme

```xml
<theme>
    <module></module>
    <main>kenobi</main>
    <lookup>kenobi</lookup>
    <title>Miolo Web Application</title>
    <company>Your Organization</company>
    <system>System Name</system>
    <logo>logo.gif</logo>
    <email>admin@example.com</email>
    <options>
        <close>true</close>
        <minimize>true</minimize>
        <help>true</help>
        <move>true</move>
    </options>
</theme>
```

### `<session>` -- Session Handling

```xml
<session>
    <handler>db</handler>     <!-- 'files' or 'db' -->
    <timeout>20</timeout>     <!-- minutes of inactivity -->
</session>
```

### `<options>` -- Framework Options

```xml
<options>
    <startup>common</startup>             <!-- default startup module -->
    <scramble>0</scramble>                <!-- URL scrambling -->
    <scramble.password>password</scramble.password>
    <dispatch>index.php</dispatch>
    <url.style>0</url.style>             <!-- 0: ?module=...&action=...  1: /module/action -->
    <authmd5>false</authmd5>
    <mainmenu>3</mainmenu>               <!-- 0:none 1:theme 2:Tigra DHTML 3:JsCookMenu -->
    <mainmenu.style>office2003</mainmenu.style>
    <mainmenu.clickopen>false</mainmenu.clickopen>
    <dbsession>0</dbsession>
    <debug>1</debug>
    <dump>
        <peer>127.0.0.1</peer>
        <profile>false</profile>
        <uses>false</uses>
        <trace>false</trace>
        <handlers>false</handlers>
    </dump>
    <loading>
        <show>true</show>
        <generating>true</generating>
    </loading>
    <performance>
        <uri_images>true</uri_images>
        <uri_themes>true</uri_themes>
    </performance>
</options>
```

### `<db>` -- Database Connections

```xml
<db>
    <miolo>                               <!-- connection name -->
        <system>sqlite</system>           <!-- firebird|mysql|postgres|sqlite|oracle8|mssql|odbc -->
        <host>localhost</host>
        <name>/usr/local/miolo/var/db/miolo.sqlite</name>
        <user>miolo</user>
        <password>miolo</password>
    </miolo>
    <admin>
        <system>postgres</system>
        <host>dbserver</host>
        <name>admin_db</name>
        <user>miolo</user>
        <password>secret</password>
    </admin>
</db>
```

### `<login>` -- Authentication

```xml
<login>
    <module>admin</module>
    <class>MAuthDb</class>                <!-- MAuthDb or MAuthDbMD5 -->
    <check>1</check>                      <!-- require login -->
    <shared>1</shared>                    <!-- shared authentication -->
    <auto>user1</auto>                    <!-- auto-login user (optional) -->
    <user1>
        <id>test</id>
        <password>pass</password>
        <name>Test User</name>
    </user1>
</login>
```

**Authentication parameter matrix:**

| check | shared | auto  | Result |
|-------|--------|-------|--------|
| true  | true   | false | User must exist in `miolo_user` |
| true  | false  | false | User must exist in `miolo_user` |
| false | true   | false | No `miolo_user` registration needed |
| true  | true   | true  | Pre-defined user must exist in `miolo_user` |
| false | true   | true  | Pre-defined user not needed in `miolo_user` |

### `<logs>` -- Logging

```xml
<logs>
    <level>2</level>              <!-- 0:none 1:errors only 2:errors+messages+SQL -->
    <handler>socket</handler>     <!-- 'socket', 'db', or 'file' -->
    <peer>127.0.0.1</peer>
    <port>9999</port>
</logs>
```

### `<i18n>` -- Internationalization

```xml
<i18n>
    <locale>/usr/local/miolo/locale</locale>
    <language>pt_BR</language>
</i18n>
```

### `<mad>` -- Administration Module

```xml
<mad>
    <module>admin</module>
    <classes>
        <access>access</access>
        <group>group</group>
        <log>log</log>
        <session>session</session>
        <transaction>transaction</transaction>
        <user>user</user>
    </classes>
</mad>
```

---

## Module Configuration: `module.conf`

Each module has its own configuration file at `<miolo>/modules/<module>/etc/module.conf`.
Module settings override the global `miolo.conf` values.

```xml
<?xml version="1.0" encoding="ISO-8859-1" standalone="yes"?>
<conf>
    <options>
        <startup>mymodule</startup>        <!-- module as startup -->
    </options>
    <db>
        <mydb>
            <system>sqlite</system>
            <host>localhost</host>
            <name><miolo>/modules/mymodule/sql/data.db</name>
            <user>miolo</user>
            <password>miolo</password>
        </mydb>
    </db>
    <theme>
        <module>miolo</module>
        <main>clean</main>
        <lookup></lookup>
        <title>My Module Title</title>
    </theme>
    <login>
        <check>0</check>                   <!-- 0 = no authentication required -->
    </login>
</conf>
```

---

## CLI Utility: `bin/miolo.php`

The command-line utility provides scaffolding and configuration management.

```bash
bin/miolo.php <command> [<parameters>]
```

| Command | Usage | Description |
|---------|-------|-------------|
| `configure` | `configure [url]` | Creates initial configuration file |
| `setconfig` | `setconfig <key> <value> [module]` | Sets a config parameter (use `.` as separator) |
| `getconfig` | `getconfig <key> [module]` | Gets a config parameter value |
| `removeconfig` | `removeconfig <key> [module]` | Removes a config parameter |
| `createmodule` | `createmodule <name> [base_module]` | Creates a module with MVC structure |
| `createhandler` | `createhandler <module> <handler> <title> <form>` | Creates a handler file |
| `createform` | `createform <module> <form> <title>` | Creates a form class |
| `createsearchform` | `createsearchform <module> <form> <title> <grid> <table> [filters...]` | Creates a search form with grid |
| `creategrid` | `creategrid <module> <grid> <title> [columns...]` | Creates a grid class |
| `createbusiness` | `createbusiness <module> <table>` | Creates a business class for a table |
| `createmvc` | `createmvc <module>` | Creates a full MVC structure (handler, business, forms, grid, SQLite DB) |
| `createtheme` | `createtheme <name> <base_theme>` | Creates a theme based on existing one |
| `start` | `start <path>` | Creates a new MIOLO installation using the same base |
| `translate extract` | `translate extract` | Extracts untranslated strings to `.po` files |
| `translate generate` | `translate generate` | Compiles translation files |
| `help` | `help [command]` | Shows help |

---

## MAD Database Schema

The MIOLO Administration (MAD) module uses the following tables (typically SQLite):

```sql
CREATE TABLE miolo_sequence (
    sequence     CHAR(20)    NOT NULL,
    value        INTEGER
);

CREATE TABLE miolo_user (
    iduser       INTEGER     NOT NULL,
    login        CHAR(25),
    name         VARCHAR(80),
    nickname     CHAR(25),
    m_password   CHAR(40),
    confirm_hash CHAR(40),
    theme        CHAR(20)
);

CREATE TABLE miolo_transaction (
    idtransaction INTEGER    NOT NULL,
    m_transaction CHAR(30)
);

CREATE TABLE miolo_group (
    idgroup      INTEGER     NOT NULL,
    m_group      CHAR(50)
);

CREATE TABLE miolo_access (
    idgroup      INTEGER     NOT NULL,
    idtransaction INTEGER    NOT NULL,
    rights       INTEGER
);

CREATE TABLE miolo_session (
    idsession    INTEGER     NOT NULL,
    tsin         CHAR(15),
    tsout        CHAR(15),
    name         CHAR(50),
    sid          CHAR(40),
    forced       CHAR(1),
    remoteaddr   CHAR(15),
    iduser       INTEGER     NOT NULL
);

CREATE TABLE miolo_log (
    idlog        INTEGER     NOT NULL,
    m_timestamp  CHAR(15),
    description  VARCHAR(200),
    module       CHAR(25),
    class        CHAR(25),
    iduser       INTEGER     NOT NULL,
    idtransaction INTEGER   NOT NULL
);

CREATE TABLE miolo_groupuser (
    iduser       INTEGER     NOT NULL,
    idgroup      INTEGER     NOT NULL
);
```
