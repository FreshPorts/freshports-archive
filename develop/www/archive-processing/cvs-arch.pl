#!/usr/bin/perl -w
#
# $Ringlet: perl/text/cvs-arch/cvs-arch.pl,v 1.1.1.1 2003/08/27 13:18:37 roam Exp $

use strict;

use DBI;
use DBD::Pg;
use Getopt::Std;
# Remove this if using msgid_fast() instead of msgid()
use Mail::Header;

my $debug = 0;

sub debug($)
{
	print STDERR "RDBG $_[0]\n" if $debug;
}

sub msgid($)
{
	my ($msg) = @_;
	my ($hdr, $count, $id);

	$hdr = new Mail::Header [@{$msg}], Modify => 0, MailFrom => 'KEEP';
	die "Could not read the header: $!\n" unless (defined($hdr));
	$count = $hdr->count('Message-ID');
	if ($count == 0) {
		die("Could not find a message ID\n");
	} elsif ($count > 1) {
		warn("More than one message ID present\n");
	}
	return $hdr->get('Message-ID');
}

sub msgid_fast($)
{
	my ($msg) = @_;

	foreach my $l (@{$msg}) {
		if ($l eq $/) {
			return undef;
		} elsif ($l =~ /^Message-Id:\s*(\S*)/i) {
			return $1;
		}
	}
	return undef;
}

sub checkdb($)
{
	my ($id) = @_;
	my ($dbh, $q, @a);

	if ($id =~ /^<(.*)>$/) {
		$id = $1;
	}
	debug("db testing for $id\n");
	$dbh = DBI->connect('dbi:Pg:dbname=archive-example', '', '') or
	    die("Could not connect to the database: $!\n");
	$q = $dbh->prepare('select messageidfound(:p0)') or
	    die("Could not prepare query: ".$dbh->errstr()."\n");
	$q->bind_param(0, $id) or die("Bind param: ".$dbh->errstr()."\n");
	$q->execute() or die("Executing db query: ".$dbh->errstr()."\n");
	@a = $q->fetchrow_array();
	$q->finish();
	$dbh->disconnect();
	return undef if ($#a == -1);
	debug("a has ".($#a + 1)." member(s)\n");
	return $a[0];
}

sub usage()
{
	die("Usage: cvs-arch [-dh]\n".
	    "\t-d\tdisplay diagnostics output;\n".
	    "\t-h\tdisplay this help message and exit.\n");
}

sub savefile($ $)
{
	my ($fname, $msg) = @_;

	open(OUT, "> $fname") or die("Opening output file $fname: $!\n");
	print OUT join('', @{$msg});
	close(OUT);
}

sub safecat($ $ $)
{
	my ($dest, $tmp, $msg) = @_;

	# This really oughtta be done in a fork...
	open(SAFECAT, "| /usr/local/bin/safecat $tmp $dest")
	    or die("Opening the safecat pipe: $!\n");
	print SAFECAT join('', @{$msg});
	close(SAFECAT);
}

MAIN:
{
	my ($id, $x, $fileno);
	my @msg;
	my %opts;

	$ENV{'PATH'} = '/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin';
	getopts('dh', \%opts) or usage();
	usage() if (defined($opts{'h'}));
	$debug = 1 if (defined($opts{'d'}));

	# This could be done better, but oh well..
	@msg = <>;
	$id = msgid_fast(\@msg);
	die("Could not get the message ID: $!\n") unless (defined($id));
	chomp($id);
	debug("got message ID $id...");
	$x = checkdb($id);
	if (defined($x)) {
		print "Naaah, $id already present as $x\n";
	} else {
		# This is where we save the message to a file
		die("No FILENO variable\n") unless defined($ENV{'FILENO'});
		if ($ENV{'FILENO'} =~ /^(\d+)$/) {
			$fileno = $1;
		} else {
			die("Invalid FILENO value: $ENV{FILENO}\n");
		}

		# Pick one of the below output methods :)

		# Just display the message to stdout...
		# print join('', @msg);

		# Save it to a file...
		print "Saving $id as msg.$fileno\n";
		&savefile("msg.$fileno", \@msg);

		# Store it to a temp directory using safecat...
		# print "Storing $id into the temp dir\n";
		# &safecat('msg', 'tmp', \@msg);
	}
}

