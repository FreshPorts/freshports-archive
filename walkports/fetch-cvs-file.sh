#!/bin/sh

if  [ $# -ne 3 ];
 then echo $0 : usage $0 CATEG PORT FILE 1>&2
 exit 1
else
 CATEG=$1
 PORT=$2
 FILE=$3

 FETCHFILE=/usr/ports/$CATEG/$PORT/$FILE

 fetch -b -o $FETCHFILE.1 http://www.freebsd.org/cgi/cvsweb.cgi/ports/$CATEG/$PORT/$FILE
>/dev/null 2>&1
 if [ $? -ne 0 -o ! -f $FETCHFILE.1 ];
  then 
     echo $0 : Download failure 1>&2
     exit 2
  else
  REV=`awk -Frev '/<a NAME="/ {print $2+0;exit}'<$FETCHFILE.1`
  echo latest ver is $REV

#  rm $FETCHFILE.1 2>/dev/null
  
  fetch -b -o $FETCHFILE http://www.freebsd.org/cgi/cvsweb.cgi/ports/$CATEG/$PORT/$FILE?rev=$REV
  exit $?
 fi
fi

