<?php
	$Title = 'FreshPorts - Processing mail archives : catchUp.pl';
?>
<html>

<head>
<title><?php echo $Title; ?></title>
</head>

<body>

<h1><?php echo $Title; ?></h1>

<hr>

<h2>Author</h2>

<p>
<a href="catchUp.pl">catchUp.pl</a> was provided by Stanislav Grozev.

<p>Thank you.

<h2>Comments</h2>
This is similar to what I might have come up with.  

<h2>Running it</h2>
<p>
Here's how it is run:

<blockquote><code class="code">
$ ls -l<br>
total 2<br>
-rwxr--r--  1 dan  dan  1171 Aug 27 11:30 catchUp.pl<br>
 $ formail -s sh -c 'perl catchUp.pl' < ../archive-sample<br>
$ ls -l<br>
total 36<br>
-rwxr--r--  1 dan  dan&nbsp;&nbsp; 1171 Aug 27 11:30 catchUp.pl<br>
-rw-r--r--  1 dan  dan&nbsp;&nbsp; 2808 Aug 27 12:14 message.000<br>
-rw-r--r--  1 dan  dan&nbsp;  17531 Aug 27 12:14 message.001<br>
-rw-r--r--  1 dan  dan&nbsp;&nbsp; 2289 Aug 27 12:14 message.003<br>
-rw-r--r--  1 dan  dan&nbsp;&nbsp; 2700 Aug 27 12:14 message.004<br>
-rw-r--r--  1 dan  dan&nbsp;&nbsp; 2203 Aug 27 12:14 message.006<br>
$ time formail -s sh -c 'perl catchUp.pl' < ../archive-sample<br>
<br>
real&nbsp;&nbsp; 0m1.134s<br>
user&nbsp;&nbsp; 0m0.775s<br>
sys&nbsp;&nbsp;&nbsp; 0m0.161s<br>
$<br>
</code></blockquote>

</body>

</html>