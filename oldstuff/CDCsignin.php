<?php 
/**
 * this logic is called when the form is submitted to itself.
 * It sets the fields required by the email message to thier values, passed in by POST.
 * It also validates the form fields (ensures they are not blank)
 */
if (isset($_POST["cmdSubmitted"]))
{
	// initialize my variables to the forms posting  
   	$netId = $_POST["NETID"];
   	$firstName = $_POST["First_Name"];
   	$lastName = $_POST["Last_Name"];
   	$contactPhone = $_POST["Contact_Phone"];
   	$emailAddress = $_POST["realname"];
   	$shortDescription = $_POST["Description_of_Problem"];

   	//convert to html entities to mitigate injection attacks
   	$netId = htmlentities($netId, ENT_QUOTES);
   	$firstName = htmlentities($firstName, ENT_QUOTES);
   	$lastName = htmlentities($lastName, ENT_QUOTES);
   	$contactPhone = htmlentities($contactPhone, ENT_QUOTES);
   	$emailAddress = htmlentities($emailAddress, ENT_QUOTES);
   	$shortDescription = htmlentities($shortDescription, ENT_QUOTES);

   	//perform validation
   	$errorMsg = array();
   	
	if($firstName=="")
		{
	        $errorMsg[]="Please enter your First Name";
	    }
	if($lastName=="")
		{
	        $errorMsg[]="Please enter your Last Name";
	    }
	if($contactPhone=="")
		{
	        $errorMsg[]="Please enter your Phone Number";
	    }
	if($emailAddress=="")
		{
	        $errorMsg[]="Please enter your Email Address";
	    } 
	if($shortDescription=="")
	{
        $errorMsg[]="Please enter a short description";
    } 

    //if there is an item in the error message array, display the errors in the errors div. Otherwise, craft and send email.
    if($errorMsg)
		{
			//if there is an error, then set $loaded to true so it can be used further down
			$loaded = true;
			//message is the combined error messages
			$message = "";
	        foreach($errorMsg as $err)
			{
				$message .= "<li style='color: #ff6666'>" . $err . "</li>\n";
	        }
	    }
		else 
		{
			//recipients
			$to = "cdclinic@uvm.edu";
			//$to = "phelan.vendeville@gmail.com";

	        //subject
	        $subject = $firstName . " " . $lastName . ": " . $shortDescription;

	    	//get date and time
	        $Todays_Date = strftime("%x");
	        $Current_Time = strftime("%X");

	        // message
	        $message = "<html><head><title>Confirmation</title></head><body><p>Below is the result of your form. It was filled out on ";
	        $message .= $Todays_Date . " at " . $Current_Time . ".</p>";
	        $message .= "<p>NetID: " . $username . "</p>";
	        $message .= "<p>First Name: " . $firstName . "</p>";
	        $message .= "<p>Last Name: " . $lastName . "</p>";
	       	$message .= "<p>Contact Phone: " . $contactPhone . "</p>";
	        $message .= "<p>Email: " . $emailAddress . "</p>";
	        $message .= "<p>Description: " . $shortDescription . "</p>";
			$message .= "</body></html>";


	        //set content type and from field
	        $headers  = "MIME-Version: 1.0\r\n";
	        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	        $headers .= "From:(" . $emailAddress . ")\r\n";

	        // and now to mail it
	        $blnMail=mail($to, $subject, $message, $headers);
	
			//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
			//now redirect to thank you page
			header('Location: thanks.php');
		}
}
?>

 <!-- ***** Begin sign in form *****  -->
 <!--<form name="form1" action="https://scripts.uvm.edu/cgi-bin/FormMail.pl" method="post">-->
 <form action="<? print $_SERVER['PHP_SELF']; ?>" method="post">
   <p>
   <input name="recipient" value="cdclinic@uvm.edu" type="hidden" />
   <input name="subject" value="CDC Intake" type="hidden" />
   <input name="required" value="realname,First_Name,Last_Name,Description_of_Problem, Contact_Phone" type="hidden" />
 
 Welcome to the Computer Depot Clinic. Please sign in by filling out the form below. We will call you based on the time stamp generated by clicking SUBMIT below. Please note our data backup procedures as posted.</p>
 
 <p>Fields marked with <b>bold text</b> are required.</p>
 
 <p><hr /></p>
 
 <ul>
 <p><li>UVM <a href="http://www.uvm.edu/it/account/?Page=what.html" TARGET="netid">NetID</a> (ex. <i>mkapoodle</i> for email address <i>mkapoodle</i>@uvm.edu<em>):<br />
 <input name="NETID" size="12" type="text" maxlength="8" value="<?php echo $netId; ?>"/></li></p>
 
 <p><li><b>First name:</b><br />
 <input name="First_Name" size="20" maxlength="20" type="text" value="<?php echo $firstName; ?>"/></li></p>
 
 <p><li><b>Last name:</b><br />
 <input name="Last_Name" size="30" maxlength="30" type="text" value="<?php echo $lastName; ?>"/></li></p>
 
 <p><li><b>Best contact phone number to reach you (with area code):</b><br />
 <input name="Contact_Phone" size="15" maxlength="15" type="text" value="<?php echo $contactPhone; ?>"/></li></p>
 
 <p><li><b>Email address:</b><br />
 <input name="realname" size="30" maxlength="30" type="text" value="<?php echo $emailAddress; ?>"/></li></p>
 
 <p><li><b>Short description of the computer problem:</b><br />
 <textarea name="Description_of_Problem" rows="5" cols="64" wrap="virtual" maxlength="640"><?php echo $shortDescription; ?></textarea></li></p>
 </ul>
 
 <!--<p><input name="Submit" onclick="change()" value="Submit" type="submit" /></p>-->
 <p><input type="submit" name="cmdSubmitted" value="Submit" /></p>
 
 </form>
 <!-- ***** end sign in form *****  -->

 <div id="errors">
			<?php
				//print out the error message
				echo $message;

				//display the div containing the error messages (setting visibility) if there are any errors
				if ($loaded)
				{
					echo '<script type="text/javascript">document.getElementById("errors").style.display = "block";</script>';
				}	
			?>
</div><!-- end errors-->
