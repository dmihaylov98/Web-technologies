fetch("../../backend/check_session/check_session.php", {method: "GET"})
    .then(response => response.json())
    .then(response => {
        if (response.success == false) {
            location.replace("../login/login.html");
        }})
    .catch(error => console.log(error));


window.addEventListener("storage", (event) => {
    if (event.key == "student-logout") {
        location.replace("../login/login.html");
    }
})

const settings = {
    method: 'GET',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
    }
};

const showData = (data) => {
    document.getElementById("name-surname").innerHTML += (data.name + " ");
    document.getElementById("name-surname").innerHTML += data.surname;
    
    document.getElementById("fn").innerHTML += data.fn;
    document.getElementById("fictive-fn").innerHTML = data.fn;
    
    document.getElementById("major").innerHTML += data.major;
    document.getElementById("degree").innerHTML += data.degree;

    document.getElementById("mark").innerHTML += data.mark;
    document.getElementById("fictive-mark").innerHTML += data.mark;
}


fetch('../../backend/dashboard_student/dashboard_student.php', settings)
    .then(response => response.json())
    .then(response => {
        if (response.success === true) {
            showData(response.data);
            if (response.data.participate == true) {
                document.getElementById("participate1").disabled = true;
                document.getElementById("participate1").checked = true;
                document.getElementById("participate2").disabled = true;
                document.getElementById("participate-btn").hidden = true;
                document.getElementById("togaandhatdiv").style.display = "block";

                if(response.data.mark >= 5.50){
                    document.getElementById("speechdiv").style.display = "block";
                }
                else {
                    document.getElementById("speechdiv").style.display = "none";
                }

                fetch("../../backend/dashboard_student/dashboard_student_read_extra_info.php",settings)
                    .then(response => response.json())
                    .then(response => {
                         if(response.success == true){
                             let flag_check = true;

                             if(response.data.toga != null){
                                document.getElementById("toga").value = response.data.toga;
                                document.getElementById("toga").disabled = true;
                             }
                             else {
                                 flag_check = false;
                             }
                            
                             if(response.data.hat != null){
                                document.getElementById("hat1").disabled = true;
                                document.getElementById("hat2").disabled = true;
                                if(response.data.hat == true){
                                    document.getElementById("hat1").checked = true;
                                }
                                else if(response.data.hat == false){
                                    document.getElementById("hat2").checked = true;
                                }
                             }
                             else {
                                 flag_check = false;
                             }

                             if(response.data.places != null){
                                document.getElementById("places").value = response.data.places;
                                document.getElementById("places").disabled = true;
                             }
                             else {
                                 flag_check = false;
                             }

                             if(document.getElementById("speechdiv").style.display == "block"){
                                 if(response.data.speech != null){
                                    document.getElementById("speech1").disabled = true;
                                    document.getElementById("speech2").disabled = true;
                                    if(response.data.speech == true) {
                                        document.getElementById("speech1").checked = true;
                                    }
                                    else if(response.data.speech == false){
                                        document.getElementById("speech2").checked = true;
                                    }
                                 }
                                 else {
                                    flag_check = false;
                                }
                             }
                            

                             if(flag_check == true){
                                 document.getElementById("togaandhat-btn").hidden = true;
                             }

                         }
                })
                .catch(error => console.log(error));

            }
            else if(response.data.participate == false){
                document.getElementById("participate1").disabled = true;
                document.getElementById("participate2").disabled = true;
                document.getElementById("participate2").checked = true;
                document.getElementById("participate-btn").hidden = true;
                document.getElementById("no-part").innerHTML = "Благодарим Ви за отговора! Моля, заповядайте в отдел \"Студенти\", за да получите дипломата си.";
            }
        }
    })
    .catch(res => console.log(res));

const logout = (event) => {
    event.preventDefault();

    fetch("../../backend/logout/logout.php", {method: 'GET'})
        .then(response => response.json())
        .then(response => {
            if (response.success === true) {
                location.replace("../login/login.html");
                localStorage.setItem("student-logout", "logout" + Math.random());
            }
        })
        .catch(error => console.log(error));
}

document.getElementById("logout-btn").addEventListener('click', logout);

const addParticipant = event => {
   event.preventDefault();
   
   let participate = "";
   let facnum = document.getElementById("fictive-fn").innerHTML;
   let ficMark = document.getElementById("fictive-mark").innerHTML;
   let flag = false;

   if(document.getElementById("participate1").checked){
       participate = document.getElementById("participate1").value;
       document.getElementById("participate1").disabled = true;
       document.getElementById("participate2").disabled = true;
       document.getElementById("participate-btn").hidden = true;
       flag = true;
   }
   else if(document.getElementById("participate2").checked){
       participate = document.getElementById("participate2").value;
       document.getElementById("participate1").disabled = true;
       document.getElementById("participate2").disabled = true;
       document.getElementById("participate-btn").hidden = true;
       document.getElementById("no-part").innerHTML = "Благодарим Ви за отговора! Моля, заповядайте в отдел \"Студенти\", за да получите дипломата си.";
       flag = true;
   }
   
   if(participate == true){
       document.getElementById("togaandhatdiv").style.display = "block";
       if(ficMark >= 5.50) document.getElementById("speechdiv").style.display = "block";
   }
   else if(participate == false){
    document.getElementById("togaandhatdiv").style.display = "none";
    if(ficMark < 5.50) document.getElementById("speechdiv").style.display = "none";
   }

   const fields = [
       participate,
       facnum
   ];

   const settings1 = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        }, 
        body: `data=${JSON.stringify(fields)}`
    };

    if(flag == true){
        fetch("../../backend/dashboard_student/dashboard_student_add_participate.php", settings1)
            .then(response => response.json()) 
            .catch(error => console.log(error));
    }
};

document.getElementById("participate-btn").addEventListener("click", addParticipant);


const addExtraInfo = event => {
    event.preventDefault();

    let facnum = document.getElementById("fictive-fn").innerHTML;
    let flag3 = false;
    let ficMark = document.getElementById("fictive-mark").innerHTML;
    if(ficMark < 5.50){
        flag3 = true;
    }

    let toga = document.getElementById("toga").value;
    
    let hat = "";
    let flag1 = false;
    if(document.getElementById("hat1").checked){
        hat = document.getElementById("hat1").value;
        flag1 = true;
    }
    else if(document.getElementById("hat2").checked){
        hat = document.getElementById("hat2").value; 
        flag1 = true;   
    }

    let places = document.getElementById("places").value;

    let speech = false;
    let flag2 = false;
    if(document.getElementById("speech1").checked){
        speech = document.getElementById("speech1").value;
        flag2 = true;
    }
    else if(document.getElementById("speech2").checked){
        speech = document.getElementById("speech2").value; 
        flag2 = true;    
    }

    const fields = [
        facnum,
        toga,
        hat,
        places,
        speech
    ];

    const settings1 = {
         method: 'POST',
         headers: {
             'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
         }, 
         body: `data=${JSON.stringify(fields)}`
     };

    if(flag1 && (flag2 || flag3)){
        document.getElementById("toga").disabled = true;
        document.getElementById("hat1").disabled = true;
        document.getElementById("hat2").disabled = true; 
        document.getElementById("places").disabled = true;
        document.getElementById("speech1").disabled = true;
        document.getElementById("speech2").disabled = true; 
        document.getElementById("togaandhat-btn").hidden = true;

        fetch("../../backend/dashboard_student/dashboard_student_add_extrainfo.php", settings1)
            .then(response => response.json())
            .catch(error => console.log(error)); 
    }
}

document.getElementById("togaandhat-btn").addEventListener("click", addExtraInfo);