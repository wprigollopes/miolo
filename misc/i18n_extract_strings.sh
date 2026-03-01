#!/bin/bash

TMP_FILES=/tmp/files.txt

function extract() {
    po=$1
    echo " Processing $po"
    if [ ! -f "$po" ]
    then
        echo "  Creating $po"
        touch "$po"
        OMIT_HEADER=''
    else
        echo "  Removing translation locations"
        grep -v ^# $po > /tmp/$po
        mv /tmp/$po $po
        OMIT_HEADER='--omit-header'
    fi
    echo "  Extracting strings"
    xgettext --from-code=ISO-8859-1 $OMIT_HEADER --no-wrap -j -s --keyword='_M:1' -Lphp -f $TMP_FILES -o $po

    if [ $(grep '^"Content-Type: .*charset=UTF-8' $po | wc -l) -gt 0 ]
    then
        echo "  Converting from UTF-8 to ISO-8859-1"
        iconv -f utf-8 -t iso-8859-1 $po > /tmp/$po
        sed -i 's/^\("Content-Type: .*charset=\)UTF-8/\1ISO-8859-1/' /tmp/$po
        mv /tmp/$po $po
    elif [ $(grep '^"Content-Type: .*charset=CHARSET' $po | wc -l) -ne 0 ]
    then
        echo "  Defining charset as ISO-8859-1"
        sed -i 's/^\("Content-Type: .*charset=\)CHARSET/\1ISO-8859-1/' $po
    fi
    mo=$(basename $po .po).mo
    echo "  Generating $mo"
    msgfmt -f $po -o $mo
}

OLD_PWD=$(pwd)
cd $(dirname $0)/../locale/pt_BR/LC_MESSAGES
echo "Entering $(pwd)"

# Extract strings from miolo (leave all modules out of this)
find ../../.. -type f | grep -v "/modules/" | egrep ".(class|inc|php)$" | sort > $TMP_FILES
po=miolo.po
extract $po

# Extract each module in a separated file
for DIR in $(find ../../../modules/ -maxdepth 1 -type d | grep -v "\.svn" | sed 1d)
do
    find $DIR -type f | egrep ".(class|inc|php)$" | sort > $TMP_FILES
    po=$(basename $DIR).po
    extract $po
done

echo "Leaving $(pwd)"
cd $OLD_PWD

