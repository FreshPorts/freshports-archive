#!/bin/sh

if [ ! -f config.sh ]
then
        echo "config.sh not found..."
        exit 1
fi

. config.sh

fetch -qo source1.txt $SOURCE1 2>&1 > /dev/null
fetch -qo source1.txt $SOURCE2 2>&1 > /dev/null

diff source1.txt source1.txt > diff.txt

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
		cat diff.txt | mail -s $SUBJECT dan@langille.org
	else
		echo 'ignoring'
	fi
fi
