#!/usr/bin/perl
#
# $Id: port.pm,v 1.1 2001-11-06 19:21:09 dan Exp $
#

package FreshPorts::Port;
require Exporter;
require	config;

use strict;
use config;

# =================================

sub _initialize {
}

# =================================

sub new {
	my $this		= {};
	my $class		= shift;

	$this->{dbh}	= shift;

	bless $this;
	$this->_initialize();
	return $this;
}

sub save {
	my $this = shift;

	print "into FreshPorts::Port::save\n";

	#
	# to save, element_id and category_id must be valid
	# 

	my $dbh = $this->{dbh}; # just a short cut...
	my $sth;
	my $sql;
	my @row;

	if ($this->{id}) {
		# we are updating

# correct this sql
		$sql = "update ports  \
				set \
				is_primary = " . $dbh->quote($this->{is_primary}) . ", \
				element_id = $this->{element_id}, \
				name      = " . $dbh->quote($this->{name}) . ", \
				description = " . $dbh->quote($this->{description}) . " \
				 where id = $this->{id}";
		$sth = $this->{dbh}->prepare($sql);
		$sth->execute ||
			die "Could not execute SQL $sql ... maybe invalid?";
	} else {
		# we are inserting
		$sql = "select CreatePort(" . $dbh->quote($this->{element_id}) . ", \
				" . $dbh->quote($this->{category_id}) . ")";

		print "sql is $sql\n";

		$sth = $this->{dbh}->prepare($sql);
		$sth->execute ||
			die "Could not execute SQL $sql ... maybe invalid?";

		@row = $sth->fetchrow_array();

		$sth->finish();

		$this->{id} = $row[0];

	}

	# after saving, return the ID
	return $this->{id};
}

sub FetchByID {
	my $this	= shift;

	my $dbh;
	my $sql;
	my $sth;
	my $row;

	$dbh		= $this->{dbh};

	$sql = "select * from ports where id = $this->{id}";
	print "sql = '$sql'\n";

	$sth = $dbh->prepare($sql);
	if (!$sth->execute) {
		Sys::Syslog::syslog('warning', "Could not execute SQL $sql");
		die "Could not execute SQL $sql ... maybe invalid?";
	}

	$row = $sth->fetchrow_hashref();

	$sth->finish();

	$this->{id} 			= $row->{id};
	$this->{element_id}		= $row->{element_id};
	$this->{category_id}	= $row->{category_id};

	print "found id = $this->{id}\n";

	return $this->{id};
}

sub FetchByName {
	# obtain the element based on the pathname supplied
	my $this	= shift;

	my $dbh;
	my $sql;
	my $sth;
	my $row;
	my $tmp;

	$dbh		= $this->{dbh};
	if (!$dbh) {
		die " no database handle!";
	}

	$tmp = $dbh->quote($this->{name});
	$sql = "select * from ports where name = $tmp";
	print "sql = '$sql'\n";

	$sth = $dbh->prepare($sql);
	if (!$sth->execute) {
		Sys::Syslog::syslog('warning', "Could not execute SQL $sql");
		die "Could not execute SQL $sql ... maybe invalid?";
	}

	$row = $sth->fetchrow_hashref();

	$sth->finish();

	$this->{id} 			= $row->{id};
	$this->{element_id}		= $row->{element_id};
	$this->{category_id}	= $row->{category_id};

	print "found id = $this->{id}\n";

	return $this->{id};
}

1;