# PHP Webdriver
- This project folder is to understand the basic usage of PHP webdriver to do automated testing on webpages
- Regression testing > this is to help to reduce time taken for testing current functions whenever there's new changes in coding
- Understand the power of [PHP Webdriver](https://github.com/php-webdriver/php-webdriver)
#
**Requirement**
1. [Chromium](https://chromium.woolyss.com/) (Recommended to use on 89.0.4389.0)
3. Laragon (required to use cURL and php)
4. PHP (Directory to put: C:\laragon\bin\php)
5. JAVA OpenJDK
6. [Chromedriver](https://chromedriver.chromium.org/downloads) (Recommended to use on 89.0.4389.23)
#
**NOTE** for opening chromedriver itself

`$port = mt_rand(49152, 65535);
 $args = ['--port=' . $port];`
 - Just comment out on line 32, paste the code from above on line 33
