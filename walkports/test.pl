#!/usr/bin/perl

#$IGNOREDCATS  = "Attic|distfiles|Mk|CVS";
$IGNOREDCATS  = "Attic|distfiles|Mk|Tools|Templates|pkg|distributed|CVS|\\.\\.|\\.";

#$IGNOREDCATS  = qr/Attic|distfiles|Mk|Tools|Templates|pkg|distributed|CVS|..|./;

$value = "Attic";

if ($value =~ /$IGNOREDCATS$/) {
   print " true\n";
} else {         
   print " false\n";
}

$value = "Atti";

if ($value =~ /$IGNOREDCATS$/) {
   print " true\n";
} else {
   print " false\n";
}
