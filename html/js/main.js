$(document).ready(function() {

  var mainAction = "";

  // Triggered when an input.submit button is clicked.
  $("form input[type=submit]").click(function() {
    // ?remove 'clicked' attribute from all buttons?
    $("input[type=submit]", $(this).parents("form")).removeAttr("clicked");
    // add 'clicked' attribute to this button
    $(this).attr("clicked", "true");
//    mainAction = $("input[type=submit][clicked=true]").val() // get the button name
    mainAction = $("input[type=submit][clicked=true]").attr('id'); // get the button id
  });


  $("#pokerMain").submit(function(e) {

    //alert("mainForm.submit:mainAction=" + mainAction);
    removeFeedback();
    var errorList = new Array();
/*
//Future:    switch (mainAction) {
    if (mainAction == "prev") {
      errorList = validateFind();
    } else if (mainAction == "updt") {
      errorList = validateAdd();
    } else if (mainAction == "next") {
      errorList = validateFind();
    } else if (mainAction == "burp") {
    //  alert("no need to validateBurp");
    } else {
      alert("mainForm.submit:unknown=" + mainAction);
    }
*/
    switch (mainAction) 
    {
      case "main":
        errorList = validateUpdt();
//        alert("mainForm.submit:mainAction:unknown=" + mainAction);
        break;
      default: 
        alert("mainForm.submit:mainAction:unknown=" + mainAction);
        break;
    }


    if (errorList == "") {
      //alert("No validation errors");
      return true;
    } else {
      //alert("Validation errors:");
      provideFeedback(errorList);
      e.preventDefault();
      return false;
    }
  });


  function validateUpdt() {
      alert("validate");
//    var ymd = new Date();
    
//    alert("validateFind");
// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Regular_Expressions
    var errorFields = new Array();
    var formField;

    if (isNaN(formField.val())) { //if match failed
      errorFields.push('ee_id')
      alert("validation errors");
      $("#ee_idError").html("js:Employee Number must contain only digits.");
    } else {
      alert("No validation errors");
      $("#ee_idError").html("");
    }

    return errorFields;
  } //end function validateFind 



  function validateAdd() {
//    alert("validateAdd");
// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Regular_Expressions
    var errorFields = new Array()
    var formField;





    // fed status
//    alert("validateAdd=fed status.");
    var re_IsMaritalStatus = /^[msMS]$/
    formField = $('#fed_status');
    if (formField.val().search(re_IsMaritalStatus) == -1
       && formField.val() != ""
       && formField.val() != null
       ) {
      errorFields.push('fed_status')
      $("#fed_statusError").html("js:Federal Marital Status must be either 'S' (Single) or 'M' (Married).");
    } else {
//      formField.val() = formField.val().toUpperCase(); 
      $("#fed_statusError").html("");
    }



//    alert("validateAdd=end.");
    return errorFields;
  } //end function validateAdd 


/*
* Alter input field properties to denote input error (change color via css)
*/
  function provideFeedback(incomingErrors) {
    for (var i = 0; i < incomingErrors.length; i++) {
      $("#" + incomingErrors[i]).addClass("errorClass");
      $("#" + incomingErrors[i] + "Error").removeClass("errorFeedback");
    }
    $("#errorDiv").html("Errors encountered");
  } //end function provideFeedback

/*
* Clear all feedback from input fields.
*/
  function removeFeedback() {
    $("#errorDiv").html("");
    $('input').each(function() {
      $(this).removeClass("errorClass");
    });
    $('.errorSpan').each(function() {
      $(this).addClass("errorFeedback");
    });
    
  } //end function removeFeedback


// End of main.js file
});



