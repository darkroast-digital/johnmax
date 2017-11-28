<?php

/**
 * The success message displayed when the form is successfully submitted
 */
$successMessage = '<div class="success-message">Your Job Application has been sent.  Thank You!</div>';
$form->setSuccessMessage($successMessage);

/**
 * Configure the recipients of the message.  You can add multiple email
 * addresses by adding one on each line inside the array enclosed in quotes,
 * separated by commas. E.g.
 * $recipients = array(
 *     'recipient1@example.com',
 *     'recipient2@example.com'
 * );
 */
$recipients = array(
//		'david@thinkblueapple.com'
		'resumes@johnmax.ca'
);

/**
 * Create the email success handler, this will email the
 * contents of the form to the set recipients when the form
 * is successfully submitted.
 */
$emailSuccessHandler = new iPhorm_SuccessHandler_Email($form);
$emailSuccessHandler->setRecipients($recipients);
$emailSuccessHandler->setSubject('Job Application from JohnMax.ca');

/**
 * Configure the name element
 * Filters: Trim
 * Validators: Required
 *
 * This example uses the addFilter and addValidator methods which are
 * used to add single filters or validators at a time.
 *
 * See documentation for more help with element configuration
 */
$first_name = new iPhorm_Element('name');             // Create the name element - name must be the same as the name attribute of your form element in the HTML
$first_name->addFilter('trim');                       // Add the Trim filter to the element
$first_name->addValidator('required');                // Add the Required validator to the element (makes the field required)
$form->addElement($first_name);                       // Add the element to the form

/**
 * Configure the email element
 * Filters: Trim
 * Validators: Required, Email
 *
 * See documentation for more help with element configuration
 */
$email = new iPhorm_Element('email');               // Create the email element - email must be the same as the name attribute of your form element in the HTML
$email->addFilter('trim');                          // Add the Trim filter to the element
$email->addValidators(array('required', 'email'));  // Add the Required and Email validators to the element
$form->addElement($email);                          // Add the element to the form

/**
 * Configure the phone element
 * Filters: Trim
 * Validators: (None)
 */
$phone = new iPhorm_Element('phone');               // Create the phone element - phone must be the same as the name attribute of your form element in the HTML
$phone->addFilter('trim');                          // Add the Trim filter to the element
$form->addElement($phone);                          // Add the element to the form

/**
 * Configure the message element
 * Filters: Trim
 * Validators: Required
 */
$notes = new iPhorm_Element('notes');             	// Create the notes element - notes must be the same as the name attribute of your form element in the HTML
$notes->addFilter('trim');                          // Add the Trim filter to the element
$notes->addValidator('required');                   // Add the Required validator to the element
$form->addElement($notes);                          // Add the element to the form

/**
 * Configure the upload element
 * Filters: (None)
 * Validators: FileUpload (Added automatically)
 */
$upload = new iPhorm_Element_File('upload');
$upload->setRequired(false);                            // Optionally set the field required
$upload->setMaximumFileSize(10485760);                  // Optionally set a maximum size (10MB)
$form->addElement($upload);
$emailSuccessHandler->addAttachmentElement($upload);    // Attach to email

/**
 * Configure the grouped upload element
 * Filters: (None)
 * Validators: FileUpload (Added automatically)
 */
$uploads = new iPhorm_Element_File('uploads[]');
$uploads->setRequired(true);                                         // Optionally set the field required
$uploads->setRequiredCount(1);                                       // Optionally require 1 files minumum
$uploads->setAllowedExtensions(array('doc', 'docx', 'pdf', 'rtf' , 'txt'));  // Optionally limit file extensions
$uploads->setMaximumFileSize(10485760);                               // Optionally set a maximum size (10MB)
$form->addElement($uploads);
$emailSuccessHandler->addAttachmentElement($uploads);                // Attach to email


/**
 * Configure the CAPTCHA element
 * Filters: Trim
 * Validators: Required, Identical
 */
$captcha = new iPhorm_Element('type_the_word');                 // Create the type_the_word (captcha) element - type_the_word must be the same as the name attribute of your form element in the HTML
$captcha->addFilter('trim');                                    // Add the Trim filter to the element
$captcha->addValidator('required');                             // Add the Required validator to the element
$captcha->addValidator('identical', array('token' => 'light')); // Add Identical validator value must match the word light
$captcha->setSkipRender(true);                                  // Success handlers should not render the element
$form->addElement($captcha);                                    // Add the element to the form
