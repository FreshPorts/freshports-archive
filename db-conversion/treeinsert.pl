#!/usr/bin/perl

#
# take a list of pathnames and add them all to the database
#

use DBI;
use strict;

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
      print "$filename";

      if (-d "$BaseDirectory$filename") {
         $FileDirFlag = 'D';
      } else {
         $FileDirFlag = 'F';
      }

      print ",'$FileDirFlag'\n";

      $sth = $dbh->prepare("select Element_Add('$filename', '$FileDirFlag')");
      if (!$sth) {print "Cannot prepare: $DBI::errstr\n";}

      $sth->execute;
      if (!$sth) {print "Cannot execute: $DBI::errstr\n";}

      $sth->finish;

   }

   $dbh->disconnect();
} else {
   print "Cannot connect to Postgres server: $DBI::errstr\n";
   print " db connection failed\n";
}
