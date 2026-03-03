#!/bin/bash

if [ "$1" = "" ]; then
  echo "Utilize: exec_recursive.sh \"comando\" [diretorio_raiz]"
  echo "Exemplo: ./exec_recursive.sh \"ls -lh\" /home/fulano/teste"
else
  if [ "$2" = "" ];then
     DIR_RAIZ="."
  else
     DIR_RAIZ=$2
  fi

  find $DIR_RAIZ -type f  | xargs $1
fi