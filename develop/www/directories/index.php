<?php
	$Title = 'FreshPorts - Displaying Directory Contents';
?>
<html>

<head>
<title><?php echo $Title; ?></title>
</head>

<body>

<h1><?php echo $Title; ?></h1>

<h2>Goals</h2>

<p>
The purpose of this project is to allow users to view the commits of previously
unavailable items.  Specifically, it will allow users to view the commits
for /usr/ports/Mk and locations.  FreshPorts contains this information,
but there is no method for presenting this information to users.

A typical /usr/ports directory on a FreeBSD system contains the following:

<blockquote><pre class="code">
<b>INDEX</b>           biology         ftp             misc            textproc
<b>INDEX-5</b>         cad             games           multimedia      ukrainian
<b>INDEX.db</b>        chinese         german          net             vietnamese
<b>LEGAL</b>           comms           graphics        news            www
<b>MOVED</b>           converters      hebrew          palm            x11
<b>Makefile</b>        databases       hungarian       picobsd         x11-clocks
<b>Mk</b>              deskutils       irc             polish          x11-fm
<b>README</b>          devel           japanese        portuguese      x11-fonts
<b>Templates</b>       distfiles       java            print           x11-servers
<b>Tools</b>           dns             korean          russian         x11-toolkits
archivers       editors         lang            science         x11-wm
astro           emulators       mail            security
audio           finance         math            shells
benchmarks      french          mbone           sysutils
</code></blockquote>

<p>
FreshPorts now displays the 
<a href="http://www.FreshPorts.org/categories.php">categories</a>, which make
the vast majority of the directory.  However, there is no way for users to
see the commits to the items shown in <b>bold</b> (e.g. INDEX, Mk, or Tools).
This project will change that.

<h2>Changes to FreshPorts layout</h2>

<p>
The home page of FreshPorts displays the last 100 or so commits.
It is initially proposed that this page be moved to another page, commits.php.
The home page will then be replaced with something which resembles a directory
listing of /usr/ports.

<h2>How FreshPorts uses 404 errors</h2>

<p>
If you look at <a href="http://www.FreshPorts.org/security/logcheck/">http://www.FreshPorts.org/security/logcheck/</a>,
you will see details of the port known as <code class="code">security/logcheck</code>.
This port can be found at /usr/ports/security/logcheck/.  However, there is
no physical directory security/logcheck located within the FreshPorts
website.  FreshPorts uses the 404 error to process the URL and determine
what should be displayed.

<p>
The existing processing can be improved.  It was written several years ago and
has not been publicly reviewed.  The current processing is something along
these lines:
</p>

<ul>
<li>split the URL, using / as a divider
<li>look at the first element, if it's a category, get the category details,
    if not, this is an error.
<li>look at the second element, if it's a port, get the port details,
    if not, this is an error.
<li>look at the third element, if it's one of the special values (e.g files.php)
    then display process the file changes associated with a given commit
</ul>

<p>
I think a better solution may be the following:
</p>

<ul>
<li>Use the URL to select an element from the FreshPorts database.
<li>If no elements are found, remove everything after the trailing slash
    and see if you can find that element in the database.
<li>repeat the above step until you find something, or run out of URL.
<li>If you found something, open it, and pass the full URL to it.
</ul>

<p>
Two PHP functions which might be useful here are <a href="http://www.php.net/basename">basename</a>
and <a href="http://www.php.net/dirname">dirname</a>.

<h2>Some definitions</h2>

<p>
Here are some definitions which are used throughout FreshPorts.
</p>

<ul>
<li><b>element</b> - Refers to an object which can be found in a directory.
    It will be either a directory or a file.  
</ul>

<h2>Database interfaces</h2>

<p>
The following functions will allow you to determine what type of object you 
have, with respect to the URL.
</p>

<ul>
<li>elementGet(PathName)
<p>
<ul>
<li>PathName - text, path to an element within the /usr/ports tree (e.g.
    '/security/logcheck'). The leading / is optional.
<li>returns a row which represents the element:
<ul>
<li>id - integer
<li>name - text
<li>type - text ('D'irectory or 'F'ile)
<li>status - text ('A'ctive or 'D'eleted)
<li>iscategory - boolean ('t'rue or 'f'alse)
<li>isport - boolean ('t'rue or 'f'alse)
</ul>
<li>if no row is returned, there is no such element
<li>if type is 'F', then both iscategory and isport will be 'f'
</ul>
<p>
This function can be used like this:

<blockquote><pre>
# select * from elementGet('ports/security/logcheck');
  id   |   name   | type | status | iscategory | isport
-------+----------+------+--------+------------+--------
 37342 | logcheck | D    | A      | f          | t
(1 row)
</pre></blockquote>

In this case, the element is a directory, it is active (i.e. not deleted, 
it is not a category, but it is a port.  Similar examples follow:

<blockquote><pre>
# select * from elementGet('ports/security');
 id |   name   | type | status | iscategory | isport
----+----------+------+--------+------------+--------
 34 | security | D    | A      | t          | f

# select * from elementGet('ports');
 id | name  | type | status | iscategory | isport
----+-------+------+--------+------------+--------
  1 | ports | D    | A      | f          | f
</pre></blockquote>

</ul>

<h2>Sample database</h2>

<p>
A sample PostgreSQL database containing the above functions, sample tables 
and data can be found <a href="database.sql">here</a>. [SORRY, this isn't available
yet]

<p>
The database contains the following entries from /usr/ports.  If the entry
is a directory, all the entries in that directory are also included. Note
that only two categories are supplied.

<blockquote><pre>
INDEX
INDEX-5
INDEX.db
LEGAL
MOVED
Makefile
Mk
README
Templates
Tools
archivers
x11-wm
</blockquote></pre>

<h2>Language</h2>

<p>
This solution must be written in PHP 4.  That's what the existing FreshPorts
uses.  The use of classes is strongly encouraged.

<p>
One design consideration which might be useful is the creation of a webpage
object.  It is passed the URL and it decides whether to display a category
page, a port page, a commit page, or just list the files under a directory.

<h2>Suggested course of action</h2>

Create a simple proof-of-concept which takes the incoming URL, and decides what
type of page must be displayed.  This would be one of:

<ul>
<li>A port page (e.g. /security/logcheck/)
<li>A category page (e.g. /security/)
<li>Commits for a file (e.g. /security/logcheck/Makefile, /INDEX)
<li>Commits for a directory (e.g. /, /Mk, /Tools)
<li>something else (none of the above
</ul>

</body>
</html>