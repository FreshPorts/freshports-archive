<?php
	$Title = 'FreshPorts - Processing mail archives : cvs-arch.pl';
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
<a href="cvs-arch.pl">cvs-arch.pl</a> was provided by Peter Pentchev.

<p>Thank you.

<h2>Comments</h2>

<h2>Running it</h2>
<p>
Here's how it is run:

<blockquote><code class="code">
$ ls<br>
cvs-arch.pl<br>
$ time formail -s sh -c 'perl cvs-arch.pl' < ../archive-sample<br>
Saving <200308262024.h7QKOMM2008648@repoman.freebsd.org> as msg.000<br>
Saving <200308262025.h7QKPwu3011893@repoman.freebsd.org> as msg.001<br>
Naaah, <200308262026.h7QKQMFw011958@repoman.freebsd.org> already present as 1<br>
Saving <200308262033.h7QKXnss012244@repoman.freebsd.org> as msg.003<br>
Saving <200308262040.h7QKec5s012576@repoman.freebsd.org> as msg.004<br>
Naaah, <200308262055.h7QKtg57013416@repoman.freebsd.org> already present as 2<br>
Saving <200308262101.h7QL10XU013707@repoman.freebsd.org> as msg.006<br>
Naaah, <200308262114.h7QLEGRj015063@repoman.freebsd.org> already present as 3<br>
<br>
real&nbsp;&nbsp; 0m1.324s<br>
user&nbsp;&nbsp; 0m0.996s<br>
sys&nbsp;&nbsp;&nbsp; 0m0.122s<br>
$ ls -l<br>
total 38<br>
-rwxr--r--  1 dan  dan&nbsp;&nbsp; 3200 Aug 27 12:08 cvs-arch.pl<br>
-rw-r--r--  1 dan  dan&nbsp;&nbsp; 2808 Aug 27 12:55 msg.000<br>
-rw-r--r--  1 dan  dan&nbsp;  17531 Aug 27 12:55 msg.001<br>
-rw-r--r--  1 dan  dan&nbsp;&nbsp; 2289 Aug 27 12:55 msg.003<br>
-rw-r--r--  1 dan  dan&nbsp;&nbsp; 2700 Aug 27 12:55 msg.004<br>
-rw-r--r--  1 dan  dan&nbsp;&nbsp; 2203 Aug 27 12:55 msg.006<br>
$
</code></blockquote>

</body>

</html>