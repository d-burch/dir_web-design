/*************
Form Validation
1. Add class for borders around required field to show whether valid or not
2. If no text entered upon leaving field a notice shows up
3. If maximum character count is reach a notice shows up
4. If invalid character is entered it is removed and a notice shows up

**Note the .textLength property was only working in Firefox but not other
browsers.  Replaced with .value.length
**************/

//Need to update if more than one form on a page
var form = document.getElementsByTagName("form")[0];

//When input loses focus
function required(loseFocusEvent) {
	var currentInput = loseFocusEvent.target;
	if (currentInput.tagName === "INPUT" && currentInput.hasAttribute("required") && currentInput.getAttribute("type") !== "submit") {	
		//Add class of highlight for borders done in CSS
		currentInput.className = "highlight";
		//Change class to show or hide "This field is required"
		var requiredParent = currentInput.parentNode;
		var requiredNotice = requiredParent.getElementsByClassName("field-required")[0];
		if (currentInput.value.length == 0) {
			requiredNotice.classList.add("on");
		} else {
			requiredNotice.classList.remove("on");
		}
	}	
}

//On keyup
function characters(keyupEvent) {
	var current = keyupEvent.target;
	//Show "Maximum characters:"
	if (current.hasAttribute("maxlength")) {
		var maxLength = current.getAttribute("maxlength");
		var maxNumber = parseInt(maxLength); //Convert to number
		var lText = current.value.length;
		var inputDiv = current.parentNode;
		var maxNotice = inputDiv.getElementsByClassName("max-characters")[0];
		if (lText >= maxNumber) {
			maxNotice.classList.add("on");
			maxNotice.innerHTML = "Maximum characters: " + maxLength;
		} else {
			maxNotice.innerHTML = "";
			maxNotice.classList.remove("on");
		}
	}
	//Remove invalid characters, show notice
	var regEx = new RegExp('[^A-Za-z0-9 /!@,.?;:\'\"&]+', 'g');
	var typedText = current.value;
	var changedText = typedText.replace(regEx, "");
	current.value = changedText;
	var changedTextLength = current.value.length;
	var characterNotice = inputDiv.getElementsByClassName("invalid-character")[0];
	if (changedTextLength < lText) {
		characterNotice.classList.add("on");
	} else if (keyupEvent.keyCode == 16) {
		//do nothing
	} else {
		characterNotice.classList.remove("on");
	}
}


//Add one event listener on form instead of every input field
form.addEventListener("focusout", required, false); //focusout bubbles, blur does not
form.addEventListener("keyup", characters, false);


