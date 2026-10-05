# $Id: config.pm,v 1.2 2001-11-09 18:49:37 dan Exp $
#

package FreshPorts::Config;

$FreshPorts::Config::scriptpath		= "/home/dan/src/dev";

$FreshPorts::Config::dbname			= 'FreshPorts2Test';
$FreshPorts::Config::user			= 'dan';
$FreshPorts::Config::password		= '';

$FreshPorts::Config::path_to_ports	= '/home/lists-test/ports';	# path to ports tree
$FreshPorts::Config::ports_prefix	= 'ports';			# where in the cvs tree are ports?

1;
