<HTML>

<HEAD>
<TITLE>FreshPorts - project website</TITLE>
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

<H1>FreshPorts Development Site - Processing</H1>

<P>This page contains an overview of FreshPorts2 message processing.</P>

<H2>The Four Main Steps</H2>

<P>
FreshPorts2 obtains its information from the FreeBSD cvs-all mailing list.
It is completely automated, requiring no manual steps. There are four logical 
steps to processing a mail message:

<OL>
<LI>capture the raw email to <A HREF="/msgs/FreeBSD/raw/">disk</A> (procmail script)</LI>
<LI>convert the email to <A HREF="/msgs/FreeBSD/xml/">XML</A> (process_cvs_mail.pl)</LI>
<LI><A HREF="/msgs/FreeBSD/xml-output/">load the XML</A> into the database (<A HREF="/classes/">load_xml_into_db.pl</A>)</LI>
<LI>if part of the ports tree, update the ports/categories table accordingly</LI>
</OL>
</P>

<H2>Involving other operating systems</H2>

<P>
Adding other source trees (e.g. OpenBSD, NetBSD) would necessiate the creation
of a custom script for XML creation (step 2).  The procmail script will ensure
that the correct script is invoked for a given mailing list.
</P>

<H2>High level overview of each step</H2>

<P>In the present implementation, step 1 does the following:

<UL>
<LI>determine that the script is for FreeBSD cvs-all</LI>
<LI>copy the incoming email to disk</LI>
<LI>invoke a script (freebsd-cvs.sh) which performs steps 2-4.</LI>
</UL>

</P>

<P>
Step 2 (conversion to XML)

<UL>
<LI>custom script creating XML according to the FreshPorts DTD.</LI>
</UL>

</P>

<P>
Step 3 (processing of the XML) is the population of the following tables:

<UL>
<LI>commit_log (basic details of the message)</LI>
<LI>commit_log_elements (one entry for each file touched by the commit)</LI>
<LI>element_revisions (one entry for each new revision)</LI>
</UL>

</P>

<P>
Step 4 (updating the ports subsystem) affects the following tables

<UL>
<LI>ports</LI>
<LI>categories</LI>
<LI>commit_log_port (lists the ports affected by a given commit)</LI>
</UL>

</P>

<P>
Steps 3 and 4 are actually carried out by a single script (load_xml_into_db.pl).
</P>

<H2>Details of Port subsystem update</H2>

<P>
Step 4 (updating the ports subsystem) consists of:


<OL TYPE="i">
<LI>scan each file affected by the commit
<LI>if no port updates are found, there is nothing to do.
<LI>ensure the category exists, creating it if necessary
<LI>ensure the port exists, creating it if necessary (with minimal information)
<LI>mark the port as needs_refresh according the files which have been updated
<LI>fetch each of the following files if updated by the commit:

<UL>
<LI>Makefile</LI>
<LI>pkg-descr</LI>
<LI>pkg-comment</LI>
<LI>Makefile.common</LI>
<LI>files/Makefile.man</LI>
</UL>
</OL>

<P>
Note that a given commit may update more than one port and may involve more than
one category.
</P>

<H2>Short term objectives</H2>

<P>
<UL>
<LI>perform step 3 as a single transaction</LI>
<LI>Perform steps 4.i to 4.v as a single transaction</LI>
<LI>Perform step 4.vi as a single transaction</LI>
</UL>
</P>

<H2>Longer term strategies</H2>

<P>If we go to the above strategy, we'll achieve the following:
<UL>
<LI>capture every email and store it on disk and in the database with a low
likelyhood of any failure.</LI>

<LI>by dividing up the work into logical units, we can assign a given unit of work
to a given script/daemon/cron job.</LI>

</UL>

</P>

</BODY>
</HTML>
