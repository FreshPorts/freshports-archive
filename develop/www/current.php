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

<H2>Watch list upload</H2>

ports not found in INDEX; links to website; keep INDEX up to date; what 
about just searching the db instead of INDEX?

</BODY>
</HTML>
