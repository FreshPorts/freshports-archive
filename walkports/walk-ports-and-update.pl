#!/usr/bin/perl -w

use strict;
use ports;
 
use DBI;

my $BASEDIR = "/usr/ports";


my $IGNOREDCATS  = "Attic|distfiles|Mk|Tools|Templates|pkg|distributed|CVS|\\.\\.|\\.";

#my $STARTWITHDIR  = "/usr/ports/x11-fonts";
my $STARTWITHDIR = "";

print "connecting to production... press enter to continue";

<STDIN>;

#my $dbh = DBI->connect('dbi:mysql:freshportstest','root','xyzzy');
my $dbh = DBI->connect('dbi:mysql:freshports','root','xyzzy');

my $maxlength=0;
my $dirname='';
my @CATEGORIES;

#
# get a list of categories
#
opendir PORTSHANDLE,$BASEDIR;
while(($dirname = readdir(PORTSHANDLE))) {
   print "looking at $BASEDIR/$dirname";
   if(-d "$BASEDIR/$dirname" && grep(/^[a-z]/, $dirname)) {
      print " considering  $dirname";
      if ($dirname !~ /$IGNOREDCATS/) {
         push @CATEGORIES, "$BASEDIR/$dirname";
         print " accepted\n";
      } else {
         print " <=== ******** ignoring\n";
      }
   } else {
      print " rejected\n";
   }
}
closedir PORTSHANDLE;

# iterate the list of categories and generate a hash of all the ports
# we use a hash because ports might exist in multiple places

my $FoundStartDir = "N";

CATEGORY:
foreach $dirname (@CATEGORIES) {
   opendir CATHANDLE, $dirname;
   print "checking $dirname";

   # we are looking for a directory to start with and we haven't found it yet
   if ($STARTWITHDIR ne "" && $FoundStartDir eq "N") {
      # is this our starting place?
      if ($dirname eq $STARTWITHDIR) {
         # this is where we start
         $FoundStartDir = "Y";
         print "\nfound our starting directory, press any key to continue.";
         print "FoundStartDir = $FoundStartDir\n";
         <STDIN>;
         closedir CATHANDLE;
      } else {
         print "\n";
         # we haven't found our start, so let's continue looping
         next CATEGORY;
      }
   }

   while((my $port = readdir(CATHANDLE))) {
      RefreshPort($dirname, $port, $dbh);

      print "presss enter to continue";
      <STDIN>;

   }
   closedir CATHANDLE;

}

#print "maximum length: $maxlength in $maxport\n";



