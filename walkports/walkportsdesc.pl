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

#                        open READHANDLE, "$dirname/$port/pkg/DESCR";
#                        $lines = <READHANDLE>;
#                        $DESCR{$port} = "$lines";
#                        close READHANDLE;
			$length = `cat $dirname/$port/pkg/DESCR | wc -c | awk '{print $1}'`;
        	        print "length = $length\n";
                        if ($length > $maxlength) {
				$maxlength = $length;
				$maxport   = $port;
			}
		}
        }
        closedir CATHANDLE;
}


#while(($key,$value) = each %DESCR) {
#        if(length $value > $maxwidtth) {
#                $maxlength = length $value;
#		$maxport  = $key;
#        }
#        print "$key:$value";
#}

print "maximum length: $maxlength in $maxport\n";
