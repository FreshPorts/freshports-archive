#!/usr/bin/perl

$BASEDIR = "/usr/ports";

$maxwidth=0;
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
                if(-d "$dirname/$port" && $port ne "." && $port ne "..") {
                        open READHANDLE, "$dirname/$port/pkg/COMMENT";
                        $lines = <READHANDLE>;
                        $COMMENT{$port} = "$lines";
                }
        }
        closedir CATHANDLE;
}

while(($key,$value) = each %COMMENT) {
        if(length $value > $maxwidtth) {
                $maxwidth = length $value;
		$maxport  = $key;
        }
#       print "$key:$value";
}

print "maximum length: $maxwidth in $maxport\n";
