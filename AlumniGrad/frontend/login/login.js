const onFormSubmitted = event => {
    event.preventDefault();

    const formData = {
        username: document.getElementById("username").value,
        password: document.getElementById("password").value
    }

    const fields = [
        formData.username,
        formData.password
    ];

    const settings = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        }, 
        body: `data=${JSON.stringify(fields)}`
    };

    fetch("../../backend/login/login.php", settings)
        .then(response => response.json())
        .then(response => {
            if (response.success === true) {
                    // check if it is student or administration
                if (response.role === "student"){
                    location.replace("../dashboard_student/dashboard_student.html");
                }
                else if (response.role === "administration"){
                    location.replace("../dashboard_administration/dashboard_administration.html");
                }
            }
            else {
                document.getElementById('user-message').innerText = response.error[0];
            }
        })
        .catch(error => console.log(error));
}

document.getElementById('login-form').addEventListener('submit', onFormSubmitted);