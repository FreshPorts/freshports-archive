<html>

<head>
<title>FreshPorts 2</title>
<link rel="stylesheet" type="text/css" href="/style.css">
</head>

<body>

<a href="http://<? echo $HTTP_HOST?>/">
<img src="http://<? echo $HTTP_HOST?>/images/freshports.jpg"
alt="freshports.org - the place for ports" width="512" height="110" border="0"></a>
<h1>FreshPorts 2</h1>
<p>This is the development box.  This is where some of the new features can be viewed
and a place for us to muck around with.</p>
<p>
Mailing list messages are converted into XML files.  These are then parsed and used
to populate the PostgreSQL database. The DTD is <a href="http://freshports.org/docs/">here</a>.</p>
<p>Have a quick look at the first 199 elements from the <a href="tree.php">source tree</a>. 
In no particular order.</p>
<p>We've also started to convert all of the incoming cvs-all <a href="msgs">messages</a> to XML.
Work on the XML translater is still underway, so the messages there are not compliant with the DTD.</p>
<p>Live <a href="commits.php">Commit logs</a> are now online.<p>
<p>If you are interested in following our exploits, please join the Develop mailing list by sending a 
message to <a href="mailto:majordomo@freshports.org?subject=sub&body=subscribe develop">majordomo@freshports.org</a>
 with "subscribe develop" in the body of the message.</p>
<h1>Things we've accomplished so far</h1>
<ul>

<li><h3>cvs-all mailing list - 13 Jan 2001</h3>
<p>The cvs-all mailing list will keep you up to date will the changes in the cvs-repository.  To subscribe, send a 
message to <a href="mailto:majordomo@freshports.org?subject=sub&body=subscribe cvs-all">majordomo@freshports.org</a> with "subscribe cvs-all" in the 
body of the message.</p>
</li>

<li><h3>cvsweb interface - 9 Jan 2001</h3>
<p>You can browse the cvs repository <a href="/cgi-bin/cvsweb.cgi/">online</a>.</p>
</li>

<li><h3>commit log  - 7 Jan 2001</h3>
<p>The FreeBSD commit log is <a href="commits.php">online</a>.</p>
</li>

<li><h3>OpenBSD - FreshPorts interface - 5 Jan 2001</h3>
<p>Anil Madhavapeddy joins the project to write the interface for the OpenBSD source tree. You can see those messages 
<a href="msgs/OpenBSD/">online</a>.
</p>
</li>

<li><h3>DTD created - 24 Dec 2000</h3>
<p>Adam Herzog creates the first generation DTD.  See it for yourself at <a href="cgi-bin/cvsweb.cgi/scripts/fp-updates.dtd">our repository</a>.
</p>
</li>

<li><h3>Database model - 29 Nov 2000</h3>
<p>With the help of John Fisher, I've been able to come up with a <a href="physical_database.gif">data model</a> which will handle the cvs repository.
</p>
</li>

</ul>

<p>This takes us back to the early days...</p>
</body>
</html>

