#!/usr/bin/perl -w

use strict;



sub PackageExists($) {                                
                                                      
   my $package = shift;

   $package = 'abc';

}

my $packageexists = 'xyz';

print "before $packageexists\n";
PackageExists($packageexists);
print "after $packageexists\n";

