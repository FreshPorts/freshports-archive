#!/usr/bin/perl -w
# $Id: catchUp.pl,v 1.1 2003-08-27 18:34:33 dan Exp $
#
# Copyright (c) Stanislav Grozev <tacho@orbitel.bg>
#
##

#use warnings;    # I added -w to the first line of the line.
use strict;

use DBI;
#use Data::Dumper;

my $dbName = 'archive-example';
my $dbUser = '';
my $dbPass = '';
my $dbHost = '';

my $fileNo = $ENV{FILENO};
my $messageId = undef;
my $message = '';

if (!defined($fileNo)) {
	die("FILENO not defined.  This function should be invoked from formail\n");
}

while (<STDIN>) {
	$message .= $_;
	next unless /^Message-Id:/o;
	chomp();
	s/^Message-Id:\s<(.*)>$/$1/o;
	$messageId = $_ unless $messageId; # the first one wins, 
					   # there should be only one anyway
}
die("No Message-Id found in message $fileNo!\n") unless $messageId;

my $dbh = DBI->connect("dbi:Pg:dbname=$dbName;host=$dbHost", $dbUser, $dbPass) || die($DBI::errstr);

my $findSql = "SELECT MessageIDFound('$messageId') AS rowNum;";
my $sth = $dbh->prepare($findSql);
$sth->execute();
my @row = $sth->fetchrow_array();
$sth->finish();
$dbh->disconnect();
#print Dumper(\@row);
#exit();

if (!defined($row[0])) {
	open(MSG, ">message.$fileNo") || die("open($fileNo): $!\n");
	print MSG $message;
	close(MSG);
}

