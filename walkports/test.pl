#!/usr/bin/perl

my $port = "Makefile";
my $IGNOREDPORTS = "pkg|CVS|apache13-php3-fp-modssl|Makefile";

if ($port =~ /$IGNOREDPORTS/) {
   print "port is in the IGNORE list... skipping\n";
} else {          
   print "port is not to be ignored.\n";
}

$port = "pkg\_remove";

if ($port =~ /$IGNOREDPORTS/ || $port eq "." || $port eq "..") {
   print "port is in the IGNORE list... skipping\n";
} else {
   print "port is not to be ignored.\n";
}
