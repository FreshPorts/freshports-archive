<HTML>

<HEAD><TITLE>FreshPorts Development Site - technical insight</TITLE></HEAD>

<BODY>

<TABLE WIDTH="98%" CELLPADDING="0" CELLSPACING="0" BORDER="0">
<TR>
   <TD><A HREF="/"><IMG SRC="/images/freshports.jpg" ALT="FreshPorts.org - the place for ports" WIDTH="512" HEIGHT="110" BORDER="0"></A>
  </TD>
   <TD ALIGN="right" CLASS="sans" VALIGN="bottom"><small><? echo date("D, j M Y g:i A T") ?></small>
  </TD>
</TR>
</TABLE>

<H1>FreshPorts Development Site - technical insight</H1>

<P>
This page talks briefly about some of the technical changes
which are involved with moving from FreshPorts to FreshPorts2.
</P>

<H2>Backend database</H2>

<P>
The original FreshPorts database used <A HREF="http://www.mySQL.org/">mySQL</A>.
This worked very well.  Two of the major reason for moving to <A HREF="http://www.postgresql.org/">PostgreSQL</A>
were

<UL>
<LI>Transaction support</LI>
<LI>Stored procedures</LI>
</UL>

Of the two, stored procedures (SP) is the most important.  The flexibility
provided by SP means that the new features included in FreshPort2 were 
much easier to create.

</P>

<H2>XML</H2>
<P>
FP2 uses XML as its main input format.  This data format
allows the parsing of the incoming data to be greatly simplifed.  It also
means that FP2 can be expanded to include all of the source tree, not just
ports.  Of more significance is the ability to include any source tree,
regardless of source.
</P>

<P>
FreshPorts has always used the cvs-all mailing list as its source of 
port change information.  In FP2, each mail message is parsed and converted
to the XML format.  The resulting file is then parsed by the FP loader
and the database is updated.
</P>

<H2>Other operating systems</H2>

<P>
FreshPorts was created for the <A HREF="http://www.FreeBSD.org/">FreeBSD</A>
ports tree but it was designed to be generic in nature and to ultimately 
handle any ports tree.  To use the FP2 database, a given source tree (e.g. NetBSD, OpenBSD)
needs to supply their cvs change in XML format according to our DTD.
</P>
</BODY>
</HTML>
