<head>
      <title>PostgreSQL test</title>
      <body>

      <?php
      $numrows = 199;
      $database=pg_connect("dbname=FreshPorts2 user=dan");
      if ($database) {
         $result = pg_exec ($database, "select *,Element_Pathname(id) as path from element order by id limit $numrows ");
         if ($result) {
            $numrows = pg_numrows($result);
            echo $numrows . " rows to fetch\n";
            echo "<table>\n";
            $i = 0;
            while ($myrow = pg_fetch_array ($result, $i)) {
               $i++;
               echo "   <tr><td>" . $myrow["id"] . "</td><td>" . 
                                    $myrow["name"] . "</td><td>".
                                    $myrow["path"] . "</td></tr>\n";
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
