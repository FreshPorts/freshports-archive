#!/usr/bin/perl -w

use strict;

`sh /home/dan/walkports/fetch-cvs-file.sh category port key`;

print "error = " . $! . "\n";
print "error = " . ($? >> 8) . "\n";
