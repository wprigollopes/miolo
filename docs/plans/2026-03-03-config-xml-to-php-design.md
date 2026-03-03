# Config XML to PHP Conversion — Implementation Plan

> **Status:** COMPLETED (2026-03-03)

**Goal:** Convert all XML `.conf` configuration files to PHP return-array files (PSR pattern) and update the loader/consumers.

**Architecture:** `MConfigLoader` switches from XML parsing (MSimpleXml) to `require`-based PHP array loading. A new `flattenArray()` method converts nested arrays to dot-notation keys so all existing `getConf('key.subkey')` calls remain unchanged. Config generation switches from DOM XML to `var_export` PHP output.

**Note:** `MSimpleXml` class is KEPT — it's used by `mAutoLoad.php`, `mKrono.php` (removed), `mCompatibility.php`, `mUI.php`, and `business.php`. Only decoupled from config loading.

**Tech Stack:** PHP 7.4+, PSR return-array pattern

---

### Task 1: Update MConfigLoader Core

**Files:**
- Modify: `classes/utils/mConfigLoader.php`

**Step 1: Replace `loadConf()` to use `require` instead of MSimpleXml**

Replace the existing `loadConf` method (lines 41-51) with:

```php
public function loadConf($module = '', $file = '')
{
    $dir = substr($_SERVER['SCRIPT_FILENAME'], 0, strrpos($_SERVER['SCRIPT_FILENAME'], '/html'));
    $fname = ($file == '') ? $dir . (($module == '') ? '/etc/miolo.php' : '/modules/' . $module . '/etc/module.php') : $file;

    if (file_exists($fname))
    {
        $data = require $fname;

        if (is_array($data))
        {
            $this->flattenArray($data, '', $this->conf);
        }
    }
}
```

**Step 2: Add `flattenArray()` helper method**

Add after `loadConf()`:

```php
private function flattenArray(array $array, string $prefix, ?array &$result): void
{
    if ($result === null)
    {
        $result = [];
    }

    foreach ($array as $key => $value)
    {
        $flatKey = ($prefix !== '') ? $prefix . '.' . $key : $key;

        if (is_array($value) && !array_is_list($value))
        {
            $this->flattenArray($value, $flatKey, $result);
        }
        else
        {
            $result[$flatKey] = $value;
        }
    }
}
```

Note: `array_is_list()` (PHP 8.1+) is used to preserve indexed arrays (like mkrono day/month lists) without flattening them. If targeting PHP < 8.1, use a polyfill:

```php
if (!function_exists('array_is_list')) {
    function array_is_list(array $array): bool {
        $i = 0;
        foreach ($array as $k => $v) {
            if ($k !== $i++) return false;
        }
        return true;
    }
}
```

**Step 3: Replace `generateConfigXML()` with `generateConfigPHP()`**

Replace the entire `generateConfigXML` method (lines 151-362) with:

```php
public function generateConfigPHP($data, $confModule = null)
{
    (!$confModule) ? $confModule = \MIOLO::_REQUEST('confModule') : null;
    !$confModule ? $confModule = 'miolo' : null;

    $config = [];

    // home
    $homeKeys = ['miolo','classes','modules','etc','logs','trace','db','html',
                 'themes','extensions','reports','images','url',
                 'url_themes','url_reports','module.themes','module.html','module.images'];
    foreach ($homeKeys as $k) {
        if (!empty($data["home.$k"])) $config['home'][$k] = $data["home.$k"];
    }

    // namespace
    $nsKeys = ['core','service','ui','themes','extensions','controls','database','utils','modules'];
    foreach ($nsKeys as $k) {
        if (!empty($data["namespace.$k"])) $config['namespace'][$k] = $data["namespace.$k"];
    }

    // theme
    $themeKeys = ['module','main','lookup','title','company','system','logo','email'];
    foreach ($themeKeys as $k) {
        if (!empty($data["theme.$k"])) $config['theme'][$k] = $data["theme.$k"];
    }
    if (!empty($data['theme.options.close'])) {
        $config['theme']['options']['close'] = $data['theme.options.close'];
    }

    // options
    $optKeys = ['startup','common','scramble','scramble.password','dispatch','url.style',
                'index','mainmenu','mainmenu.style','mainmenu.clickopen','dbsession',
                'authmd5','debug','autocomplete_alert','charset','fileextension','json_encode'];
    foreach ($optKeys as $k) {
        if (isset($data["options.$k"]) && $data["options.$k"] !== '') {
            $config['options'][$k] = $data["options.$k"];
        }
    }
    $dumpKeys = ['peer','profile','uses','trace','handlers'];
    foreach ($dumpKeys as $k) {
        if (!empty($data["options.dump.$k"])) $config['options']['dump'][$k] = $data["options.dump.$k"];
    }
    $loadingKeys = ['show','generating'];
    foreach ($loadingKeys as $k) {
        if (!empty($data["options.loading.$k"])) $config['options']['loading'][$k] = $data["options.loading.$k"];
    }

    // i18n
    if (!empty($data['i18n.locale']))   $config['i18n']['locale']   = $data['i18n.locale'];
    if (!empty($data['i18n.language'])) $config['i18n']['language'] = $data['i18n.language'];

    // mad
    if (!empty($data['mad.module'])) $config['mad']['module'] = $data['mad.module'];
    $madKeys = ['access','group','log','session','transaction','user'];
    foreach ($madKeys as $k) {
        if (!empty($data["mad.classes.$k"])) $config['mad']['classes'][$k] = $data["mad.classes.$k"];
    }

    // login
    $loginKeys = ['module','class','check','shared','auto'];
    foreach ($loginKeys as $k) {
        if (isset($data["login.$k"]) && $data["login.$k"] !== '') {
            $config['login'][$k] = $data["login.$k"];
        }
    }

    // session
    if (!empty($data['session.handler'])) $config['session']['handler'] = $data['session.handler'];
    if (!empty($data['session.timeout'])) $config['session']['timeout'] = $data['session.timeout'];

    // db
    $dbKeys = ['system','host','name','user','password'];
    foreach ($dbKeys as $k) {
        if (isset($data["db.$confModule.$k"]) && $data["db.$confModule.$k"] !== '') {
            $config['db'][$confModule][$k] = $data["db.$confModule.$k"];
        }
    }

    // logs
    $logKeys = ['level','handler','peer','port'];
    foreach ($logKeys as $k) {
        if (isset($data["logs.$k"]) && $data["logs.$k"] !== '') {
            $config['logs'][$k] = $data["logs.$k"];
        }
    }

    $export = var_export($config, true);
    // Convert array() to [] syntax
    $export = preg_replace('/array \(/', '[', $export);
    $export = preg_replace('/\)$/', ']', $export);
    $export = preg_replace('/\)(\s*,)/', ']$1', $export);
    $export = preg_replace('/\)(\s*\])/', ']$1', $export);

    return "<?php\n\nreturn " . $export . ";\n";
}
```

**Step 4: Commit**

```
git add classes/utils/mConfigLoader.php
git commit -m "refactor: switch MConfigLoader from XML to PHP array loading"
```

---

### Task 2: Convert etc/ XML Config Files to PHP

**Files:**
- Create: `etc/miolo.php.dist`
- Create: `etc/mkrono.php`
- Create: `etc/passwd.php`
- Create: `etc/passwd.php.dist`
- Delete: `etc/miolo.conf.dist`, `etc/mkrono.conf`, `etc/passwd.conf`, `etc/passwd.conf.dist`

**Step 1: Create `etc/miolo.php.dist`**

```php
<?php

return [
    'home' => [
        'miolo'         => '/var/www/miolo',
        'classes'       => '/var/www/miolo/classes',
        'modules'       => '/var/www/miolo/modules',
        'etc'           => '/var/www/miolo/etc',
        'logs'          => '/var/www/miolo/var/log',
        'trace'         => '/var/www/miolo/var/trace',
        'db'            => '/var/www/miolo/var/db',
        'html'          => '/var/www/miolo/html',
        'themes'        => '/var/www/miolo/html/themes',
        'extensions'    => '/var/www/miolo/classes/extensions',
        'reports'       => '/var/www/miolo/var/reports',
        'images'        => '/var/www/miolo/ui/images',
        'url'           => 'http://miolo.localhost',
        'url_themes'    => '/themes',
        'url_reports'   => '/reports',
        'module.themes' => '/ui/themes',
        'module.html'   => '/html',
        'module.images' => '/html/images',
        'java'          => '/home/java',
    ],
    'namespace' => [
        'core'       => '/classes',
        'service'    => '/classes/services',
        'ui'         => '/classes/ui',
        'themes'     => '/ui/themes',
        'extensions' => '/classes/extensions',
        'controls'   => '/ui/controls',
        'database'   => '/classes/database',
        'utils'      => '/classes/utils',
        'modules'    => '/modules',
        'business'   => '/db',
    ],
    'theme' => [
        'main'    => 'modern',
        'lookup'  => 'modern',
        'title'   => 'Miolo Web Application',
        'company' => 'MIOLO FRAMEWORK',
        'system'  => 'version 2.0 - rc1',
        'logo'    => 'logo_miolo.png',
        'email'   => 'admin@miolo2.localhost',
        'options' => [
            'close' => 'false',
        ],
    ],
    'options' => [
        'startup'           => 'common',
        'common'            => 'common',
        'scramble'          => '0',
        'scramble.password' => 'password',
        'dispatch'          => 'index.php',
        'url.style'         => '0',
        'index'             => 'index.php',
        'mainmenu'          => '3',
        'mainmenu.style'    => 'office2003',
        'mainmenu.clickopen'=> 'true',
        'dbsession'         => '0',
        'authmd5'           => '0',
        'debug'             => '0',
        'charset'           => 'UTF-8',
        'fileextension'     => '25',
        'dump' => [
            'peer'     => '127.0.0.1',
            'profile'  => 'false',
            'uses'     => 'false',
            'trace'    => 'false',
            'handlers' => 'false',
        ],
        'loading' => [
            'show'       => 'true',
            'generating' => 'true',
        ],
        'json_encode' => 'cpaint',
    ],
    'i18n' => [
        'locale'   => '/var/www/miolo/locale/',
        'language' => 'pt_BR',
    ],
    'mad' => [
        'module'  => 'admin',
        'classes' => [
            'access'      => 'access',
            'group'       => 'group',
            'log'         => 'log',
            'session'     => 'session',
            'transaction' => 'transaction',
            'user'        => 'user',
        ],
    ],
    'login' => [
        'module' => 'admin',
        'class'  => 'MAuthDb',
        'check'  => 'false',
        'shared' => 'true',
        'auto'   => '1',
    ],
    'session' => [
        'handler' => 'file',
        'timeout' => '300',
        'shared'  => 'true',
    ],
    'db' => [
        'miolo' => [
            'system'   => 'sqlite',
            'host'     => 'localhost',
            'name'     => '/var/www/miolo/var/db/miolo.sqlite',
            'user'     => 'CHANGE_ME',
            'password' => 'CHANGE_ME',
        ],
        'dummy' => [
            'system'      => 'mysql',
            'host'        => 'localhost',
            'name'        => 'dummy',
            'user'        => 'root',
            'password'    => '',
            'jdbc_driver' => 'com.mysql.jdbc.Driver',
            'jdbc_db'     => 'jdbc:mysql://localhost/dummy',
        ],
    ],
    'logs' => [
        'level'   => '2',
        'handler' => 'socket',
        'peer'    => '127.0.0.1',
        'port'    => '0',
    ],
];
```

**Step 2: Create `etc/mkrono.php`**

```php
<?php

return [
    'language'   => 'br',
    'dateFormat' => 'br',
    'timeFormat' => 'br',
    'separator'  => '/',
    'longDay' => [
        'br' => ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'],
        'en' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
    ],
    'longMonth' => [
        'br' => ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        'en' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    ],
    'shortDay' => [
        'br' => ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
        'en' => ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
    ],
    'shortMonth' => [
        'br' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        'en' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    ],
];
```

**Step 3: Create `etc/passwd.php`**

```php
<?php

return [
    'db' => [],
];
```

**Step 4: Create `etc/passwd.php.dist`**

```php
<?php

return [
    'db' => [
        'miolo' => [
            'password' => 'CHANGE_ME',
        ],
    ],
];
```

**Step 5: Delete old XML files and commit**

```bash
git rm etc/miolo.conf.dist etc/mkrono.conf etc/passwd.conf etc/passwd.conf.dist
git add etc/miolo.php.dist etc/mkrono.php etc/passwd.php etc/passwd.php.dist
git commit -m "refactor: convert etc/ XML config files to PHP return arrays"
```

---

### Task 3: Convert etc/miolo/*.conf to PHP Return Arrays

**Files:**
- Create: `etc/miolo/biblioteca.php`, `vestibular.php`, `tutorial3.php`, `persistence.php`, `common.php`, `adm.php`, `deteste.php`, `rh.php`
- Delete: all corresponding `.conf` files in `etc/miolo/`

**Step 1: Create each PHP file**

`etc/miolo/biblioteca.php`:
```php
<?php

return [];
```

`etc/miolo/vestibular.php`:
```php
<?php

return [
    'theme' => [
        'main'   => 'vestibular',
        'lookup' => 'vestibular',
        'title'  => 'UFJF - Programas de Ingresso',
    ],
    'login' => [
        'check' => false,
    ],
    'db' => [
        'ingresso' => [
            'system'   => 'oracle8',
            'host'     => 'localhost',
            'name'     => 'CHANGE_ME',
            'user'     => 'CHANGE_ME',
            'password' => 'CHANGE_ME',
        ],
    ],
];
```

`etc/miolo/tutorial3.php`:
```php
<?php

return [
    'tutorial3' => [
        'options' => [
            'persistence' => false,
        ],
    ],
];
```

`etc/miolo/persistence.php`:
```php
<?php

return [
    'persistence' => [
        'options' => [
            'persistence' => true,
        ],
    ],
];
```

`etc/miolo/common.php`:
```php
<?php

$config = [
    'common' => [
        'options' => [
            'persistence' => false,
        ],
    ],
];

if (($action ?? '') === 'lookup') {
    $config['login'] = ['check' => false];
}

return $config;
```

`etc/miolo/adm.php`:
```php
<?php

return [
    'tutorial3' => [
        'options' => [
            'persistence' => false,
        ],
    ],
];
```

`etc/miolo/deteste.php`:
```php
<?php

return [
    'tutorial3' => [
        'options' => [
            'persistence' => true,
        ],
    ],
];
```

`etc/miolo/rh.php`:
```php
<?php

return [
    'tutorial3' => [
        'options' => [
            'persistence' => true,
        ],
    ],
    'theme' => [
        'main'   => 'rh',
        'lookup' => 'rh',
    ],
];
```

**Step 2: Delete old files and commit**

```bash
git rm etc/miolo/biblioteca.conf etc/miolo/vestibular.conf etc/miolo/tutorial3.conf \
       etc/miolo/persistence.conf etc/miolo/common.conf etc/miolo/adm.conf \
       etc/miolo/deteste.conf etc/miolo/rh.conf
git add etc/miolo/*.php
git commit -m "refactor: convert etc/miolo/ module configs to PHP return arrays"
```

---

### Task 4: Convert Module Config Files

**Files:**
- Create: `modules/{base,admin,admin_ldap,common,hangman,helloworld,locadora,mobileexample,persistence}/etc/module.php`
- Delete: all corresponding `module.conf` files

**Step 1: Create each module.php**

`modules/base/etc/module.php`:
```php
<?php

return [];
```

`modules/admin/etc/module.php`:
```php
<?php

return [
    'namespace' => [
        'business' => '/db',
    ],
    'options' => [
        'startup' => 'admin',
    ],
];
```

`modules/admin_ldap/etc/module.php`:
```php
<?php

return [
    'db' => [
        'admin_ldap' => [
            'system'   => 'postgres',
            'host'     => 'localhost',
            'port'     => '5432',
            'name'     => 'CHANGE_ME',
            'user'     => 'CHANGE_ME',
            'password' => 'CHANGE_ME',
        ],
    ],
    'login' => [
        'ldap' => [
            'host'      => 'CHANGE_ME',
            'port'      => '389',
            'base'      => 'dc=example,dc=com',
            'user'      => 'cn=Admin,dc=example,dc=com',
            'password'  => 'CHANGE_ME',
            'userName'  => 'cn',
            'userEmail' => 'mail',
            'schema'    => 'system',
        ],
    ],
];
```

`modules/common/etc/module.php`:
```php
<?php

return [];
```

`modules/hangman/etc/module.php`:
```php
<?php

return [
    'options' => [
        'startup' => 'hangman',
    ],
    'login' => [
        'check' => '0',
    ],
];
```

`modules/helloworld/etc/module.php`:
```php
<?php

return [
    'options' => [
        'startup' => 'helloworld',
    ],
];
```

`modules/locadora/etc/module.php`:
```php
<?php

return [
    'db' => [
        'locadora' => [
            'system'   => 'sqlite',
            'host'     => 'localhost',
            'name'     => '/usr/local/miolo2/modules/locadora/sql/locadora.sqlite',
            'user'     => 'CHANGE_ME',
            'password' => 'CHANGE_ME',
        ],
    ],
    'theme' => [
        'module' => 'miolo',
        'main'   => 'system',
        'lookup' => 'system',
        'title'  => 'Miolo Web Application - LOCADORA',
    ],
    'login' => [
        'check' => '0',
    ],
];
```

`modules/mobileexample/etc/module.php`:
```php
<?php

return [
    'options' => [
        'startup' => 'mobileexample',
        'common'  => 'mobileexample',
    ],
    'login' => [
        'module' => 'mobileexample',
        'class'  => 'MAuthDb',
        'check'  => 'false',
        'shared' => 'true',
        'auto'   => '1',
    ],
];
```

`modules/persistence/etc/module.php`:
```php
<?php

return [
    'db' => [
        'persistence' => [
            'system'   => 'postgres',
            'host'     => 'localhost',
            'name'     => 'persistence',
            'user'     => 'CHANGE_ME',
            'password' => 'CHANGE_ME',
        ],
    ],
    'login' => [
        'check' => '0',
    ],
];
```

**Step 2: Delete old files and commit**

```bash
git rm modules/base/etc/module.conf modules/admin/etc/module.conf \
       modules/admin_ldap/etc/module.conf modules/common/etc/module.conf \
       modules/hangman/etc/module.conf modules/helloworld/etc/module.conf \
       modules/locadora/etc/module.conf modules/mobileexample/etc/module.conf \
       modules/persistence/etc/module.conf
git add modules/*/etc/module.php
git commit -m "refactor: convert module.conf files to PHP return arrays"
```

---

### Task 5: Update mKrono.php to Load PHP Config

**Files:**
- Modify: `classes/utils/mKrono.php` (lines 159-176)

**Step 1: Replace XML loading with PHP array loading**

Replace the MSimpleXML loading block (lines 159-176) with:

```php
$this->path = $MIOLO->getConf('home.etc') . '/mkrono.php';
$data = require $this->path;

$this->lan = $data['language'];
$this->dateFormat = $data['dateFormat'];
$this->separator = $data['separator'];
$this->timeFormat = $data['timeFormat'];
$this->error = '';

$this->_checkIniFile();

$lan = $this->lan;
$this->dayNameExt[$lan] = $data['longDay'][$lan];
$this->dayNameCon[$lan] = $data['shortDay'][$lan];
$this->monthNameExt[$lan] = $data['longMonth'][$lan];
$this->monthNameCon[$lan] = $data['shortMonth'][$lan];

// Load English as fallback
if (isset($data['longDay']['en'])) {
    $this->dayNameExt['en'] = $data['longDay']['en'];
    $this->dayNameCon['en'] = $data['shortDay']['en'];
    $this->monthNameExt['en'] = $data['longMonth']['en'];
    $this->monthNameCon['en'] = $data['shortMonth']['en'];
}
```

**Step 2: Commit**

```bash
git add classes/utils/mKrono.php
git commit -m "refactor: update mKrono to load PHP config instead of XML"
```

---

### Task 6: Update mIOLO.php References

**Files:**
- Modify: `classes/mIOLO.php`

**Step 1: Update passwd.conf reference**

At line 1531, change:
```php
$this->conf->loadConf('','../etc/passwd.conf');
```
to:
```php
$this->conf->loadConf('','../etc/passwd.php');
```

**Step 2: Commit**

```bash
git add classes/mIOLO.php
git commit -m "refactor: update mIOLO passwd.conf reference to passwd.php"
```

---

### Task 7: Update Admin Module Forms

**Files:**
- Modify: `modules/admin/forms/frmConf.php`
- Modify: `modules/admin/forms/frmSetupModuleBD.php`
- Modify: `modules/admin/forms/frmRemModuleOptions.php` (line 216)
- Modify: `modules/admin/forms/frmDbDump.php` (line 50)
- Modify: `modules/generator/forms/frmGenerate.php` (line 205)

**Step 1: Update frmConf.php**

At line 23, change `module.conf` to `module.php`:
```php
$this->conf = $MIOLO->getConf('home.modules').'/'.$confModule.'/etc/module.php';
```

At line 28, change `miolo.conf` to `miolo.php`:
```php
$this->conf = $MIOLO->getConf('home.etc').'/miolo.php';
```

At line 24 and 30, update backup file names:
```php
$bkpFileName = 'module.php';
// and
$bkpFileName = 'miolo.php';
```

At line 136, change `generateConfigXML` to `generateConfigPHP`:
```php
$php = $MIOLO->conf->generateConfigPHP($newConfArray);
```

At line 138, update variable name:
```php
echo file_put_contents($this->conf, $php);
```

Replace the `getConfArray()` method (lines 157-337) to load from PHP instead of DOM XML:
```php
public function getConfArray($conf)
{
    $data = require $conf;
    $confArray = [];

    if (!is_array($data)) {
        return $confArray;
    }

    $this->flattenForAdmin($data, '', $confArray);
    return $confArray;
}

private function flattenForAdmin(array $array, string $prefix, array &$result): void
{
    foreach ($array as $key => $value) {
        $flatKey = ($prefix !== '') ? $prefix . '.' . $key : $key;
        if (is_array($value) && !array_is_list($value)) {
            $this->flattenForAdmin($value, $flatKey, $result);
        } else {
            $result[$flatKey] = $value;
        }
    }
}
```

**Step 2: Update frmSetupModuleBD.php**

At lines 40-42, replace DOM XML loading with PHP require:
```php
$moduleConf = require $MIOLO->getConf('home.modules') . '/' . $modName . '/etc/module.php';
$dbConf = $moduleConf['db'][$modName] ?? [];
$modInfo[0] = $dbConf['system'] ?? '';
$modInfo[1] = $dbConf['host'] ?? '';
$modInfo[2] = $dbConf['port'] ?? '';
$modInfo[3] = $dbConf['name'] ?? '';
$modInfo[4] = $dbConf['user'] ?? '';
$modInfo[5] = $dbConf['password'] ?? '';
```

At line 93, replace `createXML` call with PHP config generation:
```php
$phpConfig = $this->generatePHP();
file_put_contents(
    $MIOLO->getConf('home.modules') . '/' . $modName . '/etc/module.php',
    $phpConfig
);
```

Replace `generateXML()` method (lines 168-203) with `generatePHP()`:
```php
public function generatePHP()
{
    global $modName;
    $MIOLO = MIOLO::getInstance();

    $port = ($this->getFieldValue('bdPort') !== 'default') ? $this->getFieldValue('bdPort') : null;

    $config = [
        'db' => [
            $modName => array_filter([
                'system'   => $this->getFieldValue('bdSystem'),
                'host'     => $this->getFieldValue('bdHost'),
                'port'     => $port,
                'name'     => $this->getFieldValue('bdName'),
                'user'     => $this->getFieldValue('bdUser'),
                'password' => $this->getFieldValue('bdPassword'),
            ], fn($v) => $v !== null),
        ],
        'theme' => [
            'module' => $MIOLO->getConf('theme.module'),
            'main'   => $MIOLO->getConf('theme.main'),
            'lookup' => $MIOLO->getConf('theme.lookup'),
            'title'  => $modName,
        ],
    ];

    return "<?php\n\nreturn " . var_export($config, true) . ";\n";
}
```

**Step 3: Update frmRemModuleOptions.php line 216**

Change:
```php
$dom->load($this->MIOLO->getConf('home.modules') . '/' . $moduleDeleteName . '/etc/module.conf');
```
to read the PHP config instead:
```php
$moduleConf = require $this->MIOLO->getConf('home.modules') . '/' . $moduleDeleteName . '/etc/module.php';
```

(Also update surrounding code that uses `$dom` to use the `$moduleConf` array instead — check context.)

**Step 4: Update frmDbDump.php line 50**

Change:
```php
$conf = $this->manager->getConf('home.etc') . '/miolo.conf';
```
to:
```php
$conf = $this->manager->getConf('home.etc') . '/miolo.php';
```

**Step 5: Update frmGenerate.php line 205**

Change:
```php
$confFile = $MIOLO->getConf('home.miolo') . "/etc/miolo.conf";
```
to:
```php
$confFile = $MIOLO->getConf('home.miolo') . "/etc/miolo.php";
```

**Step 6: Commit**

```bash
git add modules/admin/forms/frmConf.php modules/admin/forms/frmSetupModuleBD.php \
       modules/admin/forms/frmRemModuleOptions.php modules/admin/forms/frmDbDump.php \
       modules/generator/forms/frmGenerate.php
git commit -m "refactor: update admin forms to use PHP config instead of XML"
```

---

### Task 8: Update bin/ Scripts

**Files:**
- Modify: `bin/miolo.php`
- Modify: `bin/mioloAdmin.php`
- Modify: `bin/updater.php`

**Step 1: Update bin/miolo.php line 34**

Change:
```php
if ( !file_exists('../etc/miolo.conf') )
```
to:
```php
if ( !file_exists('../etc/miolo.php') )
```

**Step 2: Update bin/mioloAdmin.php**

At lines 57-58, change:
```php
$confSample = '../etc/miolo.php.dist';
$confFile = '../etc/miolo.php';
```

At line 79:
```php
$confFile = $this->MIOLO->getConf('home.miolo') . "/etc/miolo.php";
```

At line 83:
```php
$confFile = $this->MIOLO->getConf('home.modules') . "/$module/etc/module.php";
```

At line 134:
```php
$confFile = $this->MIOLO->getConf('home.miolo') . "/etc/miolo.php";
```

At line 138:
```php
$confFile = $this->MIOLO->getConf('home.modules') . "/$module/etc/module.php";
```

The `configure()` method (lines 54-66) needs updating — it copies the dist file and does string replacement. Change to work with PHP:
```php
public static function configure()
{
    $mioloRoot = realpath('..');
    $confSample = '../etc/miolo.php.dist';
    $confFile = '../etc/miolo.php';

    $content = file_get_contents($confSample);
    $content = str_replace('/var/www/miolo', $mioloRoot, $content);

    file_put_contents($confFile, $content);
}
```

The `setConfig()` method (lines 75-99) needs rewriting — it currently uses simplexml to modify XML. Replace with PHP array manipulation:
```php
public function setConfig($config, $value, $module=NULL)
{
    if (!$module) {
        $confFile = $this->MIOLO->getConf('home.miolo') . "/etc/miolo.php";
    } else {
        $confFile = $this->MIOLO->getConf('home.modules') . "/$module/etc/module.php";
    }

    $data = file_exists($confFile) ? require $confFile : [];
    $configParts = explode('.', $config);
    $ref = &$data;
    foreach ($configParts as $part) {
        if (!isset($ref[$part])) {
            $ref[$part] = [];
        }
        $ref = &$ref[$part];
    }
    $ref = $value;

    $this->writeContentToFile(
        "<?php\n\nreturn " . var_export($data, true) . ";\n",
        $confFile
    );
}
```

The `removeConfig()` method (lines 130-155) — same pattern:
```php
public function removeConfig($config, $module=NULL)
{
    if (!$module) {
        $confFile = $this->MIOLO->getConf('home.miolo') . "/etc/miolo.php";
    } else {
        $confFile = $this->MIOLO->getConf('home.modules') . "/$module/etc/module.php";
    }

    $data = file_exists($confFile) ? require $confFile : [];
    $configParts = explode('.', $config);
    $ref = &$data;
    for ($i = 0; $i < count($configParts) - 1; $i++) {
        if (!isset($ref[$configParts[$i]])) break;
        $ref = &$ref[$configParts[$i]];
    }
    unset($ref[$configParts[count($configParts) - 1]]);

    $this->writeContentToFile(
        "<?php\n\nreturn " . var_export($data, true) . ";\n",
        $confFile
    );
}
```

At line 215-216:
```php
$confSample = "$svnPath/etc/miolo.php.dist";
$confFile = "$mioloPath/etc/miolo.php";
```

**Step 3: Update bin/updater.php**

At lines 18-19, change backup references:
```php
$confBackup = "$BIN_PATH/updater/backup/miolo.php";
$cmd = "cp $MIOLO_PATH/etc/miolo.php $confBackup";
```

At line 40, change from simplexml to require:
```php
$tempConf = require $MIOLO_PATH . '/etc/miolo.php';
```

At line 46, update write path:
```php
$handler = fopen("$MIOLO_PATH/etc/miolo.php", 'w');
```

**Step 4: Commit**

```bash
git add bin/miolo.php bin/mioloAdmin.php bin/updater.php
git commit -m "refactor: update bin/ scripts to use PHP config files"
```

---

### Task 9: Update html/ Entry Points and Remaining References

**Files:**
- Modify: `html/popup.php` (line 13)
- Modify: `html/report.php` (line 2)
- Modify: `html/image.php` (line 4)
- Modify: `html/lookup.php` (line 9)
- Modify: `classes/utils/mIOLOUpdater.php` (lines 581-591)
- Modify: `classes/mIOLOConsole.php` (if needed)

**Step 1: Update html/ includes**

In each file, change `include_once '../etc/miolo/miolo.conf'` to `include_once '../etc/miolo/miolo.php'`:

`html/popup.php` line 13:
```php
include_once '../etc/miolo/miolo.php';
```

`html/report.php` line 2:
```php
include_once '../etc/miolo/miolo.php';
```

`html/image.php` line 4:
```php
include_once '../etc/miolo/miolo.php';
```

`html/lookup.php` line 9:
```php
include_once '../etc/miolo/miolo.php';
```

**Step 2: Update mIOLOUpdater.php**

At lines 583 and 586:
```php
$confBackup = "$this->backupDir/miolo.php";
// ...
$cmd = "cp $confBackup $MIOLO_PATH/etc/miolo.php";
```

**Step 3: Update mioloAdmin.php template reference**

At line 586, the `createModuleConf` method generates a `module.conf` — update to generate `module.php`:
```php
$template = $this->readTemplateContent('module.php');
// ...
$filePath = "$moduleDir/etc/module.php";
```

**Step 4: Commit**

```bash
git add html/popup.php html/report.php html/image.php html/lookup.php \
       classes/utils/mIOLOUpdater.php
git commit -m "refactor: update html/ entry points and updater to use PHP config"
```

---

### Task 10: Update Design Doc and Final Cleanup

**Step 1: Update design doc**

Update `docs/plans/2026-03-03-config-xml-to-php-design.md` to note that MSimpleXml is **kept** (used by mAutoLoad, mKrono loading code removed, mCompatibility, mUI, business.php).

**Step 2: Final grep for any remaining `.conf` references**

Run: `grep -rn '\.conf' classes/ modules/ html/ bin/ etc/ --include='*.php' | grep -v node_modules`

Fix any remaining references.

**Step 3: Final commit**

```bash
git add -A
git commit -m "refactor: complete XML-to-PHP config migration cleanup"
```
