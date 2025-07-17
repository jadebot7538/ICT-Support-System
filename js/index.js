const form = document.getElementById("form");
const Input = {
  employeeId: document.getElementById("employeeId"),
  employeeName: document.getElementById("employeeName"),
  department: document.getElementById("department"),
  division: document.getElementById("division"),
  section: document.getElementById("section"),
  unit: document.getElementById("unit"),
  typeOfService: document.getElementById("typeOfService"),
  typeOfSubService: document.getElementById("typeOfSubService"),
  CustomOther: document.getElementById("CustomOthers"),
  selectedLocationType: document.getElementById("department"),
  csrfToken: document.getElementById("csrfToken"),
};
let InputValue;

const InputFeedback = {
  employeeId: document.getElementById("employeeIdFeedback"),
  employeeName: document.getElementById("employeeNameFeedback"),
  selectedLocationType: document.getElementById("selectedLocationTypeFeedback"),
  typeOfService: document.getElementById("typeOfServiceFeedback"),
  typeOfSubService: document.getElementById("typeOfSubServiceFeedback"),
  CustomOther: document.getElementById("customOthersFeedback"),
};
function checkForm(InputValue, Input, InputFeedback, InputValidation) {
  validate(
    InputValue.employeeId,
    Input.employeeId,
    InputFeedback.employeeId,
    InputValidation.employeeId,
    messageFeedback.employeeId
  );
  validate(
    InputValue.employeeName,
    Input.employeeName,
    InputFeedback.employeeName,
    InputValidation.employeeName,
    messageFeedback.employeeName
  );
  validate(
    InputValue.selectedLocationType,
    Input.selectedLocationType,
    InputFeedback.selectedLocationType,
    InputValidation.selectedLocationType,
    messageFeedback.selectedLocationType
  );
  validate(
    InputValue.typeOfService,
    Input.typeOfService,
    InputFeedback.typeOfService,
    InputValidation.typeOfService,
    messageFeedback.typeOfService
  );
  validate(
    InputValue.typeOfSubService,
    Input.typeOfSubService,
    InputFeedback.typeOfSubService,
    InputValidation.typeOfSubService,
    messageFeedback.typeOfSubService
  );

  if (InputValue.typeOfSubService == "others") {
    validate(
      InputValue.CustomOther,
      Input.CustomOther,
      InputFeedback.CustomOther,
      InputValidation.CustomOther,
      messageFeedback.CustomOther
    );
  }
}
