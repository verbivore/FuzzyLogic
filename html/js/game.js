/**
 * Client side processing for the player form.
 * @author David Demaree <dave.demaree@yahoo.com>
 * File name: play.js
 *** History ***  
 * 14-04-03 Cloned from seat.js.  DHD
 * Future:
 */
$(document).ready(function() {

  var gameAction = "";

  // Triggered when an input.submit button is clicked.
  $("form input[type=submit]").click(function() {
    // ?remove 'clicked' attribute from all buttons?
    $("input[type=submit]", $(this).parents("form")).removeAttr("clicked");
    // add 'clicked' attribute to this button
    $(this).attr("clicked", "true");
    gameAction = $("input[type=submit][clicked=true]").attr('id'); // get the button id
  });

//get a reference to the element
var inviteBtn = document.getElementById('inviteBtn');

//add event listener
inviteBtn.addEventListener('click', function(event) {
    var playerCount = document.getElementById('player-count');
//    alert("gameForm.invite.onclick;button value:" + this.value + ":" + playerCount.value);
    var label = "Invite";
    var checked = false;
    if (this.value == "Invite" ) {
        checked = true;
        label = "Uninvite";
    }
    var inv_cnt = 0;
    var inv_field_name = "invite_" + inv_cnt;
//    alert("gameForm.invite.onclick=" + inv_field_name + ":");
    for (inv_cnt = 0; inv_cnt < playerCount.value; ++inv_cnt) {
//    while (inv_field_name in pokerMain) {
      inv_field_name = "invite_" + inv_cnt;
      $('#'+inv_field_name).attr('checked', checked)
//      inv_cnt++;
//      inv_field_name = "invite_" + inv_cnt;
    }
    // Reset the button label
    this.value = label;
//    alert("gameForm.invite.onclick count=" + inv_cnt);
/*
var inputs, index;
var foo = "";


inputs = document.getElementsByTagName('input');
for (index = 0; index < inputs.length; ++index) {
    foo += inputs[index] + index;
}
      alert("gameForm.invite.onclick=" + ":" + foo);
*/

});

  $("#pokerMain").submit(function(e) {

    //alert("gameForm.submit:gameAction=" + gameAction);
    removeFeedback();
    var errorList = new Array();
    switch (gameAction) 
    {
      case "updt":
        errorList = validateUpdt();
//        alert("gameForm.submit:gameAction:unknown=" + gameAction);
        break;
      default: 
//        alert("gameForm.submit:gameAction:unknown=" + gameAction);
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


// End of game.js file
});



