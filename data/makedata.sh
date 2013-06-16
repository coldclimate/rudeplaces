#!/bin/bash
set -e
echo "Starting download (may take some time"
wget http://download.geonames.org/export/dump/allCountries.zip
unzip allCountries.zip
for i in `cat rude.txt`; do echo ${i}; grep -wi ${i} allCountries.txt |awk -F $'\t' '{ print "{\42name\42:\42"$2"\42, \42loc\42:{\42lat\42:"$5", \42long\42:"$6"}}" }' >> import.js; done
mongoimport --db rude --collection places --file import.js
mongo places --eval 'db.places.ensureIndex( { loc : "2d" } )'
