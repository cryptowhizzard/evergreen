These set of scripts are written by to help the #filecoin #slingshot-evergreen project to enable automation for retrievals.
Special thanks to @Ribasushi for his work done developping the #slingshot-evergreen program.

All scripts are Dual-licensed under MIT + Apache 2.0. If you use it and have impovements please do contact me on slack and/or create Pull requests.

To start using this you need Apache2, PHP-CLI PHP-CURL, MYSQL, php-mysql, Grafana and Lotus-farcaster running on your miner. You also need to be approved in the Evergreen program
in order to be authorised to retrieve deals from participants. (Please note to use UFW to limit access to your machine if you haven't already done so for the apache2 part.)

As my time is limited and these scripts are a "working" set of idea's -> Please use everything with caution and monitor. I will work on this over the next days/weeks to improve.

Please open and edit every file to check if you need to adjust settings. You need to fill in database details, proper paths and your minerID!

To download -> git clone https://github.com/cryptowhizzard/evergreen

The current set consists of :

init.sql ( Use this to create your database ).

Autopledge.sh. This script looks in your farcaster file how many PC1/AP is open and puts it in status.html. The following files use this info to decide
if they run or not ( to avoid overloading your setup ).

listen.php. ( Run this at the first time in a screen like /usr/bin/php listen.php ). When it works ok, cron it once in 24 hours. Ask / Read in the Slack channel 
regularly to check if this is appropiate. This script will fill your database with deals to be fetched.
local.php. Same as listen.php but then only for the deals valid on your own miner.

minercheck.php ( Run this regularly with cron to see if SP's are alive for retrieval ).
updatedownloaded.php ( Run this regularly with cron to make the database aware of the files you have downloaded ).

download.sh ( Run this in a screen in a While loop -> while true; do php download.sh; sleep 5; done ). Check the settings is this file to adjust how many
parallel downloads you want to have.

propose.php ( Run this regularly to make proposals of the things you have downloaded.

import.php ( Run this in a screen in a While loop -> while true; do php import.php; sleep 5; done ). It will import the car files downloaded into your miner.
Again, please adjust settings in this file to avoid overloading your system.

For any Questions feel free to contact me on the Filecoin Slack (wijnandschouten) or by e-mail ( info@dcent.nl ).
