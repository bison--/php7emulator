# php7emulator

TL;DR: MAKE PHP5 CODES WORK AGAIN!

You have an old project that can't be changed easily to work with PHP7?  
GREAT! We have a few of them at work but I couldnt find anything like this on the whole Internet. 
So here is a wrapper for ~~all~~ some depricated and removed functions to make your PHP5 work on PHP7.  

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LZ89S5B4T4QSS)


## Installation

At first you will need all the things PHP7 has not onboard on a Ubuntu 16.04 by default.

```sh
apt-get install php-apcu php-xml php-mbstring
```
* The APC was removed and you have to install php-apcu
* In case you have done something with __DOMDocument__ or similar functions you will need php-xml
* Yes, multibyte strings are an extra package php-mbstring

To use it in your project just import it
```php
require_once('php7emulator.php');
```
In case you have some servers running PHP5 and PHP7 and you need compatibility, just check for an removed function like this:
```php
if (!function_exists('mysql_connect'))
{
    require_once(RAWBASEDIR.'/application/helper/php7emulator.php');
}
```


## FATAL ERROR

1. In case of __FATAL ERROR__, go to your error.log, find the missing function and write a wrapper for it.  
2. Repeat 1. until there are no __FATAL ERRORs__.


## TODO

* there are a LOT of missing functions, CATCH EM ALL!
* better Doku?


## License

MIT

**Free Software, Hell Yeah!**


## Other

* thanks to my boss who allowed me to publish it :)
* Blogpost: TBD
* [Twitter](https://twitter.com/bison_42)
