<head>
      <title>PostgreSQL test - last 100 commits</title>
      <body>
<p>This window contains the last 100 commits.  Those rows containing N/A in subject have been imported from FreshPorts1.
</p>
<P><B>NOTE:</B> this log is missing entries between June 18 and July 29 2001.  The database server was down.
The messages are in the mail archives.  We just have to process them.</P>

      <?php

function GetPortNameFromFileName($file_name) {

	list($subtree, $category, $port, $extra) = split('/', $file_name, 4);

#	return $subtree;
	return "$category/$port";

}

      $numrows = 300;
      $database=pg_connect("dbname=FreshPorts2Test user=dan");
      if ($database) {

#
# we limit the select to recent things by using a date
# otherwise, it joins the whole table and that takes quite a while
#
$sql = " 
select commit_log.commit_date									as commit_date_raw, 
       commit_log.id											as commit_log_id,
       commit_log.description									as commit_description, 
       to_char(commit_log.commit_date, 'YYYY-Mon-DD')			as commit_date,
       to_char(commit_log.commit_date, 'HH24:MI')				as commit_time,
       commit_log_elements.id									as cle_id,
       commit_log_elements.element_id							as cle_element_id,
       commit_log_elements.revision_name						as cle_revision_name,
       element_pathname(element.id)								as file_name
  from commit_log_port, commit_log, commit_log_elements, element
 where commit_log.commit_date					> '2001-09-29'
   and commit_log_port.commit_log_id			= commit_log.id
   and commit_log_port.commit_log_element_id	= commit_log_elements.id
   and commit_log_elements.element_id			= element.id

 order by	commit_log.commit_date desc,
			commit_log.id,
			file_name
 limit $numrows";

#echo "\n<pre>sql=$sql</pre>\n";

         $result = pg_exec ($database, $sql);
         if ($result) {
            $numrows = pg_numrows($result);
            echo $numrows . " rows to fetch\n";
			if ($numrows) { 
	            echo "<table width='*' border='1'>\n";
    	        echo "<tr><td>id</TD><td>Commit message</td><TD>port name</TD><TD>file name</TD></tr>";
        	    $i = 0;
            	while ($myrow = pg_fetch_array ($result, $i)) {
					$i++;
					echo "   <tr><td valign='top'>" .
					$myrow["commit_log_id"]										. "</td><td valign='top'>".
					"<pre><small>" . htmlspecialchars($myrow["commit_description"])	. "</small></pre></td><td valign='top'>".
					GetPortNameFromFileName($myrow["file_name"])										. "</td><td valign='top'>".
					$myrow["file_name"]     											. "</td>" . 
					"</TR>\n";
    	 			if ($i >= $numrows) break;
	            }
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
