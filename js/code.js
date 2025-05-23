const urlBase = 'http://group12cop4331.xyz/LAMPAPI';
const extension = 'php';

let userId = 0;
let firstName = "";
let lastName = "";
const ids = [];

// ----------------
//  AUTHENTICATION
// ----------------

function doLogin() {
    userId = 0;
    firstName = "";
    lastName = "";

    let login = document.getElementById("loginName").value;
    let password = document.getElementById("loginPassword").value;

    let hash = md5(password);

    let tmp = {login: login, password: hash};
    
    let jsonPayload = JSON.stringify( tmp );

    let url = urlBase + '/Login.' + extension;

    let xhr = new XMLHttpRequest();
    xhr.open("Post", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    try {
        xhr.onreadystatechange = function() {
             if (this.readyState == 4 && this.status == 200) {
                let jsonObject = JSON.parse(xhr.responseText);
                userId = jsonObject.id;

                if (userId < 1) {
                    document.getElementById("loginResult").innerHTML = "Invalid username or password.";
                    return;
                }

                firstName = jsonObject.firstName;
                lastName = jsonObject.lastName;

                saveCookie();
                window.location.href = "contacts.html";
            }
        };

        xhr.send(jsonPayload);
    }
    catch (err) {
        document.getElementById("loginResult").innerHTML = "Error: " + err.message;
    }
    
}


function doLogout() {
    userId = 0;
    firstName = "";
    lastName = "";
    document.cookie = "firstName= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
    window.location.href = "index.html";
}

function doRegister() {
    firstName = document.getElementById("firstName").value;
    lastName = document.getElementById("lastName").value;
    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;

    let hash = md5(password);

    let tmp = {
        firstName: firstName,
        lastName: lastName,
        login: username,
        password: hash
    };

    let jsonPayload = JSON.stringify(tmp);
    let url = urlBase + '/SignUp.' + extension;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    try {
        xhr.onreadystatechange = function () {
            if (this.readyState != 4) return;

            if (this.status == 409) {
                
                document.getElementById("signupResult").innerHTML = "Username already exists.";
                return;
            }

            if (this.status == 200) {
                let jsonObject = JSON.parse(xhr.responseText);
                userId = jsonObject.id;
                firstName = jsonObject.firstName;
                lastName = jsonObject.lastName;

                saveCookie();
                document.getElementById("signupResult").innerHTML = "Account created!";
                window.location.href = "contacts.html";
            }
        };

        xhr.send(jsonPayload);
    }
    catch (err) {
        document.getElementById("signupResult").innerHTML = "Error: " + err.message;
    }
}


// -------------------
//  COOKIE MANAGEMENT
// -------------------

function saveCookie() {
    let minutes = 20;
    let date = new Date();
    date.setTime(date.getTime() + (minutes * 60 * 1000));
    document.cookie = "firstName=" + firstName + 
                      ",lastName=" + lastName + 
                      ",userId=" + userId + 
                      ";expires=" + date.toGMTString();

}
function readCookie() {
    userId = -1;
    let data = document.cookie;
    let splits = data.split(",");

    for (let i = 0; i < splits.length; i++) {
        let thisOne = splits[i].trim();
        let tokens = thisOne.split("=");

        if (tokens[0] === "firstName") firstName = tokens[1];
        else if (tokens[0] === "lastName") lastName = tokens[1];
        else if (tokens[0] === "userId") userId = parseInt(tokens[1].trim());
    }

    if (userId < 0) {
        window.location.href = "index.html";
    } else {
        document.getElementById("userName").innerHTML = 
            "Welcome, " + firstName + " " + lastName + "!";
    }
}

// --------------------
//  CONTACT MANAGEMENT
// --------------------

function addContact() {

}

function loadContacts() {


}
function editRow(id) {

}

function saveRow(no) {

}

function deleteRow(no) {
    
}

