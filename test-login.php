<?php

// An example of using php-webdriver.
// Do not forget to run composer install before. You must also have Selenium server started and listening on port 4444.

namespace Facebook\WebDriver;

use Exception;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeDriver;

require_once('vendor/autoload.php');

try {
    // This is where Selenium server 2/3 listens by default. For Selenium 4, Chromedriver or Geckodriver, use http://localhost:4444/
    //$host = 'http://localhost:4444';

    $capabilities = DesiredCapabilities::chrome();
    //$driver = RemoteWebDriver::create($host, $capabilities);

    // this one will start chromdriver itself
    $driver = ChromeDriver::start($capabilities);

    // navigate to Selenium page on Wikipedia
    $driver->get('http://192.168.28.103/operator.php');

    // write 'username' in the search box
    $driver->findElement(WebDriverBy::id('username')) // find search input element
        ->sendKeys('operatora'); // fill the search box

    echo " Entered username\n";
    sleep(1);

    // write 'password' in the search box
    $driver->findElement(WebDriverBy::id('clrpasswd')) // find search input element
        ->sendKeys('asdf1234'); // fill the search box

    echo " Entered password\n";  

    // click to reload captcha
    $driver->findElement(WebDriverBy::xpath('//*[@id="login-form"]/center/div[5]/a/img'))->click();
    sleep(2);

    // focus on captcha input box
    $driver->findElement(WebDriverBy::id('captcha'))->click();
    sleep(1);

    $loginTime = time();
    while(time() - $loginTime < 6 ){
        echo "Sleep for 3 seconds to wait for manual captcha input\n"; 
        sleep(3);

        $getCaptcha = $driver->findElement(WebDriverBy::id('captcha'))->getAttribute('value');
        // $invalidCaptchaMsg = strcmp($driver->findElement(WebDriverBy::id('error'))->getText(),
        // 'Verification code entered is invalid!');
        // echo $invalidCaptchaMsg . "\n";
        // if ($invalidCaptchaMsg !== false){
        //     throw new Exception($invalidCaptchaMsg);
        // }

        if (strlen(trim($getCaptcha)) == 4) {
            echo "Captcha entered: " . $getCaptcha . "\n";
            echo "Received Captcha\n";
            $driver->findElement(WebDriverBy::id('login'))->click();
            sleep(1);

            // $invalidCaptchaText = $driver->findElement(WebDriverBy::id('error'))
            //     ->getText();

            // if (strlen(trim($invalidCaptchaText)) > 0){
            //     echo $invalidCaptchaText . " \n";
            // }

            echo "Login in progress...\n";
            break;
        }
    }

    if (strlen(trim($getCaptcha)) < 4 && strlen(trim($getCaptcha)) != 0) {
        echo "Captcha entered: " . $getCaptcha . "\n";
        throw new Exception("Captcha is invalid!\n");

        // terminate the session and close the browser
        $driver->quit();
    }
    elseif (strlen(trim($getCaptcha)) == 0) {
        throw new Exception("Captcha is empty!\n");
        // terminate the session and close the browser
        $driver->quit();
    }

    // find and switch the frame due to homepage having frame wrapping
    $my_frame = $driver->findElement(WebDriverBy::id('contentframe'));
    $driver->switchTo()->frame($my_frame);

    $checkHomepage = $driver->wait(3,250)->until(WebDriverExpectedCondition:: elementTextContains(
            WebDriverBy::xpath('//a[text()="Logout"]'), 'Logout'));

    if ($checkHomepage > 0){
        echo $checkHomepage . " \n";
        echo 'Login successfully!';
        // terminate the session and close the browser
        $driver->quit();
    }elseif ($checkHomepage = 0){
        throw new Exception("Element of 'Logout' not found!\n");
    }

} catch (\Exception $e) {
    echo 'Error - ' . $e->getMessage() . "\n";
    $driver->quit();
}

?>