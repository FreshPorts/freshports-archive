<?php
	$Title = 'FreshPorts - Processing mail archives';
?>
<html>

<head>
<title><?php echo $Title; ?></title>
</head>

<body>

<h1><?php echo $Title; ?></h1>

<h2>Project Goal</h2>

<p>FreshPorts is designed to processing incoming mail message from the FreeBSD 
cvs-all mailing list. Using procmail and various scripts, this data is loaded 
into a PostgreSQL database.&nbsp; This works well but facilities to handle 
missed mail messages are lacking.&nbsp; The goal of this project is to process 
mail archives and load any missing messages into FreshPorts.</p>


<h2>Proof of concept</h2>
<p>Here is a simple proof of concept example which shows you how this can be 
done.</p>
<p>
<blockquote><code class="code">formail -s sh -c 'cat &gt; msg.$FILENO' &lt; archive </code></blockquote></p>


formail(1) is a tool provided with procmail(1) that can be used to split up a 
mail archive into individual messages.&nbsp; In the above example, each message 
is piped to cat(1) and then redirect to a file.&nbsp; formail(1) sets the 
environment variable FILENO.&nbsp; By default, the first value is 000.&nbsp; 
Thus, the above example splits the mail archive into files msg.000, msg.001, 
msg.002, etc.</p>


<p></p>


<h2>Expanding the example</h2>
<p>From the above example, it would be easy to feed the individual message to a 
script (Perl, Python, etc).&nbsp; That script would examine the message and 
locate the message id.&nbsp; Once found, the FreshPorts database can be queried 
to determine whether or not that message is in the database.&nbsp; If it is, no 
further processing is required.&nbsp; If it is not, the entire message would be 
dumped to a file.&nbsp; That file would then be moved to a message processing 
directory where it would be picked up by existing tools and loaded into the 
FreshPorts database.</p>

<h2>Data to get you started</h2>
<p>Here are a few things to get you start:</p>
<ul>
  <li>A short sample <a href="archive-sample">archive</a>.&nbsp; It contains 8 
  cvs-all messages.</li>
  <li>A PostgreSQL <a href="database.sql">database</a> you can use for testing.</li>
</ul>

<p>You can determine whether or not a message id is in the 
database using this function:</p>
<blockquote><code class="code">
archive-example=# select MessageIDFound('200308262114.h7QLEGRj015063@repoman.freebsd.org');<br>
messageidfound<br>
----------------<br>
3<br>
(1 row)<br>
</code></blockquote></p>
<p>Here is a message which is not loaded:</p>

<blockquote><code class="code">
archive-example=# select MessageIDFound('blah');<br>
messageidfound<br>
----------------<br>
<br>
(1 row)
</code></blockquote>
<p>Note that the above returns a NULL value.</p>


<h2>What language?</h2>
<p>Use whatever language you want to get the job done.&nbsp; If it works, it 
works.</p>

<h2>Is that all?</h2>

<p>
Basically, yes.  Once we get this program working, we can worry about things such as fetching
the archive every 4 hours, but only processing the messages which have been added since our
last fetch.  But that can be done as a different project.

<p>
Oh, we and might want to send an email to the project admin whenever a missing message is found.

<h2>Any questions?</h2>

<p>
If you have any questions, don't hesitate to ask me via IRC (dvl or dvl-- on efnet, undernet, 
freenode, and oftc) or email (dan at langille org).
</body>

</html>