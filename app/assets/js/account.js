const btnUsername = document.getElementById('btn_username');
const btnEmail = document.getElementById('btn_email');
const btnPswd = document.getElementById('btn_pswd');
const btnEmailPref = document.getElementById('btn_email_pref');

const inputUsername = document.getElementById('input_username');
const inputEmail = document.getElementById('input_email');
const inputPswd = document.getElementById('input_pswd');
const inputUsernamePassword = document.getElementById('input_username_password');
const inputEmailPassword = document.getElementById('input_email_password');
const inputPswdPassword = document.getElementById('input_pswd_password');
const radioPrefYes = document.getElementById('email_pref_yes');

const displayCurrentUsername = document.getElementById('display_current_username');
const displayCurrentEmail = document.getElementById('display_current_email');

const navbar = document.getElementById('navbar');

const token = document.getElementById('token').value;

btnUsername.addEventListener("click", function() {
    document.querySelectorAll('.help').forEach(function(a){
        a.remove()
    })
    const action = 'action=update_username&newUsername='+inputUsername.value+'&pswd='+inputUsernamePassword.value+'&token='+token;
    const ajx = new XMLHttpRequest();
    ajx.onreadystatechange = function () {
        if (ajx.readyState == 4 && ajx.status == 200) {
            displayCurrentUsername.innerText = inputUsername.value;
            createNotificationWrapper(ajx.responseText, 'text-success');
            inputUsername.className = "input";
            inputUsername.value = "";
            inputUsernamePassword.className = "input";
            inputUsernamePassword.value = "";

        }
         if (ajx.readyState == 4 && ajx.status == 400) {
            let json = JSON.parse(ajx.responseText);
            if (json.username_format) {
                inputUsername.className = "input text-danger";
                let helper = document.createElement('p');
                helper.className = "help text-danger";
                helper.innerText = json.username_format;
                inputUsername.after(helper);
            } else if (json.username_taken) {
                inputUsername.className = "input text-danger";
                let helper = document.createElement('p');
                helper.className = "help text-danger";
                helper.innerText = json.username_taken;
                inputUsername.after(helper);
            } else {
                inputUsername.className = "input text-success";
            }
            if (json.wrong_password) {
                inputUsernamePassword.className = "input text-danger";
                let helper = document.createElement('p');
                helper.className = "help text-danger";
                helper.innerText = json.wrong_password;
                inputUsernamePassword.after(helper);
            } else {
                inputUsernamePassword.className = "input text-success";
            }
         }
         if (ajx.readyState == 4 && ajx.status == 401) {
            createNotificationWrapper(ajx.responseText, 'text-dark');
        }
    };
    ajx.open("POST", "./app/controllers/UsersController.php", true);
    ajx.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajx.send(action);
});

btnEmail.addEventListener("click", function() {
    document.querySelectorAll('.help').forEach(function(a){
        a.remove()
    })
    const action = 'action=update_email&newEmail='+inputEmail.value+'&pswd='+inputEmailPassword.value+'&token='+token;

    const ajx = new XMLHttpRequest();
    ajx.onreadystatechange = function () {
        if (ajx.readyState == 4 && ajx.status == 200) {
            displayCurrentEmail.innerText = inputEmail.value;
            createNotificationWrapper(ajx.responseText, 'text-success');
            inputEmail.className = "input";
            inputEmail.value = "";
            inputEmailPassword.className = "input";
            inputEmailPassword.value = "";
        }
         if (ajx.readyState == 4 && ajx.status == 400) {
            let json = JSON.parse(ajx.responseText);
            if (json.email_format) {
                inputEmail.className = "input text-danger";
                let helper = document.createElement('p');
                helper.className = "help text-danger";
                helper.innerText = json.email_format;
                inputEmail.after(helper);
            } else if (json.email_exist) {
                inputEmail.className = "input text-danger";
                let helper = document.createElement('p');
                helper.className = "help text-danger";
                helper.innerText = json.email_exist;
                inputEmail.after(helper);
            } else {
                inputEmail.className = "input text-success";
            }
            if (json.wrong_password) {
                inputEmailPassword.className = "input text-danger";
                let helper = document.createElement('p');
                helper.className = "help text-danger";
                helper.innerText = json.wrong_password;
                inputEmailPassword.after(helper);
            } else {
                inputEmailPassword.className = "input text-success";
            }
         }
         if (ajx.readyState == 4 && ajx.status == 401) {
            createNotificationWrapper(ajx.responseText, 'text-dark');
        }
    };
    ajx.open("POST", "./app/controllers/UsersController.php", true);
    ajx.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajx.send(action);
});

btnPswd.addEventListener("click", function() {
    document.querySelectorAll('.help').forEach(function(a){
        a.remove()
    })
    const action = 'action=update_password&newPassword='+inputPswd.value+'&currentPswd='+inputPswdPassword.value+'&token='+token;
    const ajx = new XMLHttpRequest();
    ajx.onreadystatechange = function () {
        if (ajx.readyState == 4 && ajx.status == 200) {
            createNotificationWrapper(ajx.responseText, 'text-success');
            inputPswd.className = "input";
            inputPswd.value = "";
            inputPswdPassword.className = "input";
            inputPswdPassword.value = "";

        }
         if (ajx.readyState == 4 && ajx.status == 400) {
            let json = JSON.parse(ajx.responseText);
            if (json.password_format) {
                inputPswd.className = "input text-danger";
                let helper = document.createElement('p');
                helper.className = "help text-danger";
                helper.innerText = json.password_format;
                inputPswd.after(helper);
            } else {
                inputPswd.className = "input text-success";
            }
            if (json.wrong_password) {
                inputPswdPassword.className = "input text-danger";
                let helper = document.createElement('p');
                helper.className = "help text-danger";
                helper.innerText = json.wrong_password;
                inputPswdPassword.after(helper);
            } else {
                inputPswdPassword.className = "input text-success";
            }
         }
         if (ajx.readyState == 4 && ajx.status == 401) {
            createNotificationWrapper(ajx.responseText, 'text-dark');
        }
    };
    ajx.open("POST", "./app/controllers/UsersController.php", true);
    ajx.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajx.send(action);
});

btnEmailPref.addEventListener("click", function() {
    document.querySelectorAll('.help').forEach(function(a){
        a.remove()
    })
    let emailPref = '0';
    if (radioPrefYes.checked) {
        emailPref = '1';
    }
    const action = 'action=update_email_pref&email_pref='+emailPref+'&token='+token;

    const ajx = new XMLHttpRequest();
    ajx.onreadystatechange = function () {
        if (ajx.readyState == 4 && ajx.status == 200) {
            createNotificationWrapper(ajx.responseText, 'text-success');
        }
        if (ajx.readyState == 4 && ajx.status == 401) {
            createNotificationWrapper(ajx.responseText, 'text-dark');
        }
    };
    ajx.open("POST", "./app/controllers/UsersController.php", true);
    ajx.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajx.send(action);
});

function createNotificationWrapper(responseText, type) {
    notificationWrapper = document.createElement('div');
    notificationWrapper.setAttribute('id', 'notification_wrapper');
    notificationWrapper.setAttribute('style', 'position:fixed;top:20px;width:100%;z-index:100;visibility:visible;animation:cssAnimation 0s 3s forwards;');
    notificationWrapper.innerHTML = '<div class="notification '+type+'"><div class="container"><p>'+responseText+'</p></div></div>';
    navbar.after(notificationWrapper);
}