$(document).ready(function() {

  var seatAction = "";

  // Triggered when an input.submit button is clicked.
  $("form input[type=submit]").click(function() {
    // ?remove 'clicked' attribute from all buttons?
    $("input[type=submit]", $(this).parents("form")).removeAttr("clicked");
    // add 'clicked' attribute to this button
    $(this).attr("clicked", "true");
    seatAction = $("input[type=submit][clicked=true]").attr('id'); // get the button id
  });


  $("#pokerMain").submit(function(e) {

    //alert("seatForm.submit:seatAction=" + seatAction);
    removeFeedback();
    var errorList = new Array();
    switch (seatAction) 
    {
      case "updt":
        errorList = validateUpdt();
//        alert("seatForm.submit:seatAction:unknown=" + seatAction);
        break;
      default: 
//        alert("seatForm.submit:seatAction:unknown=" + seatAction);
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
//    alert("validateUpdt");
// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Regular_Expressions
    var errorFields = new Array();
    var formField;

    var re_IsResponse = /^[IYMNF]$/
    formField = $('#response');

    if (formField.val().search(re_IsResponse) == -1
//       && formField.val() != ""
//       && formField.val() != null
       ) {
      errorFields.push('response')
//      alert("validation errors");
      $("#responseError").html("Invalid response. Must be one of I, Y, M, N or F. (js)");
    } else {
//      alert("No validation errors");
      $("#responseError").html("");
    }

    return errorFields;
  } //end function validateFind 

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


// End of seat.js file
});



