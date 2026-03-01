# Debugging in the Early PHP Era

**How do you debug a web application when there are no debuggers?**

Today, PHP developers reach for Xdebug (breakpoints, step-through, stack
inspection), Laravel's `dd()` (dump and die), Symfony's VarDumper (color-coded
output with collapsible objects), or browser DevTools with source maps. In
1999, none of this existed. Debugging a PHP application meant one thing:
**print the variable and stare at the screen**.

---

## The Tools That Didn't Exist

- **Xdebug** — first released in 2002, but remained impractical for most
  setups until IDE integration matured around 2008-2010
- **Browser DevTools** — Chrome DevTools arrived in 2008; before that,
  Firefox's Firebug (2006) was the first real option
- **Laravel `dd()`** — arrived with Laravel 4 in 2013, built on Symfony's
  VarDumper (2014)
- **Error tracking services** (Sentry, Bugsnag) — 2010s
- **Structured logging** (Monolog, PSR-3) — 2011-2012

In the PHP 3/4 era, your debugging toolkit was:

```php
echo $variable;
var_dump($variable);
print_r($variable);
error_log("something happened");
```

And if you were disciplined, you wrote the output to a file in `/tmp`.

---

## How MIOLO Solved It

MIOLO built an entire debugging infrastructure from scratch, years before
the ecosystem provided anything comparable.

### MDump: The Ancestor of `dd()`

**File:** `classes/services/mdump.class.php`

`MDump` is MIOLO's debug dump service. It wraps `var_dump()` with two
critical additions that `var_dump` alone didn't have: **access control**
(only show dumps to developer IPs) and **log routing** (also write to the
log system).

```php
// MDump only shows output to authorized developer IPs
if ($dump) {
    echo "<pre>\n";
    if ($info)
        echo $info . "\n" . str_repeat('-', strlen($info)) . "\n";
    var_dump($var);
    echo "</pre>\n";
}

// Simultaneously captures to the log system
ob_start();
var_dump($var);
$ob = ob_get_contents();
ob_end_clean();
$this->log->logMessage('[VARDUMP]file:$file:$line:$info');
```

The IP-based gating was configured in `miolo.conf`:

```php
$MIOLOCONF['options']['dump']['peer'] = array('127.0.0.1', '192.168.0.40');
```

Only requests from those IPs would see debug output. Everyone else saw
a normal page. This solved a real production problem: you couldn't just
`var_dump()` on a live server because every user would see it.

### Comparison: MDump (2001) vs Laravel's `dd()` (2013)

| Feature | MIOLO MDump (2001) | Laravel `dd()` (2013) |
|---|---|---|
| Core mechanism | `var_dump()` in `<pre>` tags | Symfony VarDumper with HTML formatting |
| Halts execution | No (continues running) | Yes ("dump and die") |
| Access control | IP-based peer list | None (always visible) |
| Output format | Raw `var_dump` text | Color-coded, collapsible, type-aware |
| Log integration | Writes to MLog simultaneously | None (separate concern) |
| File/line tracking | Manual `$file`, `$line` params | Automatic via `debug_backtrace()` |
| Production safety | Built-in IP gating | Must be removed before deploy |

The `dd()` concept — dump a variable in a readable format for the developer
— is the same. MIOLO had to solve the additional problem of "how do I see
this without showing it to users" because there were no staging environments.

### MVarDump: Pretty-Printing Before Pretty-Printers

**File:** `classes/utils/mvardump.class.php`

A standalone utility class that wraps `var_dump` in HTML formatting:

```php
class MVarDump
{
    public function generate()
    {
        echo "<b>Variable Dump:</b><br><br>\n";
        echo "<blockquote>\n<pre>\n";
        var_dump($this->var);
        echo "</pre>\n</blockquote>\n";
    }
}
```

Today this seems trivial, but in 2001 the default `var_dump()` output was
an unformatted stream of text that ran together with the page HTML. Wrapping
it in `<pre>` and `<blockquote>` was a genuine improvement.

### The Global `mdump()` Function

**File:** `classes/support.inc.php`

A global convenience function that routes `print_r` output through the
trace system — the equivalent of a modern helper function:

```php
function mdump($var)
{
    $MIOLO = MIOLO::getInstance();
    $MIOLO->trace(print_r($var, true));
}
```

### The `/tmp/var_dump` Pattern

**File:** `classes/support.inc.php`

When you couldn't see the browser output (background processes, AJAX
requests, cron jobs), you wrote to a file:

```php
function debug($var, $append = true)
{
    $file = '/tmp/var_dump';
    MUtil::MDEBUG($var, $append, $file);

    // Include the caller's file and line number
    $btr = debug_backtrace();
    $info = '>>> Arquivo: ' . $btr[0]['file'] . ' (linha: ' . $btr[0]['line'] . ')';
    MUtil::MDEBUG($info, true, $file);
    MUtil::MDEBUG("\n\n", true, $file);
}
```

Then in another terminal: `tail -f /tmp/var_dump`. This was the closest
thing to a "live log viewer" available at the time.

### Firebug Bridge: Server-to-Browser Console

**File:** `classes/utils/mutil.class.php`

When Firebug appeared in 2006, MIOLO immediately built a bridge that
injected `console.log()` calls from PHP:

```php
public static function clog($args)
{
    $result = MUtil::dataforFirebugConsole(func_get_args());
    foreach ($result as $line => $info) {
        $clogs .= 'console.log(\'' . $info . '\');';
    }
    $MIOLO->page->addJsCode($clogs);
    $MIOLO->trace('[CLOG] ' . var_export(func_get_args(), true));
}
```

This meant a PHP developer could write `MUtil::clog($userData)` and see
the output in the browser's JavaScript console — a technique that wouldn't
become mainstream until PHP Debug Bar (2013) and Clockwork (2014).

### The Flag File Pattern

**File:** `classes/utils/mutil.class.php`

`MUtil::flog()` only writes if `/tmp/flog` exists — a developer creates
the file to "opt in" to logging:

```php
public static function flog()
{
    if (file_exists('/tmp/flog')) {
        $dump = var_export(func_get_arg($i), true) . "\n";
        $f = fopen('/tmp/flog', 'a');
        fwrite($f, $dump);
        fclose($f);
    }
}
```

No file means no logging. `touch /tmp/flog` enables it. `rm /tmp/flog`
disables it. Zero configuration, zero overhead when disabled. This pattern
is still used in production debugging today.

---

## Listening on Specific Ports

**File:** `classes/services/mlog.class.php`

MIOLO's logging system includes a **socket handler** that sends log messages
to a network port:

```php
private function handlerSocket($msg)
{
    if ($this->port) {
        if (!$this->socket) {
            $this->socket = fsockopen($host, $this->port);
        }
        fputs($this->socket, $msg . "\n");
    }
}
```

A developer would run `netcat -l -p 9999` (or a custom listener) on their
machine, configure MIOLO to log to that port, and watch debug messages
stream in real-time — a poor man's log aggregator, years before tools like
Logstash (2010) or Fluentd (2011) existed.

---

## Archaeology: Debug Artifacts in the Code

The codebase preserves traces of debugging sessions from two decades ago.
Commented-out `var_dump` calls appear throughout, left behind by developers
who debugged a problem and moved on:

```php
// In miolo.class.php:
//var_dump($namespace,$class,$extension);
//$this->getObject('trace')->tracestack();
```

```php
// In html/index.php:
//var_dump($_POST);
```

These are fossils — evidence of real debugging sessions, preserved in
version control, telling the story of problems solved long ago.

---

## What This Tells Us

Every debugging technique that modern frameworks provide as a polished
feature — dump-and-inspect, conditional logging, browser console output,
remote log streaming — was invented ad hoc by teams like MIOLO's, out
of necessity. The difference is not in the concept but in the packaging:
MIOLO's `MDump` and Laravel's `dd()` solve the same fundamental problem.
One was built in 2001 with `var_dump` and IP checks; the other was built
in 2013 with Symfony's VarDumper and beautiful HTML output. The debugging
*instinct* is timeless; only the tools evolve.
