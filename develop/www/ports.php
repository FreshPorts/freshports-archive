<head>
	<title>ports  - PostgreSQL test - last 100 commits</title>
<body>
<p>This window contains the last 100 commits.  Those rows containing N/A in subject have been imported from FreshPorts1.
</p>
<P><B>NOTE:</B> this log is missing entries between June 18 and July 29 2001.  The database server was down.
The messages are in the mail archives.  We just have to process them.</P>

      <?php

function StripQuotes($string) {
	$string = str_replace('"', '', $string);

	return $string;
}

function FormatTime($Time, $Adjustment, $Format) {
	return date($Format, strtotime($Time) + $Adjustment);
}


function GetPortNameFromFileName($file_name) {

	list($fake, $subtree, $category, $port, $extra) = split('/', $file_name, 4);

#	return $subtree;
	return "$category/$port";

}

      $numrows = 500;
      $database=pg_connect("dbname=FreshPorts2Test user=dan");
      if ($database) {

#
# we limit the select to recent things by using a date
# otherwise, it joins the whole table and that takes quite a while
#
$sql = " 
select DISTINCT commit_log.commit_date as commit_date_raw,
       commit_log.id as commit_log_id,
       commit_log.description as commit_description,
       to_char(commit_log.commit_date - INTERVAL '10800 seconds', 'YYYY-Mon-DD') as commit_date,
       to_char(commit_log.commit_date - INTERVAL '10800 seconds', 'HH24:MI') as commit_time,
	   commit_log_port.port_id as port_id,
	   categories.name as category,
	   categories.id   as category_id,
	   element.name    as port,
	   ports.version   as version,
	   element.status    as status,
	   ports.needs_refresh  as needs_refresh,
	   ports.forbidden      as forbidden,
	   ports.broken         as broken
  from commit_log_port, commit_log, ports, element, categories
 where commit_log.commit_date        > '2001-09-01'
   and commit_log_port.commit_log_id = commit_log.id
   and commit_log_port.port_id       = ports.id
   and categories.id                 = ports.category_id
   and element.id                    = ports.element_id
order by commit_log.commit_date desc,
         commit_log_id,
         category, 
         port
         limit $numrows";

#echo "\n<pre>sql=$sql</pre>\n";

         $result = pg_exec ($database, $sql);
         if ($result) {
            $numrows = pg_numrows($result);
            echo $numrows . " rows to fetch\n";
			if ($numrows) { 

				$i=0;
				$GlobalHideLastChange = "N";
#				unset($ThisChangeLogID);
				while ($myrow = pg_fetch_array ($result, $i)) {
					$rows[$i] = $myrow;

					#
					# if we do a limit, it applies to the big result set
					# not the resulting set if we also do a DISTINCT
					# thus, count the commit id's ourselves.
					#
#					if ($ThisChangeLogID <> $myrow["commit_log_id"]) {
#						$ThisChangeLogID = $myrow["commit_log_id"];
						$i++;
#					}
#					echo "$i, ";
					if ($i >= $numrows) break;
				}

				$NumRows = $numrows;
				$LastDate = '';
				if ($NumRows > 1) {
					$LastChangeLogID = $rows[$i]["change_log_id"];
					$LastChangeLogID = -1;
				}

?>

<table width="100%" border="1" CELLSPACING="0" CELLPADDING="5"
            bordercolor="#a2a2a2" bordercolordark="#a2a2a2" bordercolorlight="#a2a2a2">
<tr>
    <td colspan="3" bgcolor="#AD0040" height="30">
        <font color="#FFFFFF" size="+1">freshports - <? echo $MaxNumberOfPorts ?> most recent commits
        <? //echo ($StartAt + 1) . " - " . ($StartAt + $MaxNumberOfPorts) ?></font>
    </td>
</tr>

<?
				print "NumRows = $NumRows\n<BR>";
				$HTML = "";
				unset($ThisChangeLogID);
				for ($i = 0; $i < $NumRows; $i++) {
					$myrow = $rows[$i];
					$ThisChangeLogID = $myrow["commit_log_id"];


					if ($LastDate <> $myrow["commit_date"]) {
						$LastDate = $myrow["commit_date"];
						$HTML .= "<tr><td colspan='3'><font size='+1'>" . $myrow["commit_date"] . "</font></td></tr>";
					}

					$j = $i;

					$HTML .= "<tr><td valign='top' width='150'>";

					// OK, while we have the log change log, let's put the port details here.
					$MultiplePortsThisCommit = 0;
					while ($j < $NumRows && $rows[$j]["commit_log_id"] == $ThisChangeLogID) {
						$myrow = $rows[$j];

						if ($MultiplePortsThisCommit) {
							$HTML .= '<br>';
						}

						$HTML .= '<a href="port-description.php3?port=' . $myrow["port_id"]  . '">';
						$HTML .= "<B>" . $myrow["port"];
						
						if (strlen($myrow["version"]) > 0) {
							$HTML .= ' ' . $myrow["version"];
						}

						$HTML .= "</b></a>";


						$URL_Category = "category.php3?category=" . $myrow["category_id"];
						$HTML .= ' <font size="-1"><a href="' . $URL_Category . '">' . $myrow["category"] . '</a></font>';

						// indicate if this port needs refreshing from CVS
						if ($myrow["status"] == "D") {
							$HTML .= '<br><font size="-1">[deleted]</font>';
						}
						if ($myrow["needs_refresh"]) {
							$HTML .= ' <font size="-1">[refresh]</font>';
						}

						if ($myrow["date_created"] > Time() - 3600 * 24 * $DaysMarkedAsNew) {
							$MarkedAsNew = "Y";
							$HTML .= "<img src=\"/images/new.gif\" width=28 height=11 alt=\"new!\" hspace=2 > ";
						}

						if ($myrow["forbidden"]) {
							$HTML .= '<img src="images/forbidden.gif" alt="' . StripQuotes($myrow["forbidden"]) . '" width="20" height="20" hspace="2">';
						}
						if ($myrow["broken"]) {
							$HTML .= '<img src="images/broken.gif" alt="' . StripQuotes($myrow["broken"]) . '" width="17" height="16" hspace="2">';
						}

						$j++;
						$MultiplePortsThisCommit = 1;
					} // end while

					$i = $j - 1;

					$HTML .= "</td><td valign='top'>";
					$HTML .= '<font size="-1">' . $myrow["commit_time"] . '</font>';
#					$HTML .= '<BR><font size="-1">' . FormatTime($myrow["commit_time"], 0, "H:i") . '</font>';
#					$HTML .= '<BR><font size="-1">' . $myrow["commit_date_raw"] . '</font>';

					$HTML .= "</td><td valign='top'>";
					$HTML .= '<PRE VARIABLE WRAP>' . htmlspecialchars($myrow["commit_description"]) . "</PRE></td>\n";

					$HTML .= "</tr>\n";
				}

				$HTML .= "</td></tr>\n";

				echo $HTML;

	            echo "</table>\n";
			} else {
				echo "<P>Sorry, nothing found in the database....</P>\n";
			}
         } else {
            echo "read from test failed";
         }

         pg_exec ($database, "end");
      } else {
         echo "no connection";
      }
      ?>

      </body></html>    
