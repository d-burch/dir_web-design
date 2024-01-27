<?php
	/*  	
				****FORM VALIDATION*****
	If input names are changed need to update the following:
	 nameemailmore-commentscommentssubmit
	 $_POST will only keep the last input that has the same name
	 so if two of same name are sent the first will be overwritten
	
	In no particular order:
	1. No HTML / JS
	2. Validate email
	3. Limit characters
	4. Notify if a bot fills in honeypot
	5. Remove forward slashes
	6. Trim extra whitespace at beginning and end of string
	7. No extra input fields
	8. No input fields with different name
	*/
	
	$form_name = 'Contact Form';
	
	# Run each variable through the check_entries function	
	$name = check_entries($_POST['name']);
	$email = check_entries($_POST['email']);
	$honeypot = check_entries($_POST['more-comments']);
	$comments = check_entries($_POST['comments']);
	$submit = check_entries($_POST['submit']);
		
	# Variables for email	
	# $to = '<email_address>';
	$message = 'Name: ' . $name . "\r\n\r\n";
	$message .= 'Email: ' . $email . "\r\n\r\n";
	$message .= 'Comments: ' . $comments . "\r\n\r\n";
	$message .= 'Honeypot: ' . $honeypot . "\r\n\r\n";
	
	# Get keys in $_POST, will determine subject line of email
	$keys = array_keys($_POST);
	$each_key = '';
	for ($i = 0; $i < count($keys); $i++) {
		$current_key = $keys[$i];
		$each_key .= $current_key;
	}
	
	# Check for form fields being manipulated takes precedence over honeypot
	# Check the following and change Subject depending
	# 1. Submit is not null
	# 2. Input names have not been changed (this also verifies no inputs have been added)
	if (isset($submit) !== true || $each_key !== 'nameemailmore-commentscommentssubmit') {
		$subject = $form_name . ' Warning! Form Fields or Submit Input may have been Manipulated';
	} else {
		$honeypot_length = strlen($honeypot);
		if ($honeypot_length > 0) {
			$subject = $form_name . ' Potential Spam';
		} else {
			$subject = $form_name;
			}
	}
 	
 	# Send email	
	$success = mail($to, $subject, $message);
	
	# Validate each input field
	function check_entries($info) {
		$info = trim($info); # Removes whitespace at beginning and end of string
		$info = stripslashes($info);
		$info = htmlspecialchars($info);
		$info = substr($info, 0, 100); # Limits number of characters
		$regex = '/[^A-Za-z0-9 \/!@,.?;:\'\"&]+/'; # global modifier (g) was causing issues
		$info = preg_replace($regex, ' REPLACED ', $info);
		if ($info == $_POST['email']) {
			$email_check = filter_var($info, FILTER_VALIDATE_EMAIL);
			if (!$email_check) {
				$info = 'WARNING: bad email address, email validation was circumvented on client side';
			} else {
				$info = $email_check;
			}
		}
		return $info;
	}
?>

<!DOCTYPE html>
<html lang="en-US">
	<head>
		<!-- Global Site Tag (gtag.js) - Google Analytics -->
		<!-- <script async src="google_tag_manager_url>"></script> -->
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments)};
		  gtag('js', new Date());
	
		  // gtag('config', '<ga_id>');
		</script>
		<title>Form Acknowledged</title>
		<meta charset="UTF-8" />
		<meta name="description" content="When a form is received." />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<!-- <link rel="stylesheet" type="text/css" href="style.css" /> -->
		<!-- <link rel="icon" type="image/png" href="images/favicon.png" /> -->
	</head>
	<body>
		<div id="internal-full-container">
		
			<nav>
				<div id="nav-background"> <!--To push #menu-list behind nav area when transitioning-->
					<div id="logo">
						<a href="index.html#home-background-cta"><img src="images/starter-logo.png" alt="Web Designs" /></a>
					</div>
					<div id="hamburger">
						<div id="one">
							<span>Menu</span>
						</div>
						<div id="two">
							<span class="hamburger-icon"></span>
							<span class="hamburger-icon"></span>
							<span class="hamburger-icon"></span>
						</div>
					</div>		
				</div>
				<div id="menu">
					<ul id="menu-list">
						<li><a href="index.html#home-background-cta">Home</a></li>
						<li><a href="designs.html">Designs</a></li>
						<li><a href="more-info.html">More Info</a></li>
						<li><a href="how-it-works.html">How It Works</a></li>
						<li><a href="articles.html">Articles</a></li>
						<li><a href="contact.html">Contact</a></li>
					</ul>
				</div>				
			</nav>
			
			<main>
				<div id="internal-background-cta" class="background-cta">					
					<!--Illustration as visual aid to text on page.-->
				</div>
				
				<div id="internal-text-area" class="text-area middle-section">
					<!--Text area-->
					<?php
						if (isset($success) && $success) {
							?>
							<h1>Form received, thank you.</h1>
						<?php } else { ?>
							<h1>There was a problem sending the form.</h1>
							<?php
								}
							?>
				</div>
				
				<div id="internal-form-area" class="form-area middle-section">
					<!--Form Area-->
				</div>
			</main>
			
			<footer>
				Inquire about web designs
				<br />
				<!-- phone number -->
			</footer>
				
		</div>
		<!-- <script src="menu.js"></script> -->
	</body>
</html>
