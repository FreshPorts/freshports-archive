#!/usr/bin/perl

#
# this script should walk the ports tree and update the 
# ports table accordingly.
#

$BASEDIR = "/usr/ports";

$maxlength=0;

#
# get a list of categories
#
opendir PORTSHANDLE,$BASEDIR;
while(($dirname = readdir(PORTSHANDLE))) {
   print "looking at $BASEDIR/$dirname\n";
      if(-d "$BASEDIR/$dirname" && grep(/^[a-z]/, $dirname)) {
         push @CATEGORIES, "$BASEDIR/$dirname";
      }
}
closedir PORTSHANDLE;

# iterate the list of categories and generate a hash of all the ports
# we use a hash because ports might exist in multiple places

foreach $dirname (@CATEGORIES) {
   opendir CATHANDLE, $dirname;
   print "checking $dirname\n";
   while(($port = readdir(CATHANDLE))) {
      print "   $dirname/$port\n";
      if (-d "$dirname/$port" && $port ne "." && $port ne ".." && $port ne "pkg") {
print "...now looking at $dirname/$port/Makefile\n";

#
# if we don't change the working dir, stuff like descrpath will not
# contain /usr/ports/...etc.  It will look more like this:
#     /usr/home/dan/walkports/pkg/DESCR
# That's because DESCR is define as .{CURDIR}/pkg/DESCR etc more or less
#
         chdir $dirname;
         ($portname, $descrpath, $categories, $portversion, $commentfile, $maintainer, $extractsuffix, $mastersites, $builddepends, $rundepends) = split(/\n/s, `make -V PORTNAME -V DESCR -V CATEGORIES -V PORTVERSION -V COMMENT -V MAINTAINER -V EXTRACT_SUFX -V MASTER_SITES -V BUILD_DEPENDS -V RUN_DEPENDS -f $dirname/$port/Makefile`);

print " 0 $dirname\n";
print " 1 $portname\n";
print " 2 $descrpath\n";
print " 3 $categories\n";
print " 4 $portversion\n";
print " 5 $commentfile\n";
print " 6 $maintainer\n";
print " 7 $extractsuffix\n";
print " 8 $mastersites\n";
print " 9 $builddepends\n";
print "10 $rundepends\n";

print "\n ---------------------------------------- \n";

      }
   }
   closedir CATHANDLE;

}

#print "maximum length: $maxlength in $maxport\n";

