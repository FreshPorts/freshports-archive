<HTML>

<HEAD>
<TITLE>FreshPorts -- project website</TITLE>
</HEAD>

<BODY>


<TABLE WIDTH="98%" CELLPADDING="0" CELLSPACING="0" BORDER="0">
<TR>
   <TD><A HREF="/"><IMG SRC="/images/freshports.jpg" ALT="FreshPorts.org - the place for ports" WIDTH="512" HEIGHT="110" BORDER="0"></A>
  </TD>
   <TD ALIGN="right" CLASS="sans" VALIGN="bottom"><small><? echo date("D, j M Y g:i A T") ?></small>
  </TD>
</TR>
</TABLE>

<H1>FreshPorts Development Site - Developers</H1>

<P>
This page contains a brief introduction for those who are joining the projects.
<P>

<H2>Logins</H2>

<P>
All developers should on the <I>develop</I> mailing list.  You should <A HREF="/mailinglists.html">join
that list first</A> and then post a message introducing yourself.  There is also a cvs-all mailing list
which contains the cvs logs.  If you're working on the project, you may wish to see what others are doing.
</P>

<P>
You also need to send your 
public key[s] (found in ~/.ssh/) to <A HREF="mailto:hostmaster@freshports.org">hostmaster@freshports.org</A> who
will arrange your login. We prefer SSH2 keys.  For details on how to create them, see 
<A HREF="http://www.freebsddiary.org/ssh-exploit.php">http://www.freebsddiary.org/ssh-exploit.php</A>.

<P>In your mesage to hostmaster, please specify your preferred login name and the IP address[es] you will
logging in from.
</P>

<H2>What to work on</H2>

<P>
The list of things we are working on is found within <A HREF="/mantis/">Mantis</A> (use the guest/guest) 
login).  Pick something and let us know you're working on it.  Eventually, we will distribute individual 
Mantis logins.
</P>

<H2>CVS</H2>

<P>
We're not provding any remote cvs access yet.  So all work is done on develop.freshports.org.  The 
repository is at <CODE>/usr/repositories/freshports2</CODE>  You'll want both the <CODE>www</CODE> 
and the <CODE>configuration</CODE> collections.
</P>

<H2>Your working website</H2>

<P>
Every developer will be given their own website to work from.  It will be of the form
<CODE>http://user.freshports.org/</CODE>.  Here's an example <CODE>vhosts.conf</CODE> as 
found in the <CODE>configuration</CODE> directory.  Copy <CODE>vhosts.conf.sample</CODE>
to <CODE>vhosts.conf</CODE>.  Subsitute your own user name for <B>user</B>.
</P>

<BLOCKQUOTE><PRE>
&lt;VirtualHost 192.168.0.16&gt;
        ServerAdmin     dan@langille.org
        DocumentRoot    /home/<B>user</B>/code/www
        ServerName      <B>user</B>.freshports.org
        ErrorLog        /usr/websites/logs/<B>user</B>.freshports.org-error.log
        CustomLog       /usr/websites/logs/<B>user</B>.freshports.org-access.log common
&lt;/VirtualHost&gt;
</PRE></BLOCKQUOTE>

<P>
<B>Make sure your IP address is as shown!</B>

</P><P>
When you're ready, notify hostmaster again and your vhost will be included within the apache
configuration.
</P>

<H2>Database access</H2>

<P>
The database login is controlled via <CODE>configuration/database.php</CODE>.  Use
<CODE>configuration/database.php.sample</CODE> as a template but make it look like
this:

<BLOCKQUOTE><CODE>
$db = pg_connect("dbname=FreshPorts2Test user=<B>USERID</B>");
</CODE></BLOCKQUOTE>

Subsitute your
user login the user id but do not use a password.  We're all using the same database for now.
</P>


</BODY>
</HTML>
