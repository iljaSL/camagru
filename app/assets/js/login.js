
const btnLogin = document.getElementById('button_login');
const resetBtn = document.getElementById('reset_pswd');
const navbar = document.getElementById('navbar');
const token = document.getElementById('token').value;

btnLogin.addEventListener("click", function() {
    const inputUsername = document.getElementById('input_username');
    const inputPassword = document.getElementById('input_password');
    const action = 'action=login&username='+inputUsername.value+'&password='+inputPassword.value+'&token='+token;
    const ajx = new XMLHttpRequest();
    ajx.onreadystatechange = function () {
        if (ajx.readyState == 4 && ajx.status == 200) {
            window.location = 'index.php';
        }
        if (ajx.readyState == 4 && ajx.status == 400) {
            createNotificationWrapper(ajx.responseText, 'text-danger');
        }
        if (ajx.readyState == 4 && ajx.status == 401) {
            createNotificationWrapper(ajx.responseText, 'text-dark');
        }
    };
    ajx.open("POST", "./app/controllers/UsersController.php", true);
    ajx.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajx.send(action);
});

resetBtn.addEventListener("click", function() {
    let email = window.prompt("Please enter your email if you want to reset your password");

    if (email) {
        const regex = /\S+@\S+\.\S+/ ;
        if (regex.test(String(email).toLowerCase())) {
            const action = 'action=reset_password_email&email='+email+'&token='+token;

            const ajx = new XMLHttpRequest();
            ajx.onreadystatechange = function () {
                if (ajx.readyState == 4 && ajx.status == 200) {
                    document.getElementById("message").innerHTML = ajx.responseText;
                }
                if (ajx.readyState == 4 && ajx.status == 400) {
                    createNotificationWrapper(ajx.responseText, 'text-danger');
                }
                if (ajx.readyState == 4 && ajx.status == 401) {
                    createNotificationWrapper(ajx.responseText, 'text-dark');
                }
            };
            ajx.open("POST", "./app/controllers/UsersController.php", true);
            ajx.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajx.send(action);
            window.alert("If the address " + email + " is related to a Camagru account, an email has been sent to reset your password");
        } else {
            window.alert("Please enter a proper email if you want to reset your password");
        }
    }
});

function createNotificationWrapper(responseText, type) {
    notificationWrapper = document.createElement('div');
    notificationWrapper.setAttribute('id', 'notification_wrapper');
    notificationWrapper.setAttribute('style', 'position:fixed;top:20px;width:100%;z-index:100;visibility:visible;animation:cssAnimation 0s 3s forwards;');
    notificationWrapper.innerHTML = '<div class="notification '+type+'"><div class="container"><p>'+responseText+'</p></div></div>';
    navbar.after(notificationWrapper);
}