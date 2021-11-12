/**
 * Author: Matthew Major
 * Date: Nov 11, 2021
 *
 * Handles signup validation. Ensures that data integrity is kept intact.
 * Ensure that usernames and emails are not duplicates.
 */

document.addEventListener("DOMContentLoaded", load);

/**
 * Description: Occurs when the web page has fully loaded
 */
function load() {
  //Get the signup form element
  let signup_form = document.getElementById("signup-form");

  //Wait for promise to resolve when form is submitted
  signup_form.addEventListener("submit", (e) => {
    Promise.resolve(form_onSubmit(e));
  });
}
/**
 * e: The submit event
 * Description: Is called when the form is submitted. Validates the form contents to ensure that
 *   data integrity is kept intact.
 */
async function form_onSubmit(e) {
  //Get the form elemnt
  let signup_form = document.getElementById("signup-form");
  //Get an array of the inputs
  let inputs = signup_form.getElementsByTagName("input");
  //Error marker
  let errors = false;
  //Async check v
  let username_check, email_check;

  //Clear previous errors
  clear_errors();

  //Perform async check to databse for username and email
  if (document.getElementById("username").value.length > 0) {
    username_check = Promise.resolve(
      check_username(document.getElementById("username").value)
    );
  }

  if (document.getElementById("email").value.length > 0) {
    email_check = Promise.resolve(
      check_email(document.getElementById("email").value)
    );
  }

  //Does the remaining checks
  if (!errors) {
    //Regex for sepecific fields
    let special_characters = /[!@#$%^&*()_+=\[\]{};:"\\|,.<>\/?(0-9)]+/;
    let email =
      /(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9]))\.){3}(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9])|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/;
    let username = /^(?=[a-zA-Z0-9._]{8,20}$)(?!.*[_.]{2})[^_.].*[^_.]$/;

    for (let input of inputs) {
      //The id of the element
      let id = input.id;
      //The id of the error div
      let error_id = input.id + "-error";
      //Error text
      let error_text = "";

      //Perform check based on the input's id
      switch (id) {
        //First name input validation
        case "fname":
          if (input.value.length == 0) {
            error_text = "First name cannot be empty";
          } else if (special_characters.test(input.value)) {
            error_text = "Please only use letters, ' and - in the name fields";
          }
          break;
        //Last name input validation
        case "lname":
          if (input.value.length == 0) {
            error_text = "Last name cannot be empty";
          } else if (special_characters.test(input.value)) {
            error_text = "Please only use letters, ' and - in the name fields";
          }
          break;
        //Birthdate input validation
        case "birthdate":
          if (input.value.length == 0) {
            error_text = "Birthdate name cannot be empty";
          } else if (Date.parse(input.value) - Date.parse(new Date()) > 0) {
            error_text = "Birthdate cannot be in the future";
          }
          break;
        //Email input validation
        case "email":
          if (input.value.length == 0) {
            error_text = "Email cannot be empty";
          } else if (!email.test(input.value)) {
            error_text = "Invalid email";
          } else if (email_check) {
            error_text = "Email is already in use, please select something new";
          }
          break;
        //Username input validation
        case "username":
          if (input.value.length < 8 || input.value.length > 20) {
            error_text = "Username must be between 8 - 20 characters";
          } else if (!username.test(input.value)) {
            error_text =
              "Invalid characters in the username, only use letters and numbers";
          } else if (input.value.includes("admin")) {
            error_text =
              "Username cannot contain the word admin, please select a new username";
          } else if (username_check) {
            error_text =
              "Username is already in use, please select something new";
          }
          break;
        //Password input validation
        case "password":
          let confirm = document.getElementById("password-confirm");
          if (input.value == "") {
            error_text = "Password cannot be empty";
          } else if (input.value.length < 8) {
            error_text = "Please make your password at least 8 characters long";
          } else if (input.value != confirm.value) {
            error_text = "Passwords do not match";
          }
          break;
        default:
          break;
      }

      //If the error text is not empty, then an error occured
      if (error_text != "") {
        //Set error marker to true
        errors = true;
        let error_div = document.getElementById(error_id);
        //Reveal and provide error text to the error div
        if (error_div != null) {
          error_div.innerHTML = error_text;
          error_div.toggleAttribute("hidden");
        }
      }
    }
  }

  //If an error occured, prevent the form from submitting
  if (errors) {
    e.preventDefault();
  }
}

/**
 * Description: Clears and hides all the error dives in the form
 */
function clear_errors() {
  let signup_form = document.getElementById("signup-form");
  let inputs = signup_form.getElementsByTagName("input");

  for (let input of inputs) {
    let error_id = input.id + "-error";
    let error_div = document.getElementById(error_id);

    if (error_div != null && !error_div.hidden) {
      error_div.toggleAttribute("hidden");
    }
  }
}

/**
 * Username: The username to be validated
 * Description: Checks the database to verify if the username has been used already
 */
async function check_username(username) {
  let valid_username = await fetch(
    `./api/validate_username.php?username=${username}`
  );

  let data = await valid_username.json();
  if (data.status == 200) {
    return data.results;
  } else if (data.status == 400) {
    return true;
  }
}

/**
 * Email: The email to be validated
 * Description: Checks the database to verify if the email has been used already
 */
async function check_email(email) {
  let valid_email = await fetch(`./api/validate_email.php?email=${email}`);

  let data = await valid_email.json();
  console.log("Results: " + data.results);
  console.log("Status: " + data.status);
  if (data.status == 200) {
    return data.results;
  } else if (data.status == 400) {
    return true;
  }
}
