const isEmpty = (field) => {
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
		return "Дължината на паролата трябва да е до 30 символа.";
	}
	
	const validPasswordFormat = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9!@#\$%\^\&*\)\(+=._-]{8,30}$/;
    
	if (!validPasswordFormat.test(password)) {
		return "Невалидна парола, паролата трябва да съдържа поне една главна буква, поне една малка буква и поне една цифра.";
    }
    
	return "";
}

const isValidFacultyNumber = facultyNumber => {
    if(isEmpty(facultyNumber)){
        return "Факултетният номер е задължително поле.";
    }

    let flag = true;
    for (let i = 0; i < facultyNumber.length; i++){
        if (isNaN(facultyNumber[i])){
            flag = false;
            break;
        }  
    }

    if(facultyNumber.length < 5 || facultyNumber.length > 9 || !flag){
        return "Дължината на факултетния номер трябва да е между 5 и 9 цифри.";
    }

    return "";
}

const isValidMajor = major => {
    if(isEmpty(major)){
        return "Специалността е задължително поле.";
    } 

    if (major.length > 30) {
		return "Дължината на специалността трябва да е до 30 символа.";
    }

    return "";
}

const isValidPhoneNumber = phoneNumber => {
    if(isEmpty(phoneNumber)){
        return "Въвеждането на телефонен номер е задължително.";
    } 

    let flag = false;

    if(phoneNumber.length === 10){
        for(let i = 0; i < phoneNumber.length; i++){
            if(phoneNumber[i] < '0' || phoneNumber[i] > '9'){
                flag = false;
                break;
            }
            flag = true;
        }
    }

    if (flag === false) {
		return "Невалиден телефонен номер."
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

const validateFacultyNumber = event => {
    facultyNumber = event.target.value;
    document.querySelector("p[id='errFacultyNumber']").innerText = isValidFacultyNumber(facultyNumber);
}

const validateMajor = event => {
    major = event.target.value;
    document.querySelector("p[id='errMajor']").innerText = isValidMajor(major);
}

const validatePhoneNumber = event => {
    phoneNumber = event.target.value;
    document.querySelector("p[id='errPhoneNumber']").innerText = isValidPhoneNumber(phoneNumber);
}

document.querySelector("input[name='username']").addEventListener('keyup', validateUsername); 
document.querySelector("input[name='first-name']").addEventListener('keyup', validateFirstName);
document.querySelector("input[name='family-name']").addEventListener('keyup', validateFamilyName);
document.querySelector("input[name='email']").addEventListener('keyup', validateEmail);
document.querySelector("input[name='password']").addEventListener('keyup', validatePassword);
document.querySelector("input[name='faculty-number']").addEventListener('keyup', validateFacultyNumber);
document.querySelector("input[name='major']").addEventListener('keyup', validateMajor);
document.querySelector("input[name='phone-number']").addEventListener('keyup', validatePhoneNumber);


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

const onFormSubmitted = (event) => {
    event.preventDefault();
   
    const formElement = event.target;
	
    const formData = {
        username: formElement.querySelector("input[name='username']").value,
		firstName: formElement.querySelector("input[name='first-name']").value,
		familyName: formElement.querySelector("input[name='family-name']").value,
		email: formElement.querySelector("input[name='email']").value,
        password: formElement.querySelector("input[name='password']").value,
        facultyNumber: formElement.querySelector("input[name='faculty-number']").value,
        major: formElement.querySelector("input[name='major']").value,
        degree: formElement.querySelector("select[name='degree']").value,
        phoneNumber: formElement.querySelector("input[name='phone-number']").value,
		role: "student"
    };
	
	const fields = [
        formData.username,
		formData.firstName,
		formData.familyName,
		formData.email, 
        formData.password,
        formData.facultyNumber,
        formData.major,
        formData.degree,
        formData.phoneNumber,	
		formData.role
	];
	
	const errors = [
        formElement.querySelector("p[id='errUsername']").innerText, 
		formElement.querySelector("p[id='errFirstName']").innerText,
		formElement.querySelector("p[id='errFamilyName']").innerText,
		formElement.querySelector("p[id='errEmail']").innerText,
        formElement.querySelector("p[id='errPassword']").innerText,	
        formElement.querySelector("p[id='errFacultyNumber']").innerText,
        formElement.querySelector("p[id='errMajor']").innerText,
        formElement.querySelector("p[id='errDegree']").innerText,
        formElement.querySelector("p[id='errPhoneNumber']").innerText				
        ];

    const settings = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        }, 
        body: `data=${JSON.stringify(fields)}`
    };

    if (validate(errors, fields)){
        fetch("../../backend/register_student/register_student.php", settings)
            .then(response=>response.json())
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
            .catch(error => console.log(error));
    }
}

document.getElementById('register-form').addEventListener('submit', onFormSubmitted);