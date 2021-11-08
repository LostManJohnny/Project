document.addEventListener("DOMContentLoaded", load);

function load() {
  let signup_form = document.getElementById("signup-form");

  signup_form.addEventListener("submit", form_onSubmit);
}

function form_onSubmit(e) {
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

    switch (id) {
      case "fname":
        if (input.value.length == 0) {
          error_text = "First name cannot be empty";
        } else if (special_characters.test(input.value)) {
          error_text = "Please only use letters, ' and - in the name fields";
        }
        break;
      case "lname":
        if (input.value.length == 0) {
          error_text = "Last name cannot be empty";
        } else if (special_characters.test(input.value)) {
          error_text = "Please only use letters, ' and - in the name fields";
        }
        break;
      case "birthdate":
        if (input.value.length == 0) {
          error_text = "Birthdate name cannot be empty";
        } else if (Date.parse(input.value) - Date.parse(new Date()) > 0) {
          error_text = "Birthdate cannot be in the future";
        }
        break;
      case "email":
        if (input.value.length == 0) {
          error_text = "Email cannot be empty";
        } else if (!email.test(input.value)) {
          error_text = "Invalid email";
        }
        break;
      case "username":
        if (input.value.length < 8 || input.value.length > 20) {
          error_text = "Username must be between 8 - 20 characters";
        } else if (!username.test(input.value)) {
          error_text =
            "Invalid characters in the username, only use letters and numbers";
        } else if (input.value.includes("admin")) {
          error_text =
            "Username cannot contain the word admin, please select a new username";
        } else if (check_username(input.value)) {
          error_text =
            "Username is already in use, please select something new";
        }
        break;
      case "password":
        let confirm = document.getElementById("password-confirm");
        if (input.value.length < 8) {
          error_text = "Please make your password at least 8 characters long";
        } else if (input.value != confirm.value) {
          error_text = "Passwords do not match";
        }
        break;
      default:
        break;
    }
    if (error_text != "") {
      errors = true;
      let error_div = document.getElementById(error_id);
      error_div.innerHTML = error_text;
      error_div.toggleAttribute("hidden");
    }
  }
  if (errors) {
    e.preventDefault();
  }
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
    `./api/validate_username.php?username=${username}`,
    {
      method: "GET",
    }
  )
    .then((res) => {
      return res.json();
    })
    .then((data) => {
      if (data.status == 200) {
        return data.results;
      } else if (data.status == 400) {
        return true;
      }
    });

  return valid_username;
}
