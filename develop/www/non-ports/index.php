<?php
	$Title = 'FreshPorts - Displaying non-port commits';
?>
<html>

<head>
<title><?php echo $Title; ?></title>
</head>

<body>

<h1><?php echo $Title; ?></h1>

<p align="right"><small>22 September 2003</small></p>

<h2>Goals</h2>

<p>
The purpose of this project is to allow users to view the commits of previously
unavailable items.  Specifically, it will allow users to view the commits
for /usr/ports/Mk and locations.  FreshPorts contains this information,
but there is no method for presenting this information to users.

<p>
The methods used to display <a href="/directories/">directories</a> will be helpful
in understanding the issues discussed here

<p>
At present, FreshPorts displays information about commits to the FreeBSD ports
tree.  These commits are grouped and displayed on a port-by-port basis.  This is useful
for seeing what changes have occurred to the port over time.  This information
is not available from any other source in such a concise format.

<p>
However, changes occur in the ports tree which are not directly related to any particular
port.  For example, the Mk and Tools directories contain files which directly affect how
ports are built.  Including the commits to these items will increase the usefulness of FreshPorts
and will expand the scope of coverage to the entire ports tree, not just the ports.

<h2>Current Status</h2>

<p>
<ul>
<li>The database handles and includes non-port port tree commits
<li>date.php needs to be modified
</ul>

<h2>Changes to FreshPorts</h2>

There are three main areas which require changing in order to accomplish the goals of this subproject:
<ul>
<li>latest_commits_ports table
<li>index.php
<li>date.php
</ul>

<h3>latest_commits_ports table</h3>

<p>
FreshPorts stores all commits in the commit_log table.  To simplify and speed processing, a secondary
table records recent commits against the ports tree.  This table is kept small by design.  Only the
last 100 or so commits are recorded and the table is trimmed on a regular basis.

<p>
The updating of this table occurs during the processing of incoming emails from the cvs-all mailing list.
This process needs to be changed so that any commit to the ports tree results in an entry added
to the latest_commits_ports table.

<h3>index.php</h3>
<p>
index.php needs to be modified to handle non-port commits.  The SQL has already been modified to cater
for non-ports.  New classes will be required to display non-port data.  A first step has been to 
create a function which returns the latest commits: 

<blockquote><pre class="code">
FUNCTION LastestCommits(int, int) RETURNS SETOF commit_record 
</code></blockquote>

<p>
This function now (24 Sep 2003) supports non-port tree commits.

<h3>date.php</h3>
<p>
See index.php.

<h2>New classes</h2>
We will need new classes which will display the correct information for a commit.  This will be used for
index.php, date.php, and possibly other locations.

<p>
Done.  See classes/commit_record.php

<blockquote><pre class="code">
</code></blockquote>

<h2>Sample database</h2>

<p>
Here is the record type which is returned to index.php:

<blockquote><pre class="code">
CREATE TYPE commit_record AS (
	commit_log_id        integer,
	commit_date_raw      timestamp with time zone,
	message_subject      text,
	message_id           text,
	committer            text,
	commit_description   text,
	commit_date          text,
	commit_time          text,
	encoding_losses      boolean,
	port_id              integer,
	needs_refresh        smallint,
	forbidden            text,
	broken               text,
	element_id           integer,
	version              text,
	revision             text,
	date_added           integer,
	short_description    text,
	category_id          integer,
	port                 text,
	status               character(1),
	category             text,
	security_notice_id   integer,
	watch                bigint,
	element_pathname     text
);
</code></blockquote>

<p>
Done.  See database-schema/datatype.txt

<h2>Language</h2>

<p>
This solution must be written in PHP 4.  That's what the existing FreshPorts
uses.  The use of classes is strongly encouraged.

<p>
One design consideration which might be useful is the creation of a webpage
object.  It is passed the URL and it decides whether to display a category
page, a port page, a commit page, or just list the files under a directory.

<h2>Suggested course of action</h2>

Create a simple proof-of-concept which takes the data from the database,
and displays the latest commits (i.e. index.php)

</body>
</html>