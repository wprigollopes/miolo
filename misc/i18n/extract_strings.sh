#!/bin/bash

SOURCE_DIR=../../
DEST_DIR=./

function addHTMLEntities() {
    FILE="$1"
    TMP_FILE="/tmp/1523tmp"

    echo " Adding HTML entities to file $FILE..."

    # acento circunflexo
    sed "s/Ã¢/\&acirc;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ãª/\&ecirc;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã´/\&ocirc;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã/\&Acirc;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã/\&Ecirc;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã/\&Ocirc;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    # til
    sed "s/Ã£/\&atilde;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ãµ/\&otilde;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã/\&Atilde;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã/\&Otilde;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    # acento agudo
    sed "s/Ã¡/\&aacute;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã©/\&eacute;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã­/\&iacute;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã³/\&oacute;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ãº/\&uacute;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã/\&Aacute;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã/\&Eacute;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã/\&Iacute;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã/\&Oacute;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã/\&Uacute;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    # crase, cedilha e trema
    sed "s/Ã /\&agrave;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã§/\&ccedil;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã¼/\&uuml;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã¶/\&ouml;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã¯/\&iuml;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã/\&Agrave;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã/\&Ccedil;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã/\&Uuml;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã/\&Ouml;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Ã/\&Iuml;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE

    # caracteres especiais
    sed "s/Âª/\&ordf;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/Âº/\&ordm;/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
}

function removeHTMLEntities() {
    FILE="$1"
    TMP_FILE="/tmp/1523tmp"

    echo " Removing HTML entities from file $FILE..."

    # acento circunflexo
    sed "s/\&acirc;/Ã¢/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&ecirc;/Ãª/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&ocirc;/Ã´/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&Acirc;/Ã/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&Ecirc;/Ã/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&Ocirc;/Ã/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    # til
    sed "s/\&atilde;/Ã£/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&otilde;/Ãµ/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&Atilde;/Ã/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&Otilde;/Ã/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    # acento agudo
    sed "s/\&aacute;/Ã¡/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&eacute;/Ã©/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&iacute;/Ã­/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&oacute;/Ã³/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&uacute;/Ãº/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&Aacute;/Ã/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&Eacute;/Ã/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&Iacute;/Ã/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&Oacute;/Ã/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&Uacute;/Ã/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    # crase, cedilha e trema
    sed "s/\&agrave;/Ã /g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&ccedil;/Ã§/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&uuml;/Ã¼/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&ouml;/Ã¶/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&iuml;/Ã¯/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&Agrave;/Ã/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&Ccedil;/Ã/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&Uuml;/Ã/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&Ouml;/Ã/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&Iuml;/Ã/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE

    # caracteres especiais
    sed "s/\&ordf;/Âª/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE
    sed "s/\&ordm;/Âº/g" $FILE > $TMP_FILE
    mv $TMP_FILE $FILE

}

#FIXME:
mv miolo.po classes.po

DIRS="classes modules/admin modules/example modules/generator modules/common"

if [ $1 ]
then
	DIRS=$1
fi
for DIR in $DIRS
do
    find $SOURCE_DIR/$DIR -type f | grep ".class.php$\|.inc.php$" > $DEST_DIR/files.txt

    if echo $DIR | grep '/'
    then
	    DIR=$(echo $DIR | cut -f2- -d/)
    fi

    OUT=$DEST_DIR/$DIR.po
    echo "Generating $OUT..."
    if [ ! -f $DEST_DIR/$OUT ]
    then
        echo " Creating file $OUT..."
        touch $OUT
        unset OMIT_HEADER
    else
        OMIT_HEADER="--omit-header"
    fi

    echo " Removing comments to regenerate them again..."
    grep -v "^#: " $OUT > /tmp/45633tmpfile
    mv /tmp/45633tmpfile $OUT
    
    addHTMLEntities $OUT
    echo " Extracting additional strings from files..."
    xgettext --from-code=ISO-8859-1 $OMIT_HEADER --no-wrap -j -s --keyword='_M:1' -Lphp -f $DEST_DIR/files.txt -o $OUT
    removeHTMLEntities $OUT

    rm $DEST_DIR/files.txt
done

#FIXME
mv classes.po miolo.po
