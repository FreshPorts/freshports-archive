#!/usr/bin/perl

#
# this script should walk the ports tree and update the 
# ports table accordingly.
#

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

sub PortUpdate($;$;$;$;$;$;$;$;$;$;$;$;$;$;$;$) {
#PortUpdate ($name, $portname, $descrfile, $categories, $portversion, 
#          $commentfile, $maintainer, $extractsuffix, $mastersites, $builddepends,
#    $rundepends, $shortdescription, $longdescription, $homepage, $packageexists, $dbh);

   my $name             = shift;
   my $portname         = shift;
   my $descpath         = shift;
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

print "$name, $portname, $descrfile, $categories, $portversion, ",  \
      "$commentfile, $maintainer, $extractsuffix, $mastersites, $builddepends, ", \
      "$builddepends, $rundepends\n";

$category = GetCategoryFromCategories($categories);

print "category = $category", "\n";

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
      $sql = "insert into ports (name, description, last_update,                           \
              primary_category_id, last_update_description, system, version, date_created, \
              short_description, long_description, maintainer, categories,                 \
              date_last_refreshed, homepage, master_sites, extract_suffix, package_exists, \
              status) values (";

      $sql .= "'$name', '$descpath', current_timestamp, $categoryid , '',                 \
              'FreeBSD', '$portversion', current_timestamp, '$shortdescription',          \
              '$longdescription', '$maintainer', '$categories', current_timestamp,        \
              '$homepage', '$mastersites', '$extractsuffix', '$packageexists', 'A')";

      print "$sql\n";

      $sth = $dbh->prepare($sql);

      $sth->execute ||
         die "Could not insert statement ... maybe invalid?";
   } else {
      # update the time on the port
      $sql = "update ports set description = '$descpath', last_update =       \ 
              current_timestamp, version = '$portversion', short_description = \
              '$shortdescription', long_description = '$longdescription', maintainer = \
              '$maintainer', categories = '$categories', date_last_refreshed = \
              current_timestamp, homepage = '$homepage', master_sites = '$mastersites', \
              extract_suffix = '$extractsuffix', package_exists = '$packageexists', status \
              = 'N') values (";

      $sql .= "'$name', '$descpath', current_timestamp, $categoryid , '', \
              'FreeBSD', '$portversion', current_timestamp, '$shortdescription', \
              '$longdescription', '$maintainer', '$categories', current_timestamp, \
              '$homepage', '$mastersites', '$extractsuffix', '$packageexists', 'A')";

      print "$sql\n";

      $sth = $dbh->prepare($sql);

      $sth->execute ||
         die "Could not execute update statement ... maybe invalid?";
   }
}

use DBI;

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

         $makecommand = "make -V PORTNAME -V PKGNAME -V DESCR -V CATEGORIES -V PORTVERSION " .
                        "-V COMMENT -V MAINTAINER -V EXTRACT_SUFX -V MASTER_SITES " . 
                        "-V BUILD_DEPENDS -V RUN_DEPENDS -f $dirname/$port/Makefile";

print "makecommand = $makecommand\n";
         chdir $dirname;
         ($portname, $packagename, $descrpath, $categories, $portversion, $commentfile,
          $maintainer, $extractsuffix, $mastersites, $builddepends,
          $rundepends) = split(/\n/s, `$makecommand`);

print " 0 $dirname\n";
print " 1 $portname\n";
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

print "12 $shortdescription\n";
print "13 $longdescription\n";
print "14 $homepage\n";
print "15 $packageexists\n";

print "\n ---------------------------------------- \n";

PortUpdate ($dirname, $portname, $descrpath, $categories, $portversion, 
          $commentfile, $maintainer, $extractsuffix, $mastersites, $builddepends,
    $rundepends, $shortdescription, $longdescription, $homepage, $packageexists, $dbh);

exit;

      }
      
   }
   closedir CATHANDLE;

}

#print "maximum length: $maxlength in $maxport\n";

