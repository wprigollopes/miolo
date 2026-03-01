# Internet in 1999: 56 kbps and Four-Hour Downloads

**A browser update took four hours. Downloading a Linux distribution
took days. And your phone didn't work while you were online.**

Modern developers take connectivity for granted. Gigabit fiber, 5G
mobile, CDNs that serve assets from edge nodes milliseconds away.
The idea that bandwidth could be a constraint on software development
feels absurd.

In 1999, especially in southern Brazil, connectivity was the single
biggest constraint on everything.

---

## The Numbers

| Metric | 1999 (Brazil) | 2025 |
|---|---|---|
| Connection speed | 28.8-56 kbps dial-up | 100-1000 Mbps fiber |
| Speed ratio | 1x | ~20,000x faster |
| Phone line | Busy while online | Separate infrastructure |
| Cost model | Per-minute charges | Flat rate |
| Availability | Major cities, universities | Nearly universal |
| Latency | 200-500ms | 5-20ms |

### What 56 kbps Means

At 56 kbps (the theoretical maximum of a V.90 modem, rarely achieved
in practice — 33.6 kbps was more common):

| Download | Size | Time at 56 kbps | Time at 33.6 kbps |
|---|---|---|---|
| A web page (HTML + images) | 50 KB | 7 seconds | 12 seconds |
| Netscape Navigator 4 installer | 15 MB | 36 minutes | 1 hour |
| Internet Explorer 5 update | 75 MB | 3 hours | 5 hours |
| Red Hat Linux 6.0 (single CD) | 650 MB | 26 hours | 43 hours |
| PostgreSQL 6.5 source tarball | 8 MB | 19 minutes | 32 minutes |
| PHP 3.0 source tarball | 800 KB | 2 minutes | 3.5 minutes |

A browser update — a routine operation today (automatic, invisible,
takes seconds) — was a **four-hour commitment** that tied up your phone
line.

### Dial-Up Mechanics

1. Your computer's modem dialed a phone number (ISP's access point)
2. You heard the characteristic modem handshake sound
3. Your phone line was **occupied** — no one could call you, and you
   couldn't make calls
4. You paid **per minute** — both for the ISP and for the phone call
5. If someone picked up another phone in the house, your connection
   dropped
6. Connection stability was poor — disconnections every 30-60 minutes
   were common

### Impact on Software Development

#### Downloading Dependencies

Today: `composer require monolog/monolog` — 3 seconds.

In 1999: Find the library on a mailing list or personal website. Start
the download. Hope the connection doesn't drop. If it drops, start over
(there was no resume in most HTTP servers). A 5 MB library could take
20 minutes.

This is why MIOLO vendored libraries directly — downloading them once
and committing them to the repository was faster than re-downloading
them every time you set up a new development environment.

#### Reading Documentation

No Google (launched September 1998, not yet dominant). No Stack Overflow
(2008). The PHP Manual was a downloadable HTML file. O'Reilly books were
the primary reference. If you had a question about a PHP function, you
either had the manual downloaded locally or you waited for a web page
to load at 4 KB/s.

Mailing lists were the primary support channel. You sent a question,
waited hours or days for a response, and hoped someone who knew the
answer was subscribed.

#### Collaboration

- **No GitHub** (2008), **no Bitbucket** (2008)
- **No Git** (2005) — CVS existed but was not widely used in small teams
- **No video calls** — bandwidth was far too low
- **Email** was the primary communication tool
- **FTP** was the primary file transfer method

MIOLO's development team at UNIVATES/SOLIS communicated via email and
face-to-face meetings. Collaborators at other universities (UFJF, UFV,
UNILASALLE) sent patches via email or shared code on physical media
(floppy disks, CDs) at conferences.

#### The Conference Network

Brazil's free software community was deeply connected through physical
events:

- **FISL** (Forum Internacional Software Livre) — the main gathering,
  held annually in Porto Alegre
- **Latinoware** — Latin American conference on free software
- **University workshops** — UNIVATES, UFJF, and others hosted training
  sessions

These events were where code was shared, partnerships were formed, and
libraries were exchanged. Rasmus Lerdorf's visit to UNIVATES in 2001 —
which led to the creation of MIOLO — happened because of these networks.

---

## Impact on MIOLO's Architecture

The connectivity constraints directly influenced MIOLO's design:

### 1. Server-Side Rendering

JavaScript was avoided not just because of browser incompatibility,
but because downloading JavaScript files over dial-up added seconds to
every page load. Generating HTML on the server and sending it as a single
response was the most efficient use of bandwidth.

### 2. Minimal Page Weight

MIOLO's theme system generated compact HTML. Tables for layout (instead
of CSS, which was unreliable) were actually bandwidth-efficient because
browsers could render them progressively. Images were heavily optimized
or avoided entirely.

### 3. Vendored Dependencies

Instead of downloading libraries from the internet, MIOLO included
everything in its repository. A developer setting up the project needed
only the MIOLO source tree — no internet-dependent package installation.

### 4. Offline-First Development

Developers had the PHP Manual and library source code on their local
machines. Development could happen entirely offline. The only time you
needed connectivity was to deploy to the server or communicate with
the team.

### 5. Postal Code as Innovation

The notion of "downloading" a full software distribution was sometimes
replaced by **receiving CDs by mail**. Linux distributions were commonly
ordered as physical CDs from specialized retailers. Conectiva Linux
(the Brazilian distribution) sold boxed sets in stores.

---

## The Broadband Transition

Brazil's broadband adoption followed a typical developing-country curve:

| Year | Connection | Impact |
|---|---|---|
| 1997-2002 | Dial-up 28.8-56 kbps | Phone line busy, per-minute |
| 2003-2006 | ADSL 256 kbps-1 Mbps | First "always-on" connections |
| 2007-2010 | ADSL 2-10 Mbps | Practical for daily work |
| 2011-2015 | Cable/fiber 10-50 Mbps | Modern web becomes usable |
| 2016+ | Fiber 50-500 Mbps | Cloud-first development |

MIOLO's architecture evolved with this transition. The Dojo Toolkit
integration (2008) and AJAX support coincided with broadband becoming
common enough that downloading JavaScript libraries and making
asynchronous requests was practical.

---

## What This Tells Us

The internet connectivity constraints of 1999 weren't just an
inconvenience — they were an architectural force. Every design decision
in MIOLO — server-side rendering, vendored dependencies, minimal page
weight, offline-capable development — was shaped by the reality that
bandwidth was precious and unreliable.

When modern developers debate "SSR vs SPA" (server-side rendering vs
single-page applications), they're re-litigating a decision that was
made for them in 1999 by physics: the server rendered everything because
there was no bandwidth for anything else. MIOLO's architecture is proof
that server-side rendering, done well, can build complex, feature-rich
applications — it just does it differently than the JavaScript-heavy
approach we've grown accustomed to.
