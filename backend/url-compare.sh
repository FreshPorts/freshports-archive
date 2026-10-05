#!/bin/sh
#
# $Id: compare.sh,v 1.5 2005-08-03 12:18:34 dan Exp $
#
# compare the commits on two different websites
#

CONFIG=$1
if [ ! -f $1 ]
then
        echo "config.sh not found..."
        exit 1
fi

. $1

result=0

src1=$(mktemp /tmp/url-compare.XXXXXX)
src2=$(mktemp /tmp/url-compare.XXXXXX)

fetch -qo $src1 $SOURCE1 2>&1 > /dev/null
fetch -qo $src2 $SOURCE2 2>&1 > /dev/null

diff -ruN $src1 $src2 > diff.txt

NUMLINES=`cat diff.txt | wc -l`
#echo $NUMLINES
#echo "NUMLINES='$NUMLINES'"
if [ $NUMLINES -gt 0 ]
then
	FIRSTLINE=`head -1 diff.txt`
	THIRDLINE=`head -3 diff.txt | tail -1`
	#
	# no sense telling us that they are out by just one line.
	# that is usually just timing.
	#
	if [   "$FIRSTLINE" != "1d0"  -a  "$FIRSTLINE" != "0a1"  -o  "$THIRDLINE" != '150a150'  -a "$THIRDLINE" != '150d150' ]
	then
		# not a trivial diff
		echo diff.txt
		result=1
	else
		# ignoring
	fi
else
	echo "Same commits found"
fi

rm $src1 $src2

exit $result
