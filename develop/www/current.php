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

<H1>FreshPorts Development Site - Current work</H1>

<P>This page outlines the current work which is now underway.  It will
also contain a record of past work.</P>

<H2>Data Migration</H2>

<P>The main part of the <A HREF="migration.php">migration process</A> seems to 
be completed.  The commit messages have been converted to the new database 
schema.  The remaining items are:

<UL>
<LI>
Migrate the user and watch list data.  This will be immediately before
the new site goes live.  We'll do a test run first though.
</LI>

<LI>
Update the FP2 database with the commits which occur between the time the
commit data was migrated and the website goes live. This should be a few
weeks worth of data.
</LI>

</UL>

<H2>One click watch list add/remove</H2>

<P>
One feature I really want to include in FP2 is an easier way to maintain
your watch list.  With this new feature, your watched ports will be indicated 
by a graphic (probably an open eye).  If you click on this open eye, you will
be asked to confirm the remove from your watch list.  Similarly, ports not on
your watch list will be indicated by a closed eye.  Clicking on that eye will 
add it to your watch list.
</P>

<H3>Strategy</H3>

<P>
The strategy behind this feature involves querying the user's watch list each
time a port is displayed on a webpage.  This query will [most likely] be an
outer join.  If the port is on the watch list, the column will be non-null.
If the port is not on the watch list, the column will be null.  If is then a 
simple test to determine watch list membership.
</P>

<P>
The above is pretty simple and fast if it involves only one port.  If a number
of ports are involved, it may be easier to maintain a hash.  The key to the
hash could be the port id.  Here is some pseudo-code:
</P>

<PRE>
if $port{watch-list} = 1 {
	$watches{$port{id}} = "[html for watched port]"
} else { 
	$watches{$port{id}} = "[html for non-watched port]"
}</PRE>

<P>
When displaying details for the port, you could just echo $watches{$port{id}}.
</P>

<P>
I did a simple test case:
</P>
<PRE>
select ports.id, 
       ports.element_id, 
       watch_list_element.element_id, 
       CASE when watch_list_element.element_id is null 
          then 'not watched' 
          else 'watched' 
       END as watch 
  from ports left outer join watch_list_element 
       on ports.element_id = watch_list_element.element_id
 where id = 829;

 id  | element_id | element_id |    watch
-----+------------+------------+-------------
 829 |      10314 |            | not watched
</PRE>

<P>
You could easily change the 'not watched' and 'watched' to the PHP or HTML code of your choice!
</P>

<P>
<B>12 February 2002</B>: one-click port maintenance is now a reality.
When the graphics arrive, they will need to be incorporated into the 
site.
</P>

<H2>Caching</H2>

I've been able to writing a simple caching system for the home page.  It is 
refreshed from the database every minute, if a port commit has taken place.
It will need some refinement to take into consideraton port refreshes which
have occurred.

<H2>User Manual</H2>

<P>
We need to write a user manual.  The system is getting rather complex and
it would be good to provide a simple set of instructions for those coming
to the website and logging in for the first time.
</P>

Things to cover:

<DL>
<DT>What is this website about?</DT>
	<DD>
	This website will help you keep up with the latest releases of your
	favourite software.  When a new version of the software is available,
	FreshPorts will send you an email telling you about the change.
	</DD>

<DT>What is a port</DT>
	<DD>
	A port is a simple easy way to install an application.
	A port is a collection of files.  These files contain the location
	of the source file, any patches which must be appplied,
	instructions for building the application, and the installation
	procedure.  Removing an installed port is also easy.  For full
	details on how to use ports, please refer to the offical port
	documents in the <A HREF="http://www.FreeBSD.org/handbook/">FreeBSD
	Handbook</A>.
	</DD>
<DT>Where do ports come from?</DT>
	<DD>Ports are created by other FreeBSD volunteers, just like you
	and just like the creators of FreshPorts.  The FreshPorts team does
	not create ports; we just tell you about the latest changes.  The
	FreeBSD Ports team creates, maintains, and upgrades the ports.
	</DD>
<DT>Who do I talk to about a port?</DT>
	<DD>The official mailing list is freebsd-ports&#64;freebsd.org.
		More information all FreeBSD mailing lists can be obtained
		from <A HREF="http://www.FreeBSD.org/handbook/eresources.html#ERESOURCES-MAIL">FreeBSD Mailing Lists</A>.
		You can ask for help there and in our <A HREF="/phorum/">Support
		Forum</A>.
<DT>How do I get these ports?</DT>
	<DD>For full information on how to obtain the ports which appear on
	this webite, please see <A HREF="http://www.FreeBSD.org/ports/">FreeBSD Ports</A>.
	The easist way to get a port is via cvsup.  An abbreviated example is
	<BR><BR>
	cvsup -h cvsup.your.fav.server /usr/share/examples/cvsup/ports-supfile
	</DD>
<DT>How is the website updated?</DT>
	<DD>
	The source code for the entire FreeBSD operating system and the Ports tree
	are stored in the official <A HREF="http://www.FreeBSD.org/cgi/cvsweb.cgi">FreeBSD 
	repository</A>.  Each time a change is committed to this <A HREF="http://cvshome.org/">CVS</A>
	repository, a mail message is sent out to the cvs-all mailing list.  FreshPorts
	takes these mail messages, parses them, and then loads them into a database.
	In theory, it's fairly straight forward.  In practice, there's much more to
	it than first meets the eye.  The website is updated as soon as the message
	arrives.
	</DD>
<DT>What does unknown mean for a revsion number?</DT>
	<DD>It means the data has been converted from an earlier
		version of the FreshPorts database that did not record this information.
	</DD>
<DT>How can I link to your site?</DT>
	<DD>Yes, thank you, you can.  No need to ask us.  Just go ahead and do it.
		We prefer the name FreshPorts (one word, mixed case). The following 
		HTML is a good place to start:<BR><BR>

		&lt;A HREF="http://www.freshports.org/"&gt;FreshPorts&lt;/A&gt;
	</DD>
<DT>Why do I need a different login for the Forums?</DT>
	<DD>
	You only need a login for the <A HREF="/phorum/">forums</A> if
	you want to use a login.  A login will ensure that only you can
	post under the name you enter.  It is a separate login because
	we didn't write the <A HREF="http://www.phorum.org/">Phorum software</A>
	used to implement for forums.
	</DD>
</DL>

<H2>Features to add</H2>

<OL>
<LI>Viewing all the commits for a given day</LI>
<LI>set robots.txt up....</LI>
</OL>

<H2>New Features</H2>
<DL>
<DT>Face lift</DT>
	<DD>New fonts, different layout</DD>
<DT>full commit messages<DT>
	<DD>The switch to XML input allows us to capture more data</DD>
<DT>URLs mirror directory struture</DT>
	<DD>You know the path to your favourite ports via /usr/ports.  Use the 
		same path in FreshPorts (e.g <A HREF="/devel/portupgrade/">sysutils/portupgrade</A>).
	</DD>
<DT>one-click watch list add/remove</DT>
	<DD>See a port you like? You can add it to your watch list with 
		a single click.
	</DD>
<DT>link to commit details from front page</DT>
	<DD>Want to know what files were changed in this commit?  It's now
	just one click away.  One more click will take to you the FreeBSD
	CVS repository.</DD>
<DT>Forums are back!</DT>
	<DD>The <A HREF="/phorum/">support forums</A> are back, better than ever</DD>
<DT>Use your pkg_info output to update your watch list<DT>
	<DD>pkg_info displays list of the ports installed 
		on your system.  Now you can use <A HREF="/pkg_upload.php">our scripts</A>
		to use this data to upgrade your watch list!
	</DD>
<DT>Search</DT>
	<DD>There is now a search on the front page</DD>
</DL>

<H2>Watch list upload</H2>

ports not found in INDEX; keep INDEX up to date; what 
about just searching the db instead of INDEX?

</BODY>
</HTML>
