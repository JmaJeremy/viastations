#!/usr/bin/python3
import xml.etree.ElementTree as ET
import sqlite3
from datetime import datetime

date = datetime.date(datetime.now())

conn = sqlite3.connect('stations.db')
c = conn.cursor()

tree = ET.parse('/var/www/html/viastation/archive/latest/StationSchedule.xml')
root = tree.getroot()

for child in root:
    sql = "INSERT OR REPLACE INTO stations(code, name, date) VALUES(\"%s\", \"%s\", \"%s\")" % (child[2].text, child[1].text, date)
#    print(sql)
    c.execute(sql)

conn.commit()
conn.close()
