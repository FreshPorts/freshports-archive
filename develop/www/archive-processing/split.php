<?php
	$Title = 'FreshPorts - Processing mail archives : split';
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
<a href="split-by-messageid">split-by-messageid</a> was provided by Dirk Meyer

<p>Thank you.

<p>
After downloading this file, be sure to follow the instructions at the top of it.

<h2>Comments</h2>

<p>
Dirk's code splits the archive into N message, then runs a shell script
over each one.  He uses a call to psql to run the query.

<h2>Running it</h2>
<p>
Here's how it is run:

<blockquote><code class="code">
$ ls -l<br>
total 4<br>
-rwxr--r--  1 dan  dan  678 Aug 27 11:41 report-missing-messageid.sh<br>
-rwxr-xr-x  1 dan  dan  658 Aug 27 11:39 split-by-messageid.awk<br>
$ mkdir test
$ ./split-by-messageid.awk < ../archive-sample<br>
200308262024.h7QKOMM2008648@repoman.freebsd.org<br>
200308262025.h7QKPwu3011893@repoman.freebsd.org<br>
200308262026.h7QKQMFw011958@repoman.freebsd.org<br>
200308262033.h7QKXnss012244@repoman.freebsd.org<br>
200308262040.h7QKec5s012576@repoman.freebsd.org<br>
200308262055.h7QKtg57013416@repoman.freebsd.org<br>
200308262101.h7QL10XU013707@repoman.freebsd.org<br>
200308262114.h7QLEGRj015063@repoman.freebsd.org<br>
<br>
real&nbsp; &nbsp; 0m0.019s<br>
user&nbsp; &nbsp; 0m0.018s<br>
sys&nbsp; &nbsp;&nbsp; 0m0.000s<br>
$ sh report-missing-messageid.sh<br>
200308262024.h7QKOMM2008648@repoman.freebsd.org<br>
200308262025.h7QKPwu3011893@repoman.freebsd.org<br>
200308262033.h7QKXnss012244@repoman.freebsd.org<br>
200308262040.h7QKec5s012576@repoman.freebsd.org<br>
200308262101.h7QL10XU013707@repoman.freebsd.org<br>
<br>
real&nbsp; &nbsp; 0m0.374s<br>
user&nbsp; &nbsp; 0m0.057s<br>
sys&nbsp; &nbsp;&nbsp; 0m0.100s<br>
$
</code></blockquote>

</body>

</html>