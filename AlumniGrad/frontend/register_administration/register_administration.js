const isEmpty = field => {
	return !field || !field.trim();
}

const isValidUsername = username => {
	if (isEmpty(username)) {
		return "Потребителското име е задължително поле.";
    }
    
	if (username.length < 3 || username.length > 50) {
		return "Потребителското име трябва да е между 3 и 50 символа.";
	}

	return "";
}

const isValidFirstName = firstName => {
    if (isEmpty(firstName)) {
		return "Името е задължително поле."
    }

    if (firstName.length > 50) {
		return "Дължината на името трябва да е до 50 символа.";
    }

    return "";
}

const isValidFamilyName = familyName => {
    if (isEmpty(familyName)) {
		return "Фамилията е задължително поле.";
    }

    if (familyName.length > 50) {
		return "Дължината на фамилията трябва да е до 50 символа.";
    }

    return "";
}

const isValidEmail = email => {
    if (isEmpty(email)) {
		return "Имейлът е задължително поле.";
	}

	if (email.length > 50) {
		return "Дължината на имейла трябва да е до 50 символа.";
	}
    const validEmailFormat = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
	
	if(!validEmailFormat.test(email))
      return "Невалиден имейл.";

    return "";
}

const isValidPassword = password => {
    if (isEmpty(password)) {
		return "Паролата е задължително поле.";
    }

	if (password.length < 8) {
		return "Дължината на паролата трябва да е поне 8 символа.";
    }
    
	if (password.length > 30) {
		return "Дължината на паролата трабва да е до 30 символа.";
	}
	
	const validPasswordFormat = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9!@#\$%\^\&*\)\(+=._-]{8,30}$/;
    
	if (!validPasswordFormat.test(password)) {
		return "Невалидна парола, паролата трябва да съдържа поне една главна буква, поне една малка буква и поне една цифра.";
    }
    
	return "";
}

const isValidPosition = position => {
    if (isEmpty(position)) {
		return "Длъжността е задължително поле.";
	}

    if (position.length > 50) {
        return "Дължината на полето длъжност трябва де е до 50 символа.";
    }
    
    return "";
}

const validateUsername = event => {
	username = event.target.value;
	document.querySelector("p[id='errUsername']").innerText = isValidUsername(username);
};

const validateFirstName = event => {
	firstName = event.target.value;
	document.querySelector("p[id='errFirstName']").innerText = isValidFirstName(firstName);
};

const validateFamilyName = event => {
	familyName = event.target.value;
	document.querySelector("p[id='errFamilyName']").innerText = isValidFamilyName(familyName);
};

const validateEmail = event => {
	email = event.target.value;
	document.querySelector("p[id='errEmail']").innerText = isValidEmail(email);
};

const validatePassword = event => {
    password = event.target.value;
    document.querySelector("p[id='errPassword']").innerText = isValidPassword(password);
}

const validatePosition = event => {
    position = event.target.value;
    document.querySelector("p[id='errPosition']").innerText = isValidPosition(position);
}

document.querySelector("input[name='username']").addEventListener('keyup', validateUsername); 
document.querySelector("input[name='first-name']").addEventListener('keyup', validateFirstName);
document.querySelector("input[name='family-name']").addEventListener('keyup', validateFamilyName);
document.querySelector("input[name='email']").addEventListener('keyup', validateEmail);
document.querySelector("input[name='password']").addEventListener('keyup', validatePassword);
document.querySelector("input[name='position']").addEventListener('keyup', validatePosition);

const validate = (errors, fields) => {
	
	for (const field of fields) {
		if(field == "") {
			document.getElementById('user-message').innerText = "Моля, попълнете всички полета.";
			return false;
		}
	}
	
	for (const err of errors) {
		if (err != "") {
			document.getElementById('user-message').innerText = "Mоля, въведете валидни данни.";
			return false;
		}
	}
	
	document.getElementById('user-message').innerText = "";
	return true;
}

const onFormSubmitted = event => {
    event.preventDefault();
    const formElement = event.target;
	
    const formData = {
        username: formElement.querySelector("input[name='username']").value,
		firstName: formElement.querySelector("input[name='first-name']").value,
		familyName: formElement.querySelector("input[name='family-name']").value,
		email: formElement.querySelector("input[name='email']").value,
        password: formElement.querySelector("input[name='password']").value,
        position: formElement.querySelector("input[name='position']").value,
		role: "administration"
    };
	
	const fields = [
        formData.username,
		formData.firstName,
		formData.familyName,
		formData.email, 
        formData.password,
        formData.position,	
		formData.role
	];
	
	const errors = [
        formElement.querySelector("p[id='errUsername']").innerText, 
		formElement.querySelector("p[id='errFirstName']").innerText,
		formElement.querySelector("p[id='errFamilyName']").innerText,
		formElement.querySelector("p[id='errEmail']").innerText,
        formElement.querySelector("p[id='errPassword']").innerText,	
        formElement.querySelector("p[id='errPosition']").innerText	
		];

		const settings = {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			}, 
			body: `data=${JSON.stringify(fields)}`
		};

    if (validate(errors, fields)) {
		fetch("../../backend/register_administration/register_administration.php", settings)
			.then(response => response.json())
			.then(response => {
				if (response.success === true) {
					var popup = document.getElementById("successful-registration");
					var login = document.getElementsByClassName("login")[0];
					popup.style.display = "block";

					login.onclick = function() {
						popup.style.display = "none";
						window.location.replace("../login/login.html");
					}
				}
				else {
					document.getElementById('user-message').innerText = response.error[0];
				}
			})
			.catch(error => console.log(error))
	}
}

document.getElementById('register-form').addEventListener('submit', onFormSubmitted);