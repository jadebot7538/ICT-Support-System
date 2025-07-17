const messageFeedback = {
  employeeId: {
    Empty: "Enter the Employee ID",
    Invalid: "Please enter a valid Employee ID",
  },
  employeeName: {
    Empty: "Enter the Employee Name",
    Invalid: "Please enter a valid Employee Name",
  },
  selectedLocationType: {
    Empty: "Enter the Location Type",
    Invalid: "Please enter a valid Location Type",
  },
  typeOfService: {
    Empty: "Enter the Type Of Service",
    Invalid: "Please enter a valid Type Of Service",
  },
  typeOfSubService: {
    Empty: "Enter the Type Of Sub Service",
    Invalid: "Please enter a valid Type Of Sub Service",
  },
  CustomOther: {
    Empty: "Enter the Custom Other",
    Invalid: "Please enter a valid Custom Other",
  },
};
function clean(field, validation) {
  /* field.classList.remove("is-valid");
    field.classList.remove("is-invalid");
    validation.classList.remove("valid-feedback");
    validation.classList.remove("invalid-feedback"); */
  field?.classList.remove("is-valid");
  field?.classList.remove("is-invalid");
  validation?.classList.remove("valid-feedback");
  validation?.classList.remove("invalid-feedback");
}

function handleValidField(field, validation) {
  clean(field, validation);
  field.classList.add("is-valid");
  validation.textContent = "";
}
function handleInvalidField(field, validation, message) {
  clean(field, validation);
  field.classList.add("is-invalid");
  validation.classList.add("invalid-feedback");
  validation.textContent = message;
}
// Name
function validate(Value, inputName, feedBackName, Validation, Message) {
  //if empty the input field
  if (Validation == -1) {
    handleInvalidField(inputName, feedBackName, Message.Empty);
  } else {
    if (Validation == 1) {
      handleValidField(inputName, feedBackName);
    } else {
      handleInvalidField(inputName, feedBackName, Message.Invalid);
    }
  }
}
