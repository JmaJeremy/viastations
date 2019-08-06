#!/bin/bash

deldate=`date -d -1hour +%Y%m%d%H`
rm -r /var/www/html/viastation/archive/$deldate*
