<?
	# TODO: 
	#  case insensitive searches
	#  partial matches
	#  handle multiple matches
	#  code clean up
	
	$Errs = Array();
	$MY404 = '/haroldp/missing.php';

	if (!empty($REQ)) {
	
			## First we'll look at their request and see if we can    ##
			## figure out what they wanted.  We might be able to do   ##
			## this more efficiently with one big regex and a switch. ##
		
			## untaint the request. ##
		if (eregi('[^-_\+\./0-9a-z]', $REQ)) {
			Header("Location: $MY404");
		}
		
		if ( eregi('^freebsd/([^/]+)/([^/]+)/?$', $REQ, $match) || eregi('^([^/]+)/([^/]+)/?$', $REQ, $match) ) {
			$Action = 'Find a FreeBSD port';		

			$database = pg_connect("dbname=FreshPorts2 user=dan");

			if ($database) {
			
				$sql = "SELECT 
						e.id                AS ElementID, 
						e.name              AS ElementName, 
						c.name              AS CategoryName, 
						p.long_description  AS Description 
					FROM 
						element e, 
						categories c, 
						ports p 
					WHERE 
						e.id=p.element_id
						AND
						p.category_id=c.id
						AND
						c.name='" . $match[1] . "' 
						AND
						e.name='" . $match[2] . "' 
					LIMIT 1
				";

				$result = pg_exec ($database, $sql);
				if ($result) {
					if (pg_numrows($result) == 1) {
						$myrow = pg_fetch_array($result, 0);
					}
					else {
						Header("Location: $MY404");
					}
				}
				else {
					$Errs[] = "SQL Query Failed: " . pg_errormessage();
				}
			}
			else {
				$Errs[] = 'Could not connect to Database';
			}
		}
		elseif ( eregi('^openbsd/([^/]+)/([^/]+)/?$', $REQ, $match) ) {
			$Action = 'Find an OpenBSD port';		
		
		}
		elseif ( eregi('^netbsd/([^/]+)/([^/]+)/?$', $REQ, $match) ) {
			$Action = 'Find a NetBSD port';		
		
		}
		elseif ( eregi('^(darwin|macosx)/([^/]+)/([^/]+)/?$', $REQ, $match) ) {
			## 404.  openpackages not implemeted yet ##	

			Header("Location: $MY404");
		}
		elseif ( eregi('^openpackages?/([^/]+)/([^/]+)/?$', $REQ, $match) ) {
			## 404.  openpackages not implemeted yet ##	

			Header("Location: $MY404");
		}
		else {
			## 404.  No idea what they were looking for ##	

			Header("Location: $MY404");
		}
	
	}
	else {
		## We didn't get a $REQ argument.                    ##
		## Panic.  They must have called this page directly? ##

		Header("Location: $MY404");
	}

?>
<HTML>
<HEAD>
	<TITLE>FP Redirector</TITLE>
</HEAD>
<BODY BGCOLOR="#FFFFFF" TEXT="#000000" LINK="#000099" ALINK="#FF3300" VLINK="#000066">

<? IF (SizeOf($Errs) > 0): ?>
<CENTER>
<TABLE BORDER="0" CELLSPACING="1" CELLPADDING="2">
<TR>
	<TD>
	<FONT FACE="arial" COLOR="#990000">
	<B>ERROR"</B><BR>
	<?= Join("<BR>\n", $Errs); ?>
	</FONT>
	</TD>
</TR>
</TABLE>
</CENTER>
<P>
<? ENDIF; ?>



<CENTER>
<TABLE BORDER="0" CELLSPACING="1" CELLPADDING="2">
<TR>
	<TD><B>Request:</B></TD>
	<TD><?= $REQ ?></TD>
</TR>
<TR>
	<TD><B>Action:</B></TD>
	<TD><?= $Action ?></TD>
</TABLE>
</CENTER>
<P>


<CENTER>
<TABLE BORDER="0" CELLSPACING="1" CELLPADDING="2" BGCOLOR="#666666">
<TR>
	<TD BGCOLOR="#FFFFFF">
<? IF ($result): ?>
		<TABLE BORDER="0" CELLSPACING="2" CELLPADDING="2" BGCOLOR="#FFFFFF">
		<TR>
			<TD BGCOLOR="#CCCCCC"><B>Category:</B></TD>
			<TD><?= $myrow[1] ?></TD>
		</TR>
		<TR>
			<TD BGCOLOR="#CCCCCC"><B>Port:</B></TD>
			<TD><?= $myrow[2] ?></TD>
		</TR>
		<TR VALIGN="top">
			<TD BGCOLOR="#CCCCCC"><B>Description:</B></TD>
			<TD><?= nl2br(HTMLSpecialChars($myrow[3])) ?></TD>
		</TR>
		</TABLE>

<? ELSE: ?>
		<B>No Results Found?!?!?</B>

<? ENDIF; ?>
	</TD>
</TR>
</TABLE>
</CENTER>


</BODY>
</HTML>
<? pg_exec($database, "end"); ?>
