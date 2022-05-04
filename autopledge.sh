#!/bin/bash

if [ `stat --format=%Y /var/lib/prometheus/node-exporter/farcaster.prom` -ge $(( `date +%s` - 180 )) ]; then

sgfc=1599443409
curr_base_fee=$(/usr/local/bin/lotus chain head | xargs /usr/local/bin/lotus chain getblock | /usr/bin/jq -r .ParentBaseFee)

        echo " current pledge basefee is " $curr_base_fee
        if [[ "$sgfc" -gt "$curr_base_fee" ]]; then

        PC1s=$(cat /var/lib/prometheus/node-exporter/farcaster.prom | grep -v "worker_host" | grep -i -E "Commit1|addpiece" | grep -i -v failed | wc -l)

                if [ $PC1s -lt "8" ]; then
                # Uncomment this if you want to pledge with less then 8 PC1 tasks.
                # /usr/local/bin/lotus-miner sectors pledge
                   echo $PC1s > /var/www/html/status.html
                 else
                   echo "Already got $PC1s running"
                   echo $PC1s > /var/www/html/status.html
                fi

        else
                echo "Sorry no pledge basefee is to high"
             fi
        else
                echo "Sorry no recent prometheus file"                         
    fi
