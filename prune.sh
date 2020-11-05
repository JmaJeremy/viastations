#!/bin/bash

deldate=`date -d -1hour +%Y%m%d%H`
rm -r /var/www/html/viastation/archive/$deldate*
rm -f /etc/logstash/sincedbs/via-*
rm -f /etc/logstash/file_completed/via-*
