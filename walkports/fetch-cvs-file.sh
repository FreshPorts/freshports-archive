#!/bin/sh

if  [ $# -ne 3 ];
 then echo $0 : usage $0 CATEG PORT FILE 1>&2
 exit 1
else
 CATEG=$1
 PORT=$2
 FILE=$3

 if [ ! -d /usr/ports/${CATEG}/${PORT} ]
 then
    echo about to create /usr/ports/${CATEG}/${PORT}
    mkdir /usr/ports/${CATEG}/${PORT}
    if [ $? -eq 0 ]
    then
       mkdir /usr/ports/${CATEG}/${PORT}/pkg
       if [ $? -ne 0 ]
       then
          exit 2
       fi
    else
       exit 3
    fi
 fi

 FETCHFILE=/usr/ports/$CATEG/$PORT/$FILE

 fetch -b -o $FETCHFILE.1 http://www.freebsd.org/cgi/cvsweb.cgi/ports/$CATEG/$PORT/$FILE
>/dev/null 2>&1
 if [ $? -ne 0 -o ! -f $FETCHFILE.1 ];
  then 
     echo $0 : Download failure 1>&2
     exit 4
  else
  REV=`awk -Frev '/<a NAME="/ { gsub("\".*$","",$2);print $2;exit}' $FETCHFILE.1`
  echo latest ver is $REV

  rm $FETCHFILE.1 2>/dev/null
  
  fetch -b -o $FETCHFILE http://www.freebsd.org/cgi/cvsweb.cgi/ports/$CATEG/$PORT/$FILE?rev=$REV
  exit $?
 fi
fi

