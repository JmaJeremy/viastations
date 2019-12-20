#!/usr/bin/python3
import xml.etree.ElementTree as ET
import sqlite3
from datetime import datetime
import csv

date = datetime.date(datetime.now())

conn = sqlite3.connect('stations.db')
c = conn.cursor()

tree = ET.parse('/var/www/html/viastation/archive/latest/StationSchedule.xml')
root = tree.getroot()

for child in root:
    with open('stops.txt') as csvfile:
        stops = csv.reader(csvfile)
        for stop in stops:
            if child[2].text == stop[1].upper():
                timezone = stop[6]
    sql = "INSERT OR REPLACE INTO stations(code, name, date, timezone) VALUES(\"%s\", \"%s\", \"%s\", \"%s\")" % (child[2].text, child[1].text, date, timezone)
#    print(sql)
    c.execute(sql)

conn.commit()
conn.close()
