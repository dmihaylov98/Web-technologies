fetch("../../backend/check_session/check_session.php", {method: "GET"})
    .then(response => response.json())
    .then(response => {
        if (response.success == false) {
            location.replace("../login/login.html");
        }})
    .catch(error => console.log(error));

    window.addEventListener("storage", (event) => {
        if (event.key == "administration-logout") {
            location.replace("../login/login.html");
        }
    })

const settingsGet = {
    method: 'GET',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
    }
};

const showData = (data) => {
    document.getElementById("name-surname").innerHTML += (data.name + " ");
    document.getElementById("name-surname").innerHTML += data.surname;
}

fetch('../../backend/dashboard_administration/dashboard_administration.php', settingsGet)
    .then(response => response.json())
    .then(response => {
        if (response.success === true) {
            showData(response.data);
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
                localStorage.setItem("administration-logout", "logout" + Math.random());
            }
        })
        .catch(error => console.log(error));
}
    
document.getElementById("logout-btn").addEventListener('click', logout);

let cnt1 = -1;

const showTogaForm = event => {
    event.preventDefault();
      
    ++cnt1;

    if(cnt1 % 2 == 0)document.getElementById("togaForm").style.display = "block";
    else document.getElementById("togaForm").style.display = "none";

}

document.getElementById("togarettitle").addEventListener('click', showTogaForm);

let cnt2 = -1;

const showPrizeForm = event => {
    event.preventDefault();
      
    ++cnt2;

    if(cnt2 % 2 == 0)document.getElementById("prizeForm").style.display = "block";
    else document.getElementById("prizeForm").style.display = "none";

}

document.getElementById("prizetitle").addEventListener('click', showPrizeForm);


const insertTogaReturn = event => {
    event.preventDefault();

    let fn1 = document.getElementById("fn1").value;

    const fields = [
        fn1
    ];
 
    const settingsPost = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        }, 
        body: `data=${JSON.stringify(fields)}`
    };

    fetch("../../backend/dashboard_administration/dashboard_adm_togaRet.php", settingsPost)
        .then(response => response.json())
        .then(response => {
            if (response.success === true) {
                document.getElementById("toga-error").innerHTML = response.message;
                document.getElementById("fn1").value = "";
                setTimeout(() => {
                    document.getElementById("toga-error").innerHTML = "";
                }, 3000);
            }
            else {
                document.getElementById("toga-error").innerHTML = response.error[0];
                document.getElementById("fn1").value = "";
                setTimeout(() => {
                    document.getElementById("toga-error").innerHTML = "";
                }, 3000);
            }
        })
        .catch(res => console.log(res))
}

document.getElementById("togaForm").addEventListener("submit", insertTogaReturn);

const insertPrize = event => {
    event.preventDefault();

    let fn2 = document.getElementById("fn2").value;
    let prize1 = document.getElementById("prize1").value;

    const fields = [
        fn2,
        prize1
    ];
 
    const settingsPost = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        }, 
        body: `data=${JSON.stringify(fields)}`
    };

    fetch("../../backend/dashboard_administration/dashboard_adm_prize.php", settingsPost)
        .then(response => response.json())
        .then(response => {
            if (response.success === true) {
                document.getElementById("prize-error").innerHTML = response.message;
                document.getElementById("fn2").value = "";
                document.getElementById("prize1").value = "";
                setTimeout(() => {
                    document.getElementById("prize-error").innerHTML = "";
                }, 3000);
            }
            else {
                document.getElementById("prize-error").innerHTML = response.error[0];
                document.getElementById("fn2").value = "";
                document.getElementById("prize1").value = "";
                setTimeout(() => {
                    document.getElementById("prize-error").innerHTML = "";
                }, 3000);
            }
        })
        .catch(res => console.log(res))
}

document.getElementById("prizeForm").addEventListener("submit", insertPrize);

let cnt3 = -1;

const showStats = event => {
    event.preventDefault();
      
    ++cnt3;

    if(cnt3 % 2 == 0){
        fetch("../../backend/dashboard_administration/stats.php", settingsGet)
           .then(response => response.json())
           .then(response => {
               if(response.res1.success == true && response.res2.success == true){
                document.getElementById("totReg").style.display = "block";
                document.getElementById("plusReg").style.display = "block";
                document.getElementById("negReg").style.display = "block";
                document.getElementById("stat-guests").style.display = "block";
                document.getElementById("totReg").innerHTML += response.res1.data.all;
                document.getElementById("plusReg").innerHTML += response.res1.data.attend;
                document.getElementById("negReg").innerHTML += response.res1.data.miss;
                document.getElementById("stat-guests").innerHTML += response.res2.data;
               }
           })
          .catch(res => console.log(res)); 
    }
    else {
        document.getElementById("totReg").innerHTML = "Общо регистрирани: ";
        document.getElementById("plusReg").innerHTML = "Заявили присъствие: ";
        document.getElementById("negReg").innerHTML = "Незаявили присъствие: ";
        document.getElementById("stat-guests").innerHTML = "Брой присъстващи студенти и гости в залата: ";
        document.getElementById("totReg").style.display = "none";
        document.getElementById("plusReg").style.display = "none";
        document.getElementById("negReg").style.display = "none";
        document.getElementById("stat-guests").style.display = "none";
    }

}

document.getElementById("stat-reg").addEventListener('click', showStats);

const createStringRow = (obj, flag) => {
    let row = "";

    for(const prop in obj) {
        row += (obj[prop] + ",");
    }

    let res = "";

    if (flag == true) res = row.slice(0, -1);
    else res = row;
    res += "\n";

    return res;
}

// exportParticipants -> "exportParticipants.php", "Име,Фамилия,ФН,Специалност,Степен\n", "participantsInCeremony.csv", true
// exportSignature -> "exportSignature.php", "Име,Фамилия,ФН,Подпис\n", "signatureList.csv", false
// exportSpeeches -> "exportSpeeches.php", "Име,Фамилия,ФН\n", "speechesList.csv", true
// exportPrizes -> "exportPrizes.php", "Име,Фамилия,ФН,Награда\n", "prizesList.csv", true
// exportExc -> "exportExc.php", "Име,Фамилия,ФН,Оценка\n", "excellentStudentsList.csv", true
// exportHats -> "exportHats.php", "Име,Фамилия,ФН\n", "hatsList.csv", true
// exportTogaGet -> "exportTogaGet.php", "Име,Фамилия,ФН,Размер,Подпис\n", "togaGetList.csv", false
// exportTogaRet -> "exportTogaRet.php", "Име,Фамилия,ФН\n", "togaNotReturnedList.csv", true

const exportList = (event, phpFileName, headerRow, downloadFileName, flag) => {
   event.preventDefault();

   fetch(phpFileName, {method: "GET"})
        .then(response => response.json())
        .then(response => {
            if (response.success == true) {
                const rows = response.data;
                let csvContent = "data:text/csv;charset=utf-8,";
                csvContent += headerRow;

                rows.forEach((elem) => {
                    const elemString = createStringRow(elem, flag);
                    csvContent += elemString;
                })
            
                const encodedUri = encodeURI(csvContent);
                let link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", downloadFileName);
                document.body.appendChild(link);

                link.click();
            }
        })
        .catch(error => console.log(error));
}

document.getElementById("btn-partic").addEventListener('click', (event) => {
    exportList(event, "../../backend/dashboard_administration/exportParticipants.php", "Име,Фамилия,ФН,Специалност,Степен\n", "participantsInCeremony.csv", true);
});

document.getElementById("btn-sign").addEventListener('click', (event) => {
    exportList(event, "../../backend/dashboard_administration/exportSignature.php", "Име,Фамилия,ФН,Подпис\n", "signatureList.csv", false);
});

document.getElementById("btn-speeches").addEventListener('click', (event) => {
    exportList(event, "../../backend/dashboard_administration/exportSpeeches.php", "Име,Фамилия,ФН\n", "speechesList.csv", true);
});

document.getElementById("btn-prizes").addEventListener('click', (event) => {
    exportList(event, "../../backend/dashboard_administration/exportPrizes.php", "Име,Фамилия,ФН,Награда\n", "prizesList.csv", true);
});

document.getElementById("btn-excellent").addEventListener('click', (event) => {
    exportList(event, "../../backend/dashboard_administration/exportExc.php", "Име,Фамилия,ФН,Оценка\n", "excellentStudentsList.csv", true);
});

document.getElementById("btn-hats").addEventListener('click', (event) => {
    exportList(event, "../../backend/dashboard_administration/exportHats.php", "Име,Фамилия,ФН\n", "hatsList.csv", true);
});

document.getElementById("btn-togaGet").addEventListener('click', (event) => {
    exportList(event, "../../backend/dashboard_administration/exportTogaGet.php", "Име,Фамилия,ФН,Размер,Подпис\n", "togaGetList.csv", false);
});

document.getElementById("btn-togaRet").addEventListener('click', (event) => {
    exportList(event, "../../backend/dashboard_administration/exportTogaRet.php", "Име,Фамилия,ФН\n", "togaNotReturnedList.csv", true);
});