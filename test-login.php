<?php

// An example of using php-webdriver.
// Do not forget to run composer install before. You must also have Selenium server started and listening on port 4444.

namespace Facebook\WebDriver;

use Exception;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeDriver;

require_once('../vendor/autoload.php');

// This is where Selenium server 2/3 listens by default. For Selenium 4, Chromedriver or Geckodriver, use http://localhost:4444/
//$host = 'http://localhost:4444';

$capabilities = DesiredCapabilities::chrome();
//$driver = RemoteWebDriver::create($host, $capabilities);

// this one will start chromdriver itself
$driver = ChromeDriver::start($capabilities);

// navigate to Selenium page on Wikipedia
$driver->get('http://192.168.28.103/operator.php');

try {
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
    $driver->findElement(WebDriverBy::xpath("//a[contains(@class,'captcha-reload')]"))->click();
    sleep(2);

    // focus on captcha input box
    $driver->findElement(WebDriverBy::id('captcha'))->click();

    $loginTime = time();
    while(time() - $loginTime < 6 ){
        echo "Sleep for 3 seconds to wait for manual captcha input\n"; 
        sleep(3);

        // enter dummy 'captcha' into the captcha box
        // $driver->findElement(WebDriverBy::id('captcha')) // find search input element
        // ->sendKeys('1111'); // fill the search box

       $getCaptcha = $driver->findElement(WebDriverBy::id('captcha'))->getAttribute('value');

        // validation for captcha fully entered
        if (strlen(trim($getCaptcha)) == 4 && is_numeric(trim($getCaptcha))) {
            echo "Captcha entered: " .trim( $getCaptcha) . "\n";
            echo "Received Captcha\n";
            $driver->findElement(WebDriverBy::id('login'))->click();

            echo "Login in progress...\n";
            sleep(2);

            // to check error after clicked 'Login' button
            try {
                $isLoginError = $driver->findElement(WebDriverBy::xpath('//div[@id="error"]'))->getText();
                if(!empty($isLoginError)){
                    throw new Exception($isLoginError);
                }
            }catch (\Exception $e){
                if (!empty($isLoginError)){
                    throw new Exception($e);
                }else{
                    break;
                }
            }
        }
    }

    // validation timeout after captcha entered or left empty
    if (strlen(trim($getCaptcha)) < 4 && strlen(trim($getCaptcha)) > 0) {
        echo "Captcha entered: " .trim($getCaptcha) . "\n";
        throw new Exception("Captcha is invalid!");
    }
    elseif (strlen(trim($getCaptcha)) == 0) {
        throw new Exception("Captcha is empty!");
    }

    // switch back into default frame
    $driver->switchTo()->defaultContent();

    // find and switch the frame due to homepage having frame wrapping
    $my_frame = $driver->findElement(WebDriverBy::xpath("//frame[@id='contentframe']"));
    $driver->switchTo()->frame($my_frame);

    try{
        // to check whether able to find 'Logout' button in homepage after login
        $checkHomepage = $driver->wait(3,250)->until(WebDriverExpectedCondition::elementTextContains(
        WebDriverBy::xpath('//a[text()="Logout"]'), 'Logout'));

        if ($checkHomepage > 0){
            echo 'Login successfully!!';
            // terminate the session and close the browser
            $driver->quit();
        }
    }catch(\Exception $e){
        throw new Exception('Logout button not found');
    }

} catch (\Exception $e) {
    echo "[" . date_default_timezone_get() . ", " . date("l") . ", " . 
    date("Y-m-d h:i:sa") . '] Error - ' . $e->getMessage() . "\n";
    $driver->quit();
}

?>