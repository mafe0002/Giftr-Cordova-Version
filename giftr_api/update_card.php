<?php
// Updates the payment source on file for a customer
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
// If you're using Composer, use Composer's autoload
require_once('./vendor/autoload.php');

//require_once('./vendor/stripe/stripe-php/init.php');

// Use your test API key (switch to the live key later)
\Stripe\Stripe::setApiKey("sk_test_s1XUez1AEXbEm9k3rl7659N8");

$customer_id = "";
$flag = false;
$accounts = array("algo@algo.com"=>"cus_EGlFvzZmxm3ide", 
                  "sales@algorank.ca"=>"cus_EGM8NR10KvWt7G",
                  "jumpstartcdp@gmail.com"=>"cus_EHUUPKu41HHuY7",
                  "briksitmedical@comcast.net"=>"cus_EEZSBvxWbAOm52",
                  "scozub@sbcglobal.net"=>"cus_EDNLFKjJ7is7ms",
                  "thomasvincze@yahoo.com"=>"cus_EBZbv5TupUQOZu",
                  "fondrose@gmail.com"=>"cus_EBZaHvzhl0B479",
                  "president@pharmacy.ca"=>"cus_EBBzdiYSeLcbEk",
                  "atkinsoninspection@gmail.com"=>"cus_E4SU2gVnPmeJaq",
                  "mohawklock@yahoo.com"=>"cus_E4SPM4dMble7Zd",
                  "mcruz@rree.go.cr"=>"cus_DwEL5gn5Iah2kk",
                  "jan@janayres.com"=>"cus_DsjCCsye6mLzjS",
                  "letsbien@gmail.com"=>"cus_Dq56eeKpksUDXu",
                  "nickmjq@gmail.com"=>"cus_Dd0WRePqscM1jw",
                  "cmolson@pm.me"=>"cus_DOSQqKTr5MIbfw",
                  "janice.francisco@bridgepointeffect.com"=>"cus_DEzWvXvqjxYk8m",
                  "Treea1111@gmail.com"=>"cus_DDA1HpYoEeIFUm",
                  "toms@sovereignbuilt.ca"=>"cus_D38tvqu5g8vY5f",
                  "chriskiez@icloud.com"=>"cus_D0oKgNrTKOcgER",
                  "nickkay46@gmail.com"=>"cus_CvWn53800Ao7AF",
                  "rosekafanabo@yahoo.com"=>"cus_CuP0Q5eAN5mQV2",
                  "skidmk@acmadventures.com"=>"cus_Ct0TjYkcr4CL66",
                  "rdiotte@frecofluidpower.com"=>"cus_CsYi3Am350wUE9",
                  "george.hadjisophocleous@carleton.ca"=>"cus_CroIKsxvByJA1K",
                  "jamie@jamiechapman.ca"=>"cus_CrToLlYvNNufgx",
                  "christielocksmith@gmail.com"=>"cus_CrSsB1bZJDImgm",
                  "apa@magma.ca"=>"cus_CrSF75iZ6VfGLc",
                  "wrighttreeservice@live.ca"=>"cus_CrSEbtGWAADdL4",
                  "PaperSignMan@gmail.com"=>"cus_CrSDmWPlwBCRb7",
                  "barrhaven@rogers.com"=>"cus_CrSCnm4VpiRjzY",
                  "assystel@videotron.ca"=>"cus_CrSCzoYR6mlNAP",
                  "compact@sympatico.ca"=>"compact@sympatico.ca",
                  "ryan@recordstoredaycanada.ca"=>"cus_CrRf5gPYSj2aoR",
                  "ottawasuisha@gmail.com"=>"cus_CrRZ9JpbxutZNe",
                  "Joe"=>"43");

if ($_POST['email']){
    
    $emailVar = $_POST['email'];
    echo "Email: " .$emailVar . ". ";

        if($accounts[$emailVar]){
            echo "CustomerID: " . $accounts[$emailVar] . ". ";
            $customer_id = $accounts[$emailVar];
            $flag = true;
            }else{
                echo "Your email does not match our record. Please check for typo or contact us for assistance.";
                }

    }else{
        echo "Please enter your email below before clicking update card details. To confirm, we will ask you to enter your email again.";
}

if (isset($_POST['stripeToken'])&&($flag)){
	//echo "stripeToken is set";
  try {
    $cu = \Stripe\Customer::retrieve($customer_id); // stored in your application
    $cu->source = $_POST['stripeToken']; // obtained with Checkout
    $cu->save();

    $success = "Your card details have been updated!";
  }
  catch(\Stripe\Error\Card $e) {

    // Use the variable $error to save any errors
    // To be displayed to the customer later in the page
    $body = $e->getJsonBody();
    $err  = $body['error'];
    $error = $err['message'];
  }
  // Add additional error handling here as needed
} else {
	//echo "Token ain't working";
}
?>
