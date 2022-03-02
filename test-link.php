<?php

// An example of using php-webdriver.
// Do not forget to run composer install before. You must also have Selenium server started and listening on port 4444.

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

require_once('vendor/autoload.php');

// This is where Selenium server 2/3 listens by default. For Selenium 4, Chromedriver or Geckodriver, use http://localhost:4444/
$host = 'http://localhost:4444';

$capabilities = DesiredCapabilities::chrome();

$driver = RemoteWebDriver::create($host, $capabilities);

// navigate to Selenium page on Wikipedia
$driver->get('http://192.168.28.103/operator.php');

$getLogoutURL = $driver->findElement(WebDriverBy::xpath('//*[@id="login-form"]/center/div[5]/a/img'))->click();
sleep(2);

echo "Done reset captcha";

// terminate the session and close the browser
/*$driver->quit();*/