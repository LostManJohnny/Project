document.addEventListener("DOMContentLoaded", load);

function load() {
  let signup_form = document.getElementById("signup-form");

  signup_form.addEventListener("submit", form_onSubmit);
}

async function form_onSubmit(e) {
  // e.preventDefault();
  let signup_form = document.getElementById("signup-form");
  let inputs = signup_form.getElementsByTagName("input");
  let errors = false;

  clear_errors();

  for (let input of inputs) {
    let id = input.id;
    let error_id = input.id + "-error";
    let error_text = "";
    let special_characters = /[!@#$%^&*()_+=\[\]{};:"\\|,.<>\/?(0-9)]+/;
    let email =
      /(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9]))\.){3}(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9])|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/;
    let username = /^(?=[a-zA-Z0-9._]{8,20}$)(?!.*[_.]{2})[^_.].*[^_.]$/;

    let username_check, email_check;

    //Perform async check if input requires is
    if (id == "username") {
      username_check = await check_username(input.value);
    } else if (id == "email") {
      email_check = await check_email(input.value);
    }

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
      console.log(id);
      console.log(error_text);
      errors = true;
      e.preventDefault();
      let error_div = document.getElementById(error_id);
      if (error_div != null) {
        error_div.innerHTML = error_text;
        error_div.toggleAttribute("hidden");
      }
    }
  }

  console.log(errors);

  if (errors) {
    e.preventDefault();
  }

  // e.preventDefault();
}

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
