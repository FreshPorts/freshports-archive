<head>
      <title>PostgreSQL test - last 100 commits</title>
      <body>
<p>This window contains the last 100 commits.  Those rows containing N/A in subject have been imported from FreshPorts1.
</p>

      <?php
      $numrows = 100;
      $database=pg_connect("dbname=FreshPorts2 user=dan");
      if ($database) {
         $result = pg_exec ($database, "select * from commit_log order by commit_date desc limit $numrows ");
         if ($result) {
            $numrows = pg_numrows($result);
            echo $numrows . " rows to fetch\n";
            echo "<table width='*' border='1'>\n";
            echo "<tr><td>Date</td><td>Subject</td><td>Date Added</td><td>Commit Date</td><td>Committer</td><td>Log</td></tr>";
            $i = 0;
            while ($myrow = pg_fetch_array ($result, $i)) {
               $i++;
               echo "   <tr><td valign='top'>" . $myrow["message_date"] . "</td><td valign='top'>". 
                                    htmlspecialchars($myrow["message_subject"]) . "</td><td valign='top'>".
                                    $myrow["date_added"] . "</td><td valign='top'>".
                                    $myrow["commit_date"] . "</td><td valign='top'>".
                                    htmlspecialchars($myrow["committer"]) . "</td><td valign='top'>".
                                    "<pre>" . htmlspecialchars($myrow["description"]) . "</pre></td></tr>".
"\n";
               if ($i >= $numrows) break;
            }
            echo "</table>\n";
         } else {
            echo "read from test failed";
         }

         pg_exec ($database, "end");
      } else {
         echo "no connection";
      }
      ?>

      </body></html>    
