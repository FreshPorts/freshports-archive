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

<H1>FreshPorts Development Site - things to do</H1>


<P>Well, we have some work to do...</P>

<P>
I remember a rather large list of things to do which Harold wrote up some
time ago.  That is probably in a cvs repo somewhere on the XEON.  Most of
that, IIRC, was related to FreshPorts2 website design.  I don't intend
to duplicate that list here.  We have more immediate needs at present
before we can even consider those items.
</P>

<H2>perl classes</H2>

<P>This is our most urgent requirement.  We need <A HREF="/classes">perl classes</A> for the following:

<UL>
<LI>commit_log</LI>
<LI>commit_log_element</LI>
<LI>element_revision</LI>
</UL>

These should be fairly easy to create, using <A HREF="/classes/element.pm">FreshPorts::Element</A>
as a starting point.
</P>

<H2>Webpages/scripts</H2>

<P>The conversion process is probably the biggest step but can not
be completed until the perl scripts are finished.
Part of the conversion process from mySQL to PostgreSQL involves
the conversion of the webpages and the scripts.  Right now we need
the following added to Mantis.  If you want do add these, contact
dan @ freshports.org and I will add you to the list of Mantis users.

<UL>
<LI>list of webpages needing conversion</LI>
<LI>list of scripts needing conversion</LI>
</UL>

<H2>Other random ideas:</H2>

<P>Each of these are completely stand-alone.  Go for it!</P>
<UL>
<LI>get the utility working and deployed which takes your pkg_info output and
    loads up your watch list</LI>
<LI>send reminder notices out to people who have had bounced emails, letting
   them know</LI>
<LI>at present, there is a system wide "date last notices were sent out".
    if your email bounces for a few days, then restarted, you don't get
    told about the updates which occurred during your bouce period.</LI>
</UL>

<H2>Other ideas?</H2>
<P>If you have any ideas, suggestions, please let the <A HREF="mailinglists.html">list</A> know.</P>

</BODY>
</HTML>
