#!/usr/bin/perl

use ports;
 
use DBI;

$BASEDIR = "/usr/ports";


$IGNOREDCATS  = "Attic|distfiles|Mk|Tools|Templates|pkg|distributed|CVS|\\.\\.|\\.";

$IGNOREDPORTS = "\\.\\.|\\.|pkg|CVS|apache13-php3-fp-modssl";

#$STARTWITHDIR  = "/usr/ports/x11-fonts";
$STARTWITHDIR = "";

print "connecting to production... press enter to continue";

<STDIN>;

#$dbh = DBI->connect('dbi:mysql:freshportstest','root','xyzzy');
$dbh = DBI->connect('dbi:mysql:freshports','root','xyzzy');

$maxlength=0;
$maxrundepends=0;
$maxbuilddepends=0;

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

$FoundStartDir = "N";

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

   while(($port = readdir(CATHANDLE))) {
      print "\n... now checking $dirname/$port .... ";
      if (!-e "$dirname/$port/Makefile") {
         print " @@@@@@ makefile does not exist (port must be in Attic)\n";
      } else {
      if (-d "$dirname/$port" && $port !~ /$IGNOREDPORTS/) {

print "...now looking at $dirname/$port/Makefile\n";

#
# if we don't change the working dir, stuff like descrpath will not
# contain /usr/ports/...etc.  It will look more like this:
#     /usr/home/dan/walkports/pkg/DESCR
# That's because DESCR is define as .{CURDIR}/pkg/DESCR etc more or less
#

         $makecommand = "make -V PORTNAME -V PKGNAME -V DESCR -V CATEGORIES -V PORTVERSION " .
                        "-V COMMENT -V MAINTAINER -V EXTRACT_SUFX -V MASTER_SITES " . 
                        "-V BUILD_DEPENDS -V RUN_DEPENDS -f $dirname/$port/Makefile";

print "makecommand = $makecommand\n";
         chdir "$dirname/$port";

#undef($portname);
#undef($packagename);
#undef($descrpath);
#undef($categories);
#undef($portversion);
#undef($commentfile);
#undef($maintainer);
#undef($extractsuffix);
#undef($mastersites);
#undef($builddepends);
#undef($rundepends);

         ($portname, $packagename, $descrpath, $categories, $portversion, $commentfile,
          $maintainer, $extractsuffix, $mastersites, $builddepends,
          $rundepends) = split(/\n/s, `$makecommand`);

$category = ExtractCategoryFromDirectory($dirname);

print " 0 $port\n";
print " 1 $portname\n";
print " a $category\n";
print " 2 $packagename\n";
print " 3 $descrpath\n";
print " 4 $categories\n";
print " 5 $portversion\n";
print " 6 $commentfile\n";
print " 7 $maintainer\n";
print " 8 $extractsuffix\n";
print " 9 $mastersites\n";
print "10 $builddepends\n";
print "11 $rundepends\n";

print "length(builddepends) = " . length($builddepends) . "\n";
print "length(rundepends)   = " . length($rundepends). "\n";

if (length($builddepends) > $maxbuilddepends) {
   $maxbuilddepends = length($builddepends);
}
   
if (length($rundepends) > $maxrundepends) {
   $maxrundepends = length($rundepends);
}

print "maxbuilddepends = $maxbuilddepends\n";
print "maxrundepends   = $maxrundepends\n";

# It appears that if no homepage is found, the homepage does not get over 
# written (i.e. cleared).  Therefore I will do it manually.
#
#undef($homepage);
#undef($longdescription);

($longdescription, $homepage) = GetDescrAndHomePage($descrpath);

$shortdescription = ReadFile($commentfile);

$packageexists = PackageExists($packname . "tgz");

# because we are adding in \ before the quotes,
# we need to quote the \'s first.

#  these bits might have \'s.   
$longdescription  =~ s/\\/\\\\/g;
$shortdescription =~ s/\\/\\\\/g;

#  these bits might have quotes.
$longdescription  =~ s/\'/\\'/g;
$shortdescription =~ s/\'/\\'/g;

print "12 $shortdescription\n";
print "13 $longdescription\n";
print "14 $homepage\n";
print "15 $packageexists\n";

print "\n ---------------------------------------- \n";

PortUpdate ($port, $portname, $category, $descrpath, $categories, $portversion, 
            $commentfile, $maintainer, $extractsuffix, $mastersites, $builddepends,
            $rundepends, $shortdescription, $longdescription, $homepage, $packageexists, $dbh);

print "maxbuilddepends = $maxbuilddepends\n";
print "maxrundepends   = $maxrundepends\n";

#if ($maxrundepends * $maxbuilddepends > 0 ) {
#   exit;
#}

print "presss enter to continue";
<STDIN>;

      } else {
         print "skipping\n";
      }
   } # else yes, the Makefile does exist.
   }
   closedir CATHANDLE;

}

#print "maximum length: $maxlength in $maxport\n";

print "maxbuilddepends = $maxbuilddepends\n";
print "maxrundepends   = $maxrundepends\n";
