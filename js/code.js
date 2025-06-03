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

    const login = document.getElementById("loginName").value;
    const password = document.getElementById("loginPassword").value;

    const tmp = { login: login, password: password };
    const jsonPayload = JSON.stringify(tmp);
    const url = urlBase + '/Login.' + extension;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    try {
        xhr.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                const jsonObject = JSON.parse(xhr.responseText);
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
            else if (this.readyState === 4 && this.status === 401) {
                document.getElementById("loginResult").innerHTML = "Invalid username or password.";
            }
        };
        xhr.send(jsonPayload);
    } catch (err) {
        document.getElementById("loginResult").innerHTML = "Error: " + err.message;
    }
}

function doLogout() {
    userId = 0;
    firstName = "";
    lastName = "";

    const url = urlBase + '/Logout.' + extension;
    const xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    try {
        xhr.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                document.cookie = "firstName= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
                window.location.href = "index.html";
            }
        };
        xhr.send(JSON.stringify({ userId: userId }));
    } catch (err) {
        console.log(err.message);
    }
}

function doRegister() {
    firstName = document.getElementById("firstName").value;
    lastName = document.getElementById("lastName").value;
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    const tmp = {
        FirstName: firstName,
        LastName: lastName,
        Login: username,
        Password: password
    };

    const jsonPayload = JSON.stringify(tmp);
    const url = urlBase + '/CreateUser.' + extension;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    try {
        xhr.onreadystatechange = function () {
            if (this.readyState !== 4) return;

            if (this.status === 409) {
                document.getElementById("signupResult").innerHTML = "Username already exists.";
                return;
            }

            if (this.status === 200) {
                const jsonObject = JSON.parse(xhr.responseText);

                document.getElementById("signupResult").innerHTML = "Account created!";
                window.location.href = "contacts.html";
            } else {
                const jsonObject = JSON.parse(xhr.responseText);
                document.getElementById("signupResult").innerHTML = jsonObject.error || "Registration failed.";
            }
        };
        xhr.send(jsonPayload);
    } catch (err) {
        document.getElementById("signupResult").innerHTML = "Error: " + err.message;
    }
}

// -------------------
//  COOKIE MANAGEMENT
// -------------------

function saveCookie() {
    const minutes = 20;
    const date = new Date();
    date.setTime(date.getTime() + (minutes * 60 * 1000));
    document.cookie = "firstName=" + firstName +
        ",lastName=" + lastName +
        ",userId=" + userId +
        ";expires=" + date.toGMTString();
}

function readCookie() {
    userId = -1;
    const data = document.cookie;
    const splits = data.split(",");

    for (let i = 0; i < splits.length; i++) {
        const thisOne = splits[i].trim();
        const tokens = thisOne.split("=");

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

// ----------------
// PAGE NAVIGATION
// ----------------

function clickLogin() {
    window.location.href = "index.html";
}

function clickRegister() {
    window.location.href = "signup.html";
}


// --------------------
//  CONTACT MANAGEMENT
// --------------------

function addContact() {
    const firstName = document.getElementById("contactTextFirst").value;
    const lastName = document.getElementById("contactTextLast").value;
    const phone = document.getElementById("contactTextNumber").value;
    const email = document.getElementById("contactTextEmail").value;
	console.log("Phone:", phone); 
    console.log("Email:", email);
    console.log("userId being used to add contact:", userId);
    const tmp = {
        FirstName: firstName,
        LastName: lastName,
        Phone: phone,
        Email: email,
        UserID: userId  // make sure this variable is set globally during login
    };

    const jsonPayload = JSON.stringify(tmp);
    const url = urlBase + '/CreateContact.' + extension;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    try {
        xhr.onreadystatechange = function () {
            if (this.readyState === 4) {
                if (this.status === 200) {
                    console.log("Contact added successfully");
                    document.getElementById("addMe").reset();
                    loadContacts();  // Refresh contact list
                } else {
                    console.error("Error adding contact:", xhr.responseText);
                    alert("Failed to add contact: " + xhr.responseText);
                }
            }
        };
        xhr.send(jsonPayload);
    } catch (err) {
        console.log("Request failed: " + err.message);
    }
}

/* Brett's helper functions */
function showContactForm() {
            document.getElementById("contactForm").style.display = "block";
}

function hideContactForm() {
    document.getElementById("contactForm").style.display = "none";
}

/* end helper functions */

function loadContacts() {
    let tmp = {
        search: "",
        userId: userId
    };

    let jsonPayload = JSON.stringify(tmp);
    let url = urlBase + '/FindContact.' + extension;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    try {
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                let jsonObject = JSON.parse(xhr.responseText);
                let text = "<table border='1'>";
                for (let i = 0; i < jsonObject.results.length; i++) {
                    ids[i] = jsonObject.results[i].ID;
                    text += "<tr id='row" + i + "'>";
                    text += "<td id='first_Name" + i + "'><span>" + jsonObject.results[i].FirstName + "</span></td>";
                    text += "<td id='last_Name" + i + "'><span>" + jsonObject.results[i].LastName + "</span></td>";
                    text += "<td id='email" + i + "'><span>" + jsonObject.results[i].Email + "</span></td>";
                    text += "<td id='phone" + i + "'><span>" + jsonObject.results[i].Phone + "</span></td>";
                    text += "<td>" +
                        "<button onclick='editRow(" + i + ")'>Edit</button>" +
                        "<button onclick='saveRow(" + i + ")' style='display:none;'>Save</button>" +
                        "<button onclick='deleteRow(" + i + ")'>Delete</button>" +
                        "</td>";
                    text += "</tr>";
                }
                text += "</table>";
                document.getElementById("tbody").innerHTML = text;
            }
        };
        xhr.send(jsonPayload);
    } catch (err) {
        console.log(err.message);
    }
}


function editRow(i) {
    document.getElementById("edit_button" + i).style.display = "none";
    document.getElementById("save_button" + i).style.display = "inline-block";

    const firstNameCell = document.getElementById("first_Name" + i);
    const lastNameCell = document.getElementById("last_Name" + i);
    const emailCell = document.getElementById("email" + i);
    const phoneCell = document.getElementById("phone" + i);

    const firstName = firstNameCell.innerText;
    const lastName = lastNameCell.innerText;
    const email = emailCell.innerText;
    const phone = phoneCell.innerText;

    firstNameCell.innerHTML = `<input type='text' id='namef_text${i}' value='${firstName}'>`;
    lastNameCell.innerHTML = `<input type='text' id='namel_text${i}' value='${lastName}'>`;
    emailCell.innerHTML = `<input type='text' id='email_text${i}' value='${email}'>`;
    phoneCell.innerHTML = `<input type='text' id='phone_text${i}' value='${phone}'>`;
}


function saveRow(i) {
    const firstName = document.getElementById("namef_text" + i).value;
    const lastName = document.getElementById("namel_text" + i).value;
    const email = document.getElementById("email_text" + i).value;
    const phone = document.getElementById("phone_text" + i).value;
    const id = ids[i];

    document.getElementById("first_Name" + i).innerHTML = firstName;
    document.getElementById("last_Name" + i).innerHTML = lastName;
    document.getElementById("email" + i).innerHTML = email;
    document.getElementById("phone" + i).innerHTML = phone;

    document.getElementById("edit_button" + i).style.display = "inline-block";
    document.getElementById("save_button" + i).style.display = "none";

    const tmp = {
        id: id,
        newFirstName: firstName,
        newLastName: lastName,
        emailAddress: email,
        phoneNumber: phone
    };

    const jsonPayload = JSON.stringify(tmp);
    const url = urlBase + '/UpdateContact.' + extension;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    try {
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log("Contact updated");
                loadContacts();
            }
        };
        xhr.send(jsonPayload);
    } catch (err) {
        console.log(err.message);
    }
}


function deleteRow(i) {
    const firstName = document.getElementById("first_Name" + i).innerText;
    const lastName = document.getElementById("last_Name" + i).innerText;
    const id = ids[i];

    if (!confirm("Delete contact: " + firstName + " " + lastName + "?")) return;

    const tmp = {
        ID: id,
        UserID: userId
    };

    const jsonPayload = JSON.stringify(tmp);
    const url = urlBase + '/DeleteContact.' + extension;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    try {
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log("Contact deleted");
                loadContacts();
            }
        };
        xhr.send(jsonPayload);
    } catch (err) {
        console.log(err.message);
    }
}

function getUserInfo() {
    const tmp = {
        userId: userId
    };

    const jsonPayload = JSON.stringify(tmp);
    const url = urlBase + '/FindUser.' + extension;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    try {
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                const jsonObject = JSON.parse(xhr.responseText);
                // Example use: display user info in input fields
                document.getElementById("profileFirstName").value = jsonObject.firstName;
                document.getElementById("profileLastName").value = jsonObject.lastName;
                document.getElementById("profileLogin").value = jsonObject.login;
            }
        };
        xhr.send(jsonPayload);
    } catch (err) {
        console.log(err.message);
    }
}

function updateUser() {
    const newFirstName = document.getElementById("profileFirstName").value;
    const newLastName = document.getElementById("profileLastName").value;
    const newLogin = document.getElementById("profileLogin").value;
    const newPassword = document.getElementById("profilePassword").value;

    const hash = md5(newPassword);

    const tmp = {
        userId: userId,
        newFirstName: newFirstName,
        newLastName: newLastName,
        newLogin: newLogin,
        newPassword: hash
    };

    const jsonPayload = JSON.stringify(tmp);
    const url = urlBase + '/UpdateUser.' + extension;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    try {
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log("User updated successfully.");
            }
        };
        xhr.send(jsonPayload);
    } catch (err) {
        console.log(err.message);
    }
}


function deleteUser() {
    if (!confirm("Are you sure you want to delete your account? This cannot be undone.")) return;

    const tmp = {
        userId: userId
    };

    const jsonPayload = JSON.stringify(tmp);
    const url = urlBase + '/DeleteUser.' + extension;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    try {
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log("User deleted");
                doLogout(); // optional: clear session and redirect
            }
        };
        xhr.send(jsonPayload);
    } catch (err) {
        console.log(err.message);
    }
}