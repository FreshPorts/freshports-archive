<head>
      <title>PostgreSQL test - last 100 commits</title>
      <body>
<p>This window contains the last 100 commits.  Those rows containing N/A in subject have been imported from FreshPorts1.
</p>
<P><B>NOTE:</B> this log is missing entries between June 18 and July 29 2001.  The database server was down.
The messages are in the mail archives.  We just have to process them.</P>

      <?php

function GetPortNameFromFileName($file_name) {

	list($fake, $subtree, $category, $port, $extra) = split('/', $file_name, 4);

#	return $subtree;
	return "$category/$port";

}

      $numrows = 100;
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
       to_char(commit_log.commit_date, 'YYYY-Mon-DD') as commit_date,
       to_char(commit_log.commit_date, 'HH24:MI') as commit_time,
	   commit_log_port.port_id as port_id,
	   element_pathname(ports.element_id) as full_file_name
  from commit_log_port, commit_log, ports
 where commit_log.commit_date        > '2001-09-29'
   and commit_log_port.commit_log_id = commit_log.id
   and commit_log_port.port_id       = ports.id
order by commit_log.commit_date desc,
commit_log.id
 limit 100";

#echo "\n<pre>sql=$sql</pre>\n";

         $result = pg_exec ($database, $sql);
         if ($result) {
            $numrows = pg_numrows($result);
            echo $numrows . " rows to fetch\n";
			if ($numrows) { 

				$i=0;
				$GlobalHideLastChange = "N";
				while ($myrow = pg_fetch_array ($result, $i)) {
					$rows[$i] = $myrow;
#					echo "$i, ";
					$i++;
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
						$HTML .= "<b>" . GetPortNameFromFileName($myrow["full_file_name"]);
#						if (strlen($myrow["version"]) > 0) {
#							$HTML .= ' ' . $myrow["version"];
#						}

						$HTML .= "</b></a>";
#
#						$URL_Category = "category.php3?category=" . $myrow["category_id"];
#						$HTML .= ' <font size="-1"><a href="' . $URL_Category . '">' . $myrow["category"] . '</a></font>';
#
#						// indicate if this port needs refreshing from CVS
#						if ($myrow["status"] == "D") {
#							$HTML .= '<br><font size="-1">[deleted]</font>';
#						}
#						if ($myrow["needs_refresh"]) {
#							$HTML .= ' <font size="-1">[refresh]</font>';
#						}
#
#						if ($myrow["date_created"] > Time() - 3600 * 24 * $DaysMarkedAsNew) {
#							$MarkedAsNew = "Y";
#							$HTML .= "<img src=\"/images/new.gif\" width=28 height=11 alt=\"new!\" hspace=2 > ";
#						}
#

						$j++;
						$MultiplePortsThisCommit = 1;
					} // end while

					$i = $j - 1;

					$HTML .= "</td><td valign='top'>";
					$HTML .= '<font size="-1">' . $myrow["commit_time"] . '</font>';

					$HTML .= "</td><td valign='top'>";
#					if ($myrow["forbidden"]) {
#						$HTML .= '<img src="images/forbidden.gif" alt="Forbidden" width="20" height="20" hspace="2">';
#					}
#					if ($myrow["broken"]) {
#						$HTML .= '<img src="images/broken.gif" alt="Broken" width="17" height="16" hspace="2">';
#					}
					$HTML .= htmlspecialchars($myrow["commit_description"]) . "</td>\n";

					$HTML .= "</tr>\n";
				}

				$HTML .= "</td></tr>\n";

				echo $HTML;


#	            echo "<table width='*' border='1'>\n";
#    	        echo "<tr><td>id</TD><td>Commit message</td><TD>port name</TD><TD>file name</TD></tr>";
#        	    $i = 0;
#            	while ($myrow = pg_fetch_array ($result, $i)) {
#					$i++;
#					echo "   <tr><td valign='top'>" .
#					$myrow["commit_log_id"]										. "</td><td valign='top'>".
#					"<pre><small>" . htmlspecialchars($myrow["commit_description"])	. "</small></pre></td><td valign='top'>".
#					GetPortNameFromFileName($myrow["full_file_name"])										. "</td><td valign='top'>".
#					$myrow["full_file_name"]     											. "</td>" . 
#					"</TR>\n";
#    	 			if ($i >= $numrows) break;
#	            }
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
