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
#                        open READHANDLE, "$dirname/$port/pkg/COMMENT";
#                        $lines = <READHANDLE>;
#                        $COMMENT{$port} = "$lines";
                        $length = `cat $dirname/$port/pkg/COMMENT | wc -c | awk '{print $1}'`;

#$length = `cat $dirname/$port/pkg/COMMENT | wc -c`;
#$file = "$dirname/$port/pkg/COMMENT";

$cat = $dirname;
($name, $dist, $suff, $sites, $mainemail) = split(/\n/s, `make -V PKGNAME -V DISTNAME -V EXTRACT_SUFX -V MASTER_SITES -V MAINTAINER -f $dirname/$port/Makefile`);

#($extra, $mainemail) = split(/ /s, `grep MAINTAINER`);

#print "file = '$file'";
#$length = -s $file;

$length = length($mainemail);
                        print "port = $dirname/$port length = '$length'\n";
                        if ($length > $maxlength) {
                                $maxlength = $length;
                                $maxport   = $port;
                        }
                        print "maxlength = $maxlength\n";

                }
        }
        closedir CATHANDLE;
}

#while(($key,$value) = each %COMMENT) {
#        if(length $value > $maxwidtth) {
#                $maxlength = length $value;
#		$maxport  = $key;
#        }
#       print "$key:$value";
#}

print "maximum length: $maxlength in $maxport\n";

