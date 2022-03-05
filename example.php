<?php

// An example of using php-webdriver.
// Do not forget to run composer install before. You must also have Selenium server started and listening on port 4444.

namespace Facebook\WebDriver;

use Exception;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

require_once('vendor/autoload.php');

try {
    // This is where Selenium server 2/3 listens by default. For Selenium 4, Chromedriver or Geckodriver, use http://localhost:4444/
    // putenv('WEBDRIVER_CHROME_DRIVER=C:\Program Files\chrome-win\chromedriver.exe');
    // $host = 'http://localhost:4444/';

    $capabilities = DesiredCapabilities::chrome();

    // $driver = RemoteWebDriver::create($host, $capabilities);
    $driver = ChromeDriver::start($capabilities);

    $driver->get('https://www.youtube.com/');

    $driver->findElement(WebDriverBy::name('search_query')) // find search input element
        ->sendKeys('rickroll') // fill the search box
        ->submit(); // submit the whole form

    $driver->wait()->until(
        WebDriverExpectedCondition::elementTextContains(WebDriverBy::cssSelector('yt-formatted-string.ytd-video-renderer'), 'Rick Astley - Never Gonna Give You Up (Official Music Video)')
    );

    $findFirstVid = $driver->findElement(
        WebDriverBy::cssSelector('#video-title > yt-formatted-string')
    );
    $findFirstVid->click();

    // $skipAd = $driver->findElement(WebDriverBy::className('ytp-ad-skip-button-container'));
    // $skipAd->click();

    sleep(60);

    $driver->quit();
} catch(\Exception $e) {
    throw $e;
    throw new Exception('Error: ' . $e);
}