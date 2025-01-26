$(document).ready(function () {
  let displayValue = "";
  let expressionValue = "";
  let firstOperand = null;
  let operator = null;
  let waitingForSecondOperand = false;

  const $display = $("#display");
  const $expression = $("#expression");

  // Update displays
  function updateDisplays() {
    $display.val(displayValue || "0");
    $expression.val(expressionValue);
  }

  // Handle number input
  function inputDigit(digit) {
    if (waitingForSecondOperand) {
      displayValue = digit;
      expressionValue =
        expressionValue.replace(/[\+\-\*\/]\s*$/, "") +
        " " +
        operator +
        " " +
        digit;
      waitingForSecondOperand = false;
    } else {
      displayValue = displayValue === "0" ? digit : displayValue + digit;
      expressionValue =
        expressionValue === "0" ? digit : expressionValue + digit;
    }
  }

  // Handle decimal point
  function inputDecimal() {
    if (waitingForSecondOperand) {
      displayValue = "0.";
      expressionValue += "0.";
      waitingForSecondOperand = false;
      return;
    }
    if (!displayValue.includes(".")) {
      displayValue += ".";
      expressionValue += ".";
    }
  }

  // Handle operators
  function handleOperator(nextOperator) {
    const inputValue = parseFloat(displayValue);

    if (nextOperator === "%") {
      if (operator === "+" || operator === "-") {
        const percentValue = firstOperand * (inputValue / 100);
        const result = calculate(firstOperand, percentValue, operator);
        displayValue = `${parseFloat(result.toFixed(7))}`;
        expressionValue += nextOperator + " = " + displayValue;
        firstOperand = result;
        operator = null;
      } else if (operator === "*" || operator === "/") {
        const percentValue = inputValue / 100;
        const result = calculate(firstOperand, percentValue, operator);
        displayValue = `${parseFloat(result.toFixed(7))}`;
        expressionValue += nextOperator + " = " + displayValue;
        firstOperand = result;
        operator = null;
      } else {
        const percentValue = inputValue / 100;
        displayValue = `${parseFloat(percentValue.toFixed(7))}`;
        expressionValue += nextOperator + " = " + displayValue;
        firstOperand = parseFloat(percentValue);
      }
      waitingForSecondOperand = true;
      return;
    }

    if (firstOperand === null && !isNaN(inputValue)) {
      firstOperand = inputValue;
    } else if (operator) {
      const result = calculate(firstOperand, inputValue, operator);
      displayValue = `${parseFloat(result.toFixed(7))}`;
      firstOperand = result;
    }

    waitingForSecondOperand = true;
    operator = nextOperator;
    expressionValue = firstOperand + " " + nextOperator + " ";
  }

  // Calculate result
  function calculate(first, second, op) {
    switch (op) {
      case "+":
        return first + second;
      case "-":
        return first - second;
      case "*":
        return first * second;
      case "/":
        return first / second;
      default:
        return second;
    }
  }

  // Clear calculator
  function resetCalculator() {
    displayValue = "";
    expressionValue = "";
    firstOperand = null;
    operator = null;
    waitingForSecondOperand = false;
  }

  // Delete last character
  function deleteLastChar() {
    if (waitingForSecondOperand) {
      operator = null;
      waitingForSecondOperand = false;
      expressionValue = expressionValue.replace(/[\+\-\*\/]\s*$/, "");
      displayValue = expressionValue;
      return;
    }

    displayValue = displayValue.toString().slice(0, -1);
    expressionValue = expressionValue.toString().slice(0, -1);

    if (displayValue === "") {
      displayValue = "0";
    }
    if (expressionValue === "") {
      expressionValue = "0";
    }
  }

  // Handle keyboard input
  function handleKeyboardInput(key) {
    if (!$("#calculatorModal").hasClass("show")) {
      // If the modal is not visible, do nothing
      return;
    }

    // Ignore F1-F12 keys
    if (/^F[1-9]$|^F1[0-2]$/.test(key)) {
      return;
    }

    if (/[0-9]/.test(key)) {
      inputDigit(key);
    } else if (key === ".") {
      inputDecimal();
    } else if (["+", "-", "*", "/", "%"].includes(key)) {
      handleOperator(key);
    } else if (key === "Enter" || key === "=") {
      if (operator && !waitingForSecondOperand) {
        const secondOperand = parseFloat(displayValue);
        expressionValue += " = ";
        const result = calculate(firstOperand, secondOperand, operator);
        displayValue = `${parseFloat(result.toFixed(7))}`;
        firstOperand = result;
        operator = null;
        waitingForSecondOperand = true;
      }
    } else if (key === "Backspace") {
      deleteLastChar();
    } else if (key === "Delete") {
      resetCalculator(); // Changed "Esc" to "Delete"
    }
    updateDisplays();
  }

  // Event listeners
  $(".calc_buttons button").on("click", function () {
    const $target = $(this);

    if ($target.hasClass("operator")) {
      handleOperator($target.text());
      updateDisplays();
      return;
    }

    if ($target.hasClass("number")) {
      if ($target.text() === ".") {
        inputDecimal();
      } else {
        inputDigit($target.text());
      }
      updateDisplays();
      return;
    }

    if ($target.hasClass("equals")) {
      if (operator && !waitingForSecondOperand) {
        const secondOperand = parseFloat(displayValue);
        expressionValue += " = ";
        const result = calculate(firstOperand, secondOperand, operator);
        displayValue = `${parseFloat(result.toFixed(7))}`;
        firstOperand = result;
        operator = null;
        waitingForSecondOperand = true;
        updateDisplays();
      }
      return;
    }

    if ($target.hasClass("clear")) {
      resetCalculator();
      updateDisplays();
      return;
    }

    if ($target.hasClass("delete")) {
      deleteLastChar();
      updateDisplays();
    }
  });

  // Add keyboard support
  $(document).on("keydown", function (event) {
    handleKeyboardInput(event.key);
  });

  // Initialize display
  updateDisplays();
});
