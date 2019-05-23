### abstruct
snipet for getting data by oauth2
(this repo is using strava for example.)

### what you need
php (>= 5.4), composer

### get it started
1. ```composer install```
1. change index.php to your client id
1. echo ```<your client secret>``` > secret/clientSecret.data
1. ```sudo CLIENT_SECRET=`cat secret/clientSecret.data` php -S 127.0.0.1:80```
1. access http://127.0.0.1
1. get data!!!
