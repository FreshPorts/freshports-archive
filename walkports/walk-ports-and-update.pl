#!/usr/bin/perl

#
# this script should walk the ports tree and update the 
# ports table accordingly.
#

# =================================    
sub ExtractCategoryFromDirectory($) {

   my $dir = shift;

print "directory = $dir\n";

   # split the dir into separate elements
   my @fields = split(/\//, $dir);

   #grab the last one.
   my $category = @fields[$#fields];

print "category = $category\n";

   # who's your daddy?
   return $category
}
   

# =================================
sub PackageExists($) {

   my $package = shift;
   my $exists  = "N";

   open F,"/usr/local/etc/freshports/packages.exists";

LINE:
   while(<F>){
      if(/$package/) {
         $exists = "Y";
         last LINE;
      }
   }
   close F;
                                                                
   return $exists;                                              
}  



# =================================
sub ReadFile($) {

   my $file = shift;

   open F,$file;

   $content = "";
   while(<F>){
      $content .= $_;
   }

   close F;

   return $content;
}


# =================================
sub GetDescrAndHomePage($) {

   my $file = shift;

   open F,$file;

   $DESCR = "";
   while(<F>){
      $DESCR .= $_;
      if(/WWW:(.*)/) {
         $url = $1;
         $url =~  s/^\s+//g;
      }
   }

   close F;                              
                                                                
   @result = ($DESCR, $url);                                    
                                                                
   return @result;                                              
}


# =================================
sub GetCategoryFromCategories($) {
   my $categories = shift;
   my $category;

   ($category) = split(/ /s, $categories);

   return $category;
}


# =================================

#sub GetPortCategory($category, $dbh) {
sub GetPortCategory($;$) {
   my $category = shift;
   my $dbh = shift;

   $sql = "select id from categories where name = '" . $category . "'";

   print "\n",$sql, "\n";

   $sth = $dbh->prepare($sql);

   $sth->execute ||
        die "Could not execute SQL statement ... maybe invalid?";


   @row=$sth->fetchrow_array;

   print "\nGetPortCategory = $sql which gives ", @row[0], "\n";

   return @row[0];
}

sub PortUpdate($;$;$;$;$;$;$;$;$;$;$;$;$;$;$;$;$) {
#PortUpdate ($name, $portname, $category, $descrfile, $categories, $portversion, 
#          $commentfile, $maintainer, $extractsuffix, $mastersites, $builddepends,
#    $rundepends, $shortdescription, $longdescription, $homepage, $packageexists, $dbh);

   my $name             = shift;
   my $portname         = shift;
   my $category         = shift;
   my $descrfile        = shift;
   my $categories       = shift;
   my $portversion      = shift;
   my $commentfile      = shift;
   my $maintainer       = shift;
   my $extractsuffix    = shift;
   my $mastersites      = shift;
   my $builddepends     = shift;
   my $rundepends       = shift;
   my $shortdescription = shift;
   my $longdescription  = shift;
   my $homepage         = shift;
   my $packageexists    = shift;
   my $dbh              = shift;


if ($name ne $portname) {
   print "*************** port ('$name') differs from portname('$portname')\n";
}

print " 0 $name\n";
print " 1 $portname\n";
print " a $category\n";
print " 2 $descrfile\n";
print " 3 $categories\n";
print " 4 $portversion\n";
print " 5 $commentfile\n";
print " 6 $maintainer\n";
print " 7 $extractsuffix\n";
print " 8 $mastersites\n";
print " 9 $builddepends\n";
print "10 $rundepends\n";
print "11 $shortdescription\n";
print "12 $longdescription\n";
print "13 $homepage\n";
print "14 $packageexists\n";

# this asks for user input
#<STDIN>;

#return;

   $categoryid = GetPortCategory($category, $dbh);
   if (!$categoryid) {
      print "ERROR *** could not find category for $category\n";
      exit;
   }

   # update the port, creating it if necessary

   $sql = "select id from ports where name = '$name' and primary_category_id = $categoryid";
   print "sql = ", $sql, "\n";
   $sth = $dbh->prepare($sql);

   $sth->execute ||
      die "Could not execute SQL statement ... maybe invalid?";

   @row=$sth->fetchrow_array;

   if (@row) {
      print "something found\n";
   } else {
      print "nothing found\n";
   }

   # Get the short description (and escape them)
   # Get the long description (and escape them)
   # get homepage
   # get package exists

   print "port ID found = ", $row[0], "\n";
  if (!@row) {
      # no such port.  create it.
      $sql = "insert into ports (name, description,                            \
              primary_category_id, system, version, date_created, \
              short_description, long_description, maintainer, categories,                 \
              date_last_refreshed, needs_refresh, homepage, master_sites, extract_suffix, package_exists, \
              status) values (";

      $sql .= "'$name', '$descpath', $categoryid ,                 \
              'FreeBSD', '$portversion', current_timestamp, '$shortdescription',          \
              '$longdescription', '$maintainer', '$categories', current_timestamp, 'N',    \
              '$homepage', '$mastersites', '$extractsuffix', '$packageexists', 'A')";

      print "$sql\n";

      $sth = $dbh->prepare($sql);

      $sth->execute ||
         die "Could not insert statement ... maybe invalid?";
   } else {
      # update the time on the port
      $sql = "update ports set description = '$descpath',       \ 
              version = '$portversion', short_description = \
              '$shortdescription', long_description = '$longdescription', maintainer = \
              '$maintainer', categories = '$categories', date_last_refreshed = \
              current_timestamp, homepage = '$homepage', master_sites = '$mastersites', \
              extract_suffix = '$extractsuffix', package_exists = '$packageexists', status \
              = 'N', needs_refresh = 'N' where id = $row[0]";

      print "$sql\n";

      $sth = $dbh->prepare($sql);

      $sth->execute ||
         die "Could not execute update statement ... maybe invalid?";
   }
}

use DBI;

$BASEDIR = "/usr/ports";


$IGNOREDCATS  = "Attic|distfiles|Mk|Tools|Templates|.|..|pkg|distributed|CVS";
$IGNOREDCATS  = "Attic|distfiles|Mk|Tools|Templates|pkg|distributed|CVS|\\.\\.|\\.";

$IGNOREDPORTS = "\\.\\.|\\.|pkg";

$STARTWITHDIR  = "/usr/ports/x11-fonts";

$dbh = DBI->connect('dbi:mysql:freshports','updater','xyzzy');

$maxlength=0;

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

($longdescription, $hompage) = GetDescrAndHomePage($descrpath);

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

      } else {
         print "skipping\n";
      }
      
   }
   closedir CATHANDLE;

}

#print "maximum length: $maxlength in $maxport\n";

