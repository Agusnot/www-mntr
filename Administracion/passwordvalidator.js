<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>

var minLength = 8;             // Minimum length of password
var maxLength = 16;            // Maximum length of password
var noSpecialChars = true;     // Sets if special characters (punctuation etc.) can be in password
var isPasswordRequired = true;  // Sets if the password is a required field
var showTip = true;             // Show a tip to users if their password is not perfect

// Custom strings for personalisation or i18n
var strRequired = "Campos Obligatorios";     // Displays when nothing is entered & password is required
var strTooShort = "Minimo 8 caracteres";   // Displays when password is less than minLength 
var strTooLong = "Maximo 16 caracteres";      // Displays when password is too long
var strSpecialChars = "Caracteres especiales no son validos";     // Displays when user enters special chars
var strWeak = "Su Contrase&ntilde;a es demasiado Facil!";       // Displays when password is weak strength
var strMedium = "Su Contrase&ntilde;a puede ser mejor";   // Displays when password is medium strength
var strStrong = "Su Contrase&ntilde;a es Segura!";          // Displays when password is perfect

// UI settings
var BackgroundColor = "#FFFFFF";     // Background color of validator 
var TextColor = "#FF0000";           // Text color of validator 
var TextFontFamily = "Verdana,Arial"; // Font Family
var TextSize = "smaller";               // Text font size
var TextBold = true;              // Is text bold?


/*************** End of user specified settings **********/
/*************** DO NOT EDIT BELOW THIS LINE ****************/


var tip = 'Consejos para su contrase&ntilde;a\\n1.Debe poseer entre '+minLength+' y '+maxLength+' Caracteres \\n2.No deberia ser una palabra del diccionario comun, ya que es facil de adivinar!\\n3.Debe poseer almenos una letra mayuscula, una minuscula y almenos un digito.';

/************** Create the validator **************/
function createPasswordValidator(elementToValidate)
	{	
		// Initialise display
		var validatorStyle = '<style type="text/css"> .pwdvalid { background-color:'+BackgroundColor+'; color:'+TextColor+'; font-family:'+TextFontFamily+'; font-size:'+TextSize+';';
		if(TextBold)
			validatorStyle += 'font-weight: bold;';
		validatorStyle +='}</style>';
		document.write(validatorStyle);
		
		// Get the element to validate
		var elm;
		if(!(elm = document.getElementById(elementToValidate)))
		{
			alert('El programa no pudo encontrar la etiqueta id='+elementToValidate);
			return;
		}
		
		// Create visual output
		var output = '<div id="_pwdvalid'+elementToValidate+'" class="pwdvalid">&nbsp;</div>';
		document.write(output);
		
		// Register event handlers
		// Use quirksmode idea for flexible registration by copying existing events
		// onKeyUp
		var oldEventCode = (elm.onkeyup) ? elm.onkeyup : function () {};
		elm.onkeyup = function () {oldEventCode(); validatePassword(elm.id)};
		// onmouseout
		oldEventCode = (elm.onmouseout) ? elm.onmouseout : function () {};
		elm.onmouseout = function() {oldEventCode(); validatePassword(elm.id)};		
	}
	
function validatePassword(elementToValidate) 
	{
		var elm;
		if(!(elm = document.getElementById(elementToValidate)))
		{
			return;
		}
		var passwordDiv = document.getElementById("_pwdvalid"+elementToValidate);
		var passwordString = elm.value;
		if(passwordString.length == 0)
		{
			passwordDiv.innerHTML = strRequired;
			return;
		}
		if(passwordString.length < minLength)
		{
			passwordDiv.innerHTML = strTooShort;
			return;
		}
		if(passwordString.length > maxLength)
		{
			passwordDiv.innerHTML = strTooLong;
			return;
		}
		// Match special characters
		if(passwordString.match(/\W/))
		{
			passwordDiv.innerHTML = strSpecialChars;
			return;
		}			
		var strength = 0;
		// Match upper case characters
		if(passwordString.match(/[a-z]/))
		{
			strength++;
		}
		// Match lower case characters
		if(passwordString.match(/[A-Z]/))
		{
			strength++;
		}
		// Match digits
		if(passwordString.match(/\d/))
		{
			strength++;
		}		
		switch(strength)
		{
			case 1: passwordDiv.innerHTML = strWeak;
					displayTip(passwordDiv);
					break;
			case 2: passwordDiv.innerHTML = strMedium;
					displayTip(passwordDiv);
					break;
			case 3: passwordDiv.innerHTML = strStrong;
					break;
		}				
	}
		
	function displayTip(div)
	{		
		// Show tip
		if(showTip)		
			div.innerHTML += '&nbsp;'+'<a href="javascript:alert(\''+tip+'\');" style="font-size:smaller; text-decoration: none">Tip</a>';
	}

							
							 
		
	