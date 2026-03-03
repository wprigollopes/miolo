#!/bin/bash

rm miolo_2.6_i386.deb
cd miolo
mkdir -p var/www
cd var/www
svn export https://svn.solis.coop.br/miolo/branches/2.6 miolo

cd miolo/etc
cp miolo.conf.dist miolo.conf
cd ../..

rm -r miolo/package

cd ../../..
cp -a miolo miolo_aux
find miolo_aux -name .svn | xargs rm -r
dpkg -b miolo_aux miolo_2.6_i386.deb
rm -r miolo_aux
rm -r miolo/var/
