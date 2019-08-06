#!/bin/bash

LINKS=(https://tsimobile.viarail.ca/data/StationSchedule.xml
https://tsimobile.viarail.ca/data/allData.json
https://tsimobile.viarail.ca/data/allDataCIS.json
https://tsimobile.viarail.ca/data/coordinates.json
https://tsimobile.viarail.ca/data/coordinates.xml
https://tsimobile.viarail.ca/data/coordinatesTSI.json
https://tsimobile.viarail.ca/data/coordinatesWitronix.xml
https://tsimobile.viarail.ca/data/vehiculesLookup.csv)

timestamp=`date +%Y%m%d%H%M%S`
mkdir /var/www/html/viastation/archive/$timestamp

for i in ${LINKS[@]}; do
	filename=${i##*/}
	wget -O /var/www/html/viastation/archive/$timestamp/$filename $i
done

rm /var/www/html/viastation/archive/latest
ln -s /var/www/html/viastation/archive/$timestamp /var/www/html/viastation/archive/latest

tar -C /var/www/html/viastation/archive/$timestamp -czvf /var/www/html/viastation/archive/$timestamp.tar.gz .
aws s3 cp /var/www/html/viastation/archive/$timestamp.tar.gz s3://via-backups/

exit 0
