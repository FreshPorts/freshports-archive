#!/usr/bin/perl

($name, $dist, $suff, $sites, $mainemail) = split(/\n/s, `make -V PKGNAME -V DISTNAME -V EXTRACT_SUFX -V MASTER_SITES -V MAINTAINER -f /usr/ports/security/logcheck/Makefile`);

#print "name=$name\ndist=$dist\nsuff=$suff\nsite=$sites\nmaint=$mainemail\n";

#$length=length($mainemail);

#print "length = $length\n";

#($extra, $mainemail) = split(/ /s, `grep MAINTAINER /usr/ports/security/logcheck/Makefile`);

#chomp($mainemail);

($package, $port) = split(/\n/s, `make -V PKGNAME -V PORTNAME -f /usr/ports/security/logcheck/Makefile`);

print "package='$package'\nport=$port\n";
