#!/bin/sh

cd ~/FreshPorts-backend

fetch -qo production.txt http://www.freshports.org/backend/commits.php       2>&1 > /dev/null
fetch -qo test.txt       http://migration.freshports.org/backend/commits.php 2>&1 > /dev/null

diff test.txt production.txt > diff.txt

NUMLINES=`cat diff.txt | wc -l`
echo $NUMLINES
echo "NUMLINES='$NUMLINES'"
if [ $NUMLINES -gt 0 ]
then
	FIRSTLINE=`head -1 diff.txt`
	THIRDLINE=`head -3 diff.txt | tail -1`
	echo "\$FIRSTLINE='$FIRSTLINE'"
	echo "\$THIRDLINE='$THIRDLINE'"
	#
	# no sense telling us that they are out by just one line.
	# that is usually just timing.
	#
	if [   "$FIRSTLINE" != "1d0"  -a  "$FIRSTLINE" != "0a1"  -o  "$THIRDLINE" != '100a100'  -a "$THIRDLINE" != '100d100' ]
	then
		echo 'emailing'
		cat diff.txt | mail -s "FreshPorts diffs" dan@langille.org
	else
		echo 'ignoring'
	fi
fi
