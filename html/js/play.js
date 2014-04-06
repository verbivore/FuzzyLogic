/**
 * Client side processing for the player form.
 * @author David Demaree <dave.demaree@yahoo.com>
 * File name: play.js
 *** History ***  
 * 14-04-05 Added select to removeFeedback.  DHD
 * 14-04-03 Added dot to $("#errorDiv").html(".") to prevent screen hop.  DHD
 * 14-04-03 Cloned from seat.js.  DHD
 * Future:
 *  Format score, right-justify stats
 */
$(document).ready(function() {

  var playAction = "";

  // Triggered when an input.submit button is clicked.
  $("form input[type=submit]").click(function() {
    // ?remove 'clicked' attribute from all buttons?
    $("input[type=submit]", $(this).parents("form")).removeAttr("clicked");
    // add 'clicked' attribute to this button
    $(this).attr("clicked", "true");
    playAction = $("input[type=submit][clicked=true]").attr('id'); // get the button id
  });


  $("#pokerMain").submit(function(e) {

    //alert("playForm.submit:playAction=" + playAction);
    removeFeedback();
    var errorList = new Array();
    switch (playAction) 
    {
      case "updt":
//        alert("playForm.submit:playAction:updt=" + playAction);
        errorList = validateUpdt();
        break;
      default: 
//        alert("playForm.submit:playAction:unknown=" + playAction);
        break;
    }


    if (errorList == "") {
      //alert("No validation errors");
      return true;
    } else {
      alert("Validation errors (play.js)");
      provideFeedback(errorList);
      e.preventDefault();
      return false;
    }
  });


  function validateUpdt() {
//    alert("validateUpdt");
// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Regular_Expressions
    var errorFields = new Array();
//    var formField;

    validateNickname(errorFields);
    validate_name_first(errorFields);
    validate_name_last(errorFields);
    validate_status(errorFields);
    validate_email(errorFields);
    validate_phone(errorFields);

    return errorFields;
  } //end function validateUpdt 

  function validateNickname(errorFields) {

    var formField = 'nickname';
//    alert("Validating "+formField);

    if (  $('#'+formField).val() == ""     // Not blank
       || $('#'+formField).val() == null   // Not null
       ) {
      errorFields.push(formField)
//      alert(formField+" validation errors");
      $("#"+formField+"Error").html("Plese enter a Nickname. (js)");
    } else {
//      alert("No "+formField+" validation errors");
      $("#"+formField+"Error").html("");
    }
  } //end function validateNickname 

/**
 * Validate the name_first form field
 */
  function validate_name_first(errorFields) {

    var re_IsName = /^[A-Z][A-z ]*$/       // Alpha, capitalized
    var formField = 'name_first';
//    alert("Validating "+formField);

    if (  $('#'+formField).val() == ""     // Not blank
       || $('#'+formField).val() == null   // Not null
       || $('#'+formField).val().search(re_IsName) == -1
       ) {
      errorFields.push(formField)
//      alert(formField+" validation errors");
      $("#"+formField+"Error").html("Plese enter a First Name, capitalized.3 (js)");
    } else {
//      alert("No "+formField+" validation errors");
      $("#"+formField+"Error").html("");
    }
  } //end function validate_name_first 

/**
 * Validate the name_last form field
 */
  function validate_name_last(errorFields) {

    var re_IsName = /^[A-Z][A-z ]*$/       // Alpha, capitalized
    var formField = 'name_last';
//    alert("Validating "+formField);

    if (  $('#'+formField).val() == ""     // Not blank
       || $('#'+formField).val() == null   // Not null
       || $('#'+formField).val().search(re_IsName) == -1
       ) {
      errorFields.push(formField)
//      alert(formField+" validation errors");
      $("#"+formField+"Error").html("Plese enter a Last Name, capitalized.3 (js)");
    } else {
//      alert("No "+formField+" validation errors");
      $("#"+formField+"Error").html("");
    }
  } //end function validate_name_last 


  function validate_status(errorFields) {

    var regEx = /^[AX]$/
    var formField = 'status';
//    alert("Validating "+formField);

    if (  $('#'+formField).val() == ""     // Not blank
       || $('#'+formField).val() == null   // Not null
       || $('#'+formField).val().search(regEx) == -1
       ) {
      errorFields.push(formField)
//      alert(formField+" validation errors");
      $("#"+formField+"Error").html("Please enter a Status of 'A' or 'X'.2 (js)");
    } else {
//      alert("No "+formField+" validation errors");
      $("#"+formField+"Error").html("");
    }
  } //end function validate_status

/**
 * Validate the email form field
 */
  function validate_email(errorFields) {

    var regEx = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var formField = 'email';
//    alert("Validating "+formField+":"+regEx);

    if (  $('#'+formField).val() == ""     // Not blank
       || $('#'+formField).val() == null   // Not null
       || $('#'+formField).val().search(regEx) == -1
       ) {
      errorFields.push(formField)
//      alert(formField+" validation errors");
      $("#"+formField+"Error").html("Plese enter a valid Email address.3 (js)");
    } else {
//      alert("No "+formField+" validation errors");
      $("#"+formField+"Error").html("");
    }
  } //end function validate_email 

/**
 * Validate the phone form field
 */
  function validate_phone(errorFields) {

//    var regEx = /^[AX]$/;
    var regEx = /^\d{3}-\d{3}-\d{4}|\d{10}$/;
    var formField = 'phone';
//    alert("Validating "+formField);

    if (  $('#'+formField).val() == ""     // Not blank
       || $('#'+formField).val() == null   // Not null
       || $('#'+formField).val().search(regEx) == -1
       ) {
      errorFields.push(formField)
//      alert(formField+" validation errors");
      $("#"+formField+"Error").html("Plese enter a 10-digit Phone Number.4 (js)");
    } else {
//      alert("No "+formField+" validation errors");
      $("#"+formField+"Error").html("");
    }
  } //end function validate_phone 



/**
 * Alter input field properties to denote input error (change color via css)
 */
  function provideFeedback(incomingErrors) {
    for (var i = 0; i < incomingErrors.length; i++) {
      $("#" + incomingErrors[i]).addClass("errorClass");
      $("#" + incomingErrors[i] + "Error").removeClass("errorFeedback");
    }
    $("#errorDiv").html("See "+incomingErrors.length+" errors below. (js)");
  } //end function provideFeedback

/**
 * Clear all feedback from input fields.
 */
  function removeFeedback() {
    $("#errorDiv").html(".");                // Remove page message (dot is to prevent screen hop)
    $('input').each(function() {
      $(this).removeClass("errorClass");    // Remove error attributes from each input field
    });
    $('select').each(function() {
      $(this).removeClass("errorClass");    // Remove error attributes from each input field
    });
    $('.errorSpan').each(function() {
      $(this).addClass("errorFeedback");    // Add ??? attributes for each input field
    });
    
  } //end function removeFeedback


// End of play.js file
});



