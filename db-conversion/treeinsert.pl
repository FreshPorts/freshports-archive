#!/usr/bin/perl

#
# take a list of pathnames and add them all to the database
#

use DBI;
use strict;

#use lib './';
use portspsql;

my $dbh;
my $sth;
my $filename;
my $FileDirFlag;

my $BaseDirectory = '/home/repositories/FreeBSD/ncvs/';

$dbh = DBI->connect('DBI:Pg:dbname=FreshPorts2', 'dan', '');
if ($dbh) {
   print "connected\n";

   while ( defined($filename = <STDIN> ) ) {
      # remove the trailing CR/LF
      $filename =~ s/\n//g;
#      print "$filename\n";

      #
      # get the element ID for this element
      #

      my $id = GetIDFromPath($filename, $dbh);

      if (!defined($id)) {
         if (-d "$BaseDirectory$filename") {
            $FileDirFlag = 'D';
         } else {
            $FileDirFlag = 'F';
         }

         $id = AddNewElement($filename, $FileDirFlag, $dbh);

         print " * * * *  adding $filename = $id\n";
      }
   }

   $dbh->disconnect();
} else {
   print "Cannot connect to Postgres server: $DBI::errstr\n";
   print " db connection failed\n";
}
