<HTML>

<HEAD>
<TITLE>FreshPorts - migration</TITLE>
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

<H1>FreshPorts Development Site - Migration from FP to FP2</H1>

<P>Dan Langille - 31 January 2002</P>

<P>
This page documents the migration strategy for implementing FP2 on a production
database server.  It deals mainly with populating the database and migrating
data from the existing production database to the new production database.
</P>

<P>
The existing database server is mySQL.  The new database server is PostgreSQL.  The database
schema has some similar components and many new tables.  It may be feasible to convert everything.
It may not be practical.  That will be determined with time.
</P>

<H2>Data which must be migrated</H2>

<P>
The following data must be migrated.  This will mostly likely necessitate
some sort of data modification.  The table names appears in (brackets).
</P>

<UL>
<LI>user logins (<B>users</B>)</LI>
<LI>watch lists for users (<B>watch_list</B>, <B>watch_list_element</B>)</LI>
</UL>

<P>
NOTE: this information cannot be migrated until after the rest of the database
is populated.  Specifcally, the element table needs to be fully populated
before the watch_list_element table can be migrated.
</P>


<H2>Data which needs to be populated</H2>

<P>
The following tables need to be populated.

<UL>
<LI>categories</LI>
<LI>element</LI>
<LI>element_revision</LI>
<LI>ports</LI>
<LI>system</LI>
<LI>system_branch</LI>
</UL>

<P>
There is more than one way to populate these tables.
</P>

<H3>Populating from a ports cvsup</H3>

<P>
One way to do this is from an existing cvsup of the ports collection:
</P>

<BLOCKQUOTE><PRE>
cvsup ports-supfile
find /usr/ports > ~/ports-list</PRE></BLOCKQUOTE>

<P>
This will produce a full list of the ports tree which can then be added, one at a time to the element table,
creating ports and categories as necessary.
</P>

<P>
This strategy will probably be used when it comes to to track all of the source tree (see 
<A HREF="http://FreshSource.org/">http://FreshSource.org/</A>).
</P>

<TABLE BORDER="1" CELLPADDING="6" CELLSPACING="1">
<TR><TD><B>Good</B></TD><TD><B>Bad</B></TD></TR>
<TR><TD>ensures data is consistent with cvs</TD><TD>we lose the existing FreshPorts commit history</TD></TR>
</TABLE>

<H3>Migrating from the existing database</H3>

<P>
It may be more practical to take the existing FreshPorts data and convert it in part.  Specifically,
the category and port tables.  The commit logs will be more interesting to convert.  The only real issue
is that the new layout stores revision numbers; this information is not available in the old database.
</P>

<TABLE BORDER="1" CELLPADDING="6" CELLSPACING="1">
<TR><TD><B>Good</B></TD><TD><B>Bad</B></TD></TR>
<TR><TD>existing FreshPorts commit history is retained</TD><TD>commit history will not include revision numbers</TD></TR>
</TABLE>

<P>
Since we have no revision numbers, we will have to fake it.  I suggest we use "unknown" as the revision 
number. This means that all migrated commits will be against the same logical revision number.  I have no 
idea of how to deal with this in a simple uncomplicated manner.  For now, I think this will be sufficient.
Any solution with real revision numbers would involve a CVS query looking for commit dates, etc.  I think
that is far too much to deal with just now.
</P>

<H3>Check the data against cvs</H3>
<P>
There is a script which will compare FreshPorts with what is on disk from a cvsup ports-supfile.
Ports which are not found on disk are flagged as such.  The script will also tell you which ports
were found on disk but not in FreshPorts. This script should be upgraded and run on a regular basis.
</P>


<H3>Things to consider</H3>

<P>
The existing port refresh code assumes that the files need to be first fetched from the CVS server.
This should be changed in a couple of ways:
</P>

<UL>
<LI>configure the cvsup server so it can be parameterized.  At 
present, it always uses the main cvsup server at FreeBSD.org.</LI>
<LI>Modify the code that you can choose whether or not you want
to fetch the files before refreshing</LI>
</UL>

<P>
Actually, this point may not affect the migration process, but it may be worthwhile keeping in mind.
</P>

<H2>Recommendations</H2>

<P>
At this point in time, it appears the best strategy is to migrate the existing FreshPorts data.
</P>

</BODY>
</HTML>