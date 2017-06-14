<?php
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// 
// Initialize variables
//  
//  Here we set the default values that we want our form to display

$debug = false;

if(isset($_GET["debug"])){
    $debug = true;
}

if ($debug) print "<p>DEBUG MODE IS ON</p>";

//
//  CHANGES NEEDED: create variable for each form element
//                  set your default values

$firstName="Kelly";
$email="kkrawczy@uvm.edu";
$hiking = true;
$kayaking = false;
$gender = "Male";
$mountain="Small";

// this would be the full url of your form
$yourURL = "http://www.uvm.edu/~kkrawczy/cs008/assignment5.1/form.php";


//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
// 
// initialize flags for errors, one for each item
//
// CHANGES NEEDED: create variable for each form element that we can check
// use same variable name as above and add ERROR (just a good naming convention
//

$firstNameERROR = false;
$emailERROR = false;


$mailed = false;
$message = "Thank you for joining Andy's Pizzeria's insider email list.";



//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// 
// This if statement is how we can check to see if the form has been submitted
// 
// NO CHANGES: but VERFIY your forms submit button is named btnSubmit

if (isset($_POST["btnSubmit"])){

    //******************************************************************
    // is the refeering web page the one we want or is someone trying 
    // to hack in. this is not 100% reliable but ok for our purposes   */
    //
    // Security check block one, no changes needed
    
    $fromPage = getenv("http_referer"); 

    if ($debug) print "<p>From: " . $fromPage . " should match yourUrl: " . $yourURL;

    if($fromPage != $yourURL){
        die("<p>Sorry you cannot access this page. Security breach detected and reported</p>");
    } 
    
    
    //************************************************************
    // we need to make sure there is no malicious code so we do 
    // this for each element we pass in. Be sure your names match
    // your objects
    // 
    // Security check block two
    // 
    // What this does is take things like <script> and replace it
    // with &lt;script&gt; so that hackers cannot send malicous 
    // code to you.
    //   
    // You will notice i have set radio buttons, list box and the 
    // check boxes just in case someone tries something funky.
    // 
    // CHANGES NEEDED: match PHP variables with form elements
    // 
    // */
    
    $firstName = htmlentities($_POST["txtFirstName"],ENT_QUOTES,"UTF-8");
    $email = htmlentities($_POST["txtEmail"],ENT_QUOTES,"UTF-8");
    
    
    if(isset($_POST["chkMeat"])) {
        $hiking  = true;
    }else{
        $hiking  = false;
    }
    
    if(isset($_POST["chkVeggies"])) {
        $kayaking  = true;
    }else{
        $kayaking  = false;
    }

    $gender = htmlentities($_POST["radGender"],ENT_QUOTES,"UTF-8");
    $mountain = htmlentities($_POST["lstSize"],ENT_QUOTES,"UTF-8");
    
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    // 
    // Begin looking for mistakes after we have done the above security checks
    //
    // the error msg array is going to be used to hold our mistakes if there 
    // are any. the array can expand to hold as much as we need.
    // CHANGES NEEDED: 
    //                 Be sure to create the file: validation_functions.php
    // 
    
    
    include ("validation_functions.php"); // you need to create this file (see link in lecture notes)
    
    $errorMsg=array();
    
    //#######################################################
    // we are going to put our forms data into this array so we can save it
    // NO CHANGES NEEDED
    $dataRecord=array();
    
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    // 
    // Now start checking each one. I am only doing one here but the IF block
    // is pretty important as it gives you the structure of what to to do.
    //
    // CHANGES NEEDED: 
    //                 Be sure you change $firstName to match your variables 
    //                 The IF block would be copied for each item you are
    //                 checking.
    //                 You would need to change the second IF to refelct the
    //                 function or condition you are checking for
    //    
    
    // Test first name for empty and valid characters
    if(empty($firstName)){
       $errorMsg[]="Please enter your First Name";
       $firstNameERROR = true;
    } else {
       $valid = verifyAlphaNum ($firstName); /* test for non-valid  data */
       if (!$valid){ 
           $errorMsg[]="First Name must be letters and numbers, spaces, dashes and single quotes only.";
           $firstNameERROR = true;
       }
    }

    //#######################################################
    // we are going to put our forms data into this array so we can save it
    // CHANGES NEEDED make sure the variable matches yours above
    $dataRecord[]=$firstName;
    
    // @@@@@ keep checking other inputs here
    
    // ie Check email
    
    // ie check misc
    
    
    //#######################################################
    // We need to save all the values for the other object 
    // types. 
    
    // example for check boxes if it is set i want to save
    // the word Hiking which is the value on my check box
    // I send it through html entities to be safe
    if(isset($_POST["chkHiking"])) {
        $dataRecord[]= htmlentities($_POST["chkHiking"],ENT_QUOTES,"UTF-8");
    }else{
        $dataRecord[]=""; 
    }

    if(isset($_POST["chkKayaking"])) {
        $dataRecord[]= htmlentities($_POST["chkKayaking"],ENT_QUOTES,"UTF-8"); 
    }else{
        $dataRecord[]=""; 
    }

    // example radio button
    $dataRecord[]=$gender;

    // example for a list box
    $dataRecord[]=$mountain;

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    // 
    // our form data is valid at this point so we can process the data
    if(!$errorMsg){	
        if ($debug) print "<p>Form is valid</p>";
        
        //####################################################################
        //
        // Begin processing data
        
        
        //####################################################################
        // Save Forms data to a csv file on the cloud
        
        // NOTE: When you save the forms information to a file, the file 
        // permissions can cause problems

        
        // Step one in netbeans create new file, name it formData.csv
        // Step two delete the contents of this csv file and save it
        // Step three use fugu or winscp to set the permissions on this
        //            file to 666 (rw-  for everyone)
        // Now try your form and see if it saves.
        
        $fileExt=".csv";
        $filename= "formData". $fileExt;
        
        if ($debug) {
            print "\n\n<p>Filename is $filename";
            print "\n\n<p>Webspace is $webspace";
            print "\n\n<p>Script filename is " . $_SERVER['SCRIPT_FILENAME'];
            print "<pre>";
            print_r($findYourUsername);
            print "</pre>";
            print "\n\n<p>findYourUsername 4 is " . $findYourUsername[4];
            print "\n\n<p>file ext is $fileExt";
        }
        
        
        // now we just open the file for append
        $file = fopen($filename, 'a');    

        // write the forms informations
        fputcsv($file, $dataRecord);

        // close the file
        fclose($file);

        //####################################################################

    
        //************************************************************
        //
        //  In this block I am just putting all the forms information
        //  into a variable so I can print it out on the screen
        //
        //  the substr function removes the 3 letter prefix
        //  preg_split('/(?=[A-Z])/',$str) add a space for the camel case
        //  see: http://stackoverflow.com/questions/4519739/split-camelcase-word-into-words-with-php-preg-match-regular-expression
        //
        //  CHANGES: first message line. foreach no changes needed

        $message  = "<h2>Thank you for registering for Andy's Pizzeria's insider email list.</h2>";

        foreach ($_POST as $key => $value){
            $message .= "<p>"; 

            $camelCase = preg_split('/(?=[A-Z])/',substr($key,3));

            foreach ($camelCase as $one){
                $message .= $one . " ";
            }
            $message .= " = " . $value . "</p>";
        }
        
        //$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
        //
        // since I have all the forms information i am going to mail it
        //
        include_once('mailMessage.php');
        $mailed = sendMail($email, $message);
                  
    } // no errors      
} // ends if form was submitted. We will be adding more information ABOVE this

?>
<!DOCTYPE html>
<html lang="en">
<head>

<title>Registration For Insider Deals at Andy's Pizzeria</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="author" content="Kelly Krawczyk">

<meta name='description' content="Sign up to get great deals on the best pizza in DC.">

<link rel="stylesheet"
href="style.css"
type="text/css"
media="screen">

</head>
<body id="form">
<? 
//*****************************************************************************
//
//  In this block  display the information that was submitted and do not 
//  display the form.
//  
//  @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//  NO CHANGES NEEDED but the if condition is different than last time
//
if (isset($_POST["btnSubmit"])AND empty($errorMsg)){  // closing of if marked with: end body submit
    //$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
    print "<h1>Your Request has ";

    if (!$mailed) {
        echo "not ";
    }
    
    echo "been processed</h1>";

    print "<p>A copy of this message has ";
    if (!$mailed) {
        echo "not ";
    }
    print "been sent</p>";
    print "<p>To: " . $email . "</p>";
    print "<p>Congratulations! You are now a registered customer of Andy's Pizzeria.  You will receive a confirmation email shortly.</p>";
    echo $message;
    
} else {

// display the form, notice the closing } bracket at the end just before the 
// closing body tag
 
    
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// Here we display any errors that were on the form
//
    
print '<div id="errors">';

if($errorMsg){
    echo "<ol>\n";
    foreach($errorMsg as $err){
        echo "<li>" . $err . "</li>\n";
    }
    echo "</ol>\n";
} 

print '</div>';

?>

    <!-- notice we are sending the form to itself 
    
    Notice each input tag has a php echo that prints out our variable. 
    This is how we set the default values for each element
    
    CHANGES NEEDED MAke sure the variable names match the variables you 
    Initialized on the first few lines -->
    
<form action="<? print $_SERVER['PHP_SELF']; ?>" 
      method="post"
      id="frmRegister">
			
<fieldset class="wrapper">
  <legend>Register Today</legend>
  <p>Please fill out the following registration form. Required fields <span class='required'></span>.</p>

<fieldset class="intro">
<legend>Please complete the following form</legend>

<fieldset class="contact">
<legend>Contact Information</legend>					
	<label for="txtFirstName" class="required">First Name</label>
  	<input type="text" id="txtFirstName" name="txtFirstName" 
               <?php if($firstNameERROR) echo 'class="mistake"'; ?>
               value="<?php echo $firstName; ?>" 
    		tabindex="100" maxlength="25" placeholder="enter your first name" autofocus onfocus="this.select();" >
				
	<label for="txtEmail" class="required">Email</label>
  	<input type="email" id="txtEmail" name="txtEmail" 
               <?php if($emailERROR) echo 'class="mistake"'; ?>
               value="<?php echo $email; ?>"
    		tabindex="110" maxlength="45" placeholder="enter a valid email address" onfocus="this.select();" >
        
        

</fieldset>					

<fieldset class="checkbox">
	<legend>Do you like (check all that apply):</legend>
  	<label><input type="checkbox" id="chkHiking" name="chkMeat" value="Meat" tabindex="221" 
			<?php if($hiking) echo ' checked="checked" ';?>>Meat</label>
            
	<label><input type="checkbox" id="chkKayaking" name="chkVeggies" value="Veggies" tabindex="223" 
			<?php if($kayaking) echo ' checked="checked" ';?>>Veggies</label>
</fieldset>

<fieldset class="radio">
	<legend>What is your gender?</legend>
	<label><input type="radio" id="radGenderMale" name="radGender" value="Male" tabindex="231" 
			<?php if($gender=="Male") echo ' checked="checked" ';?>>Male</label>
            
	<label><input type="radio" id="radGenderFemale" name="radGender" value="Female" tabindex="233" 
			<?php if($gender=="Female") echo ' checked="checked" ';?>>Female</label>
</fieldset>

<fieldset class="lists">	
	<legend>Select your size:</legend>
	<select id="lstMountains" name="lstSize" tabindex="281" size="1">
		<option value="Small" <?php if($mountain=="Small") echo ' selected="selected" ';?>>Small</option>
		<option value="Medium" <?php if($mountain=="Medium") echo ' selected="selected" ';?>>Medium</option>
		<option value="Large" <?php if($mountain=="Large") echo ' selected="selected" ';?>>Large</option>
	</select>
</fieldset>

<fieldset class="buttons">
	<legend></legend>				
	<input type="submit" id="btnSubmit" name="btnSubmit" value="Register" tabindex="991" class="button">

	<input type="reset" id="butReset" name="butReset" value="Reset Form" tabindex="993" class="button" onclick="reSetForm();" >
</fieldset>					

</fieldset>
</fieldset>
</form>
<?php
} // end body submit NO CHANGE NEEDED
if ($debug) print "<p>END OF PROCESSING</p>";
?>
</body>
</html>
