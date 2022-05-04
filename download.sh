#!/bin/bash

#just go and find or download as much carfiles as we can

cd /root/evergreen/get
#find . -name \*.sh -exec {} \;

for file in $(find . -name "f0*.sh")
do
	   if [ "$(ps aux | grep retrieve | wc -l)" -gt 30 ]; then

	           echo "Waiting, 30 open"
	           sleep 2
		    else
	           echo "Continiue"
		   $file
		   rm $file
 		fi
   done



