#!/usr/bin/perl

$BASEDIR = "/usr/ports";

$maxlength=0;
# get a list of categories
opendir PORTSHANDLE,$BASEDIR;
while(($dirname = readdir(PORTSHANDLE)))
{
        if(-d "$BASEDIR/$dirname" && grep(/^[a-z]/, $dirname)) {
                push @CATEGORIES, "$BASEDIR/$dirname";
        }
}
closedir PORTSHANDLE;

# iterate the list of categories and generate a hash of all the ports
# we use a hash because ports might exist in multiple places

foreach $dirname (@CATEGORIES) {
        opendir CATHANDLE, $dirname;
        while(($port = readdir(CATHANDLE)))
        {
                if(-d "$dirname/$port" && $port ne "." && $port ne ".." && $port ne "pkg") {

($mastersites) = split(/\n/s, `make -V MASTER_SITES -f $dirname/$port/Makefile`);


$length = length($mastersites);
                        print "port = $dirname/$port master=$mastersites length = '$length'\n";
                        if ($length > $maxlength) {
                                $maxlength = $length;
                                $maxport   = $port;
                        }
                        print "maxlength = $maxlength\n";

                }
        }
        closedir CATHANDLE;
}

print "maximum length: $maxlength in $maxport\n";

