
const btnCreateAccount = document.getElementById('button_create_account');
const inputUsername = document.getElementById('input_username');
const inputEmail = document.getElementById('input_email');
const inputPassword = document.getElementById('input_password');
const navbar = document.getElementById('navbar');
const token = document.getElementById('token').value;

btnCreateAccount.addEventListener("click", function() {
    document.querySelectorAll('.help').forEach(function(a){
        a.remove()
    })

    const action = 'action=signup&username='+inputUsername.value+'&email='+inputEmail.value+'&password='+inputPassword.value+'&token='+token;
    const ajx = new XMLHttpRequest();
    ajx.onreadystatechange = function () {
        if (ajx.readyState == 4 && ajx.status == 200) {
            window.location = 'index.php?p=login';
        }
        if (ajx.readyState == 4 && ajx.status == 400) {
            let json = JSON.parse(ajx.responseText);
            if (json.username_format) {
                inputUsername.className = "input text-danger";
                let helper = document.createElement('p');
                helper.className = "help text-danger";
                helper.innerText = json.username_format;
                inputUsername.after(helper);
            } else {
                inputUsername.className = "input text-success";
            }
            if (json.email_invalid) {
                inputEmail.className = "input text-danger";
                let helper = document.createElement('p');
                helper.className = "help text-danger";
                helper.innerText = json.email_invalid;
                inputEmail.after(helper);
            } else {
                inputEmail.className = "input text-success";
            }
            if (json.password_format) {
                inputPassword.className = "input text-danger";
                let helper = document.createElement('p');
                helper.className = "help text-danger";
                helper.innerText = json.password_format;
                inputPassword.after(helper);
            } else {
                inputPassword.className = "input text-success";
            }
            if (json.username_or_email_exist) {
                inputUsername.className = "input text-danger";
                inputEmail.className = "input text-danger";
                createNotificationWrapper(json.username_or_email_exist, 'text-danger');
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

function createNotificationWrapper(responseText, type) {
    notificationWrapper = document.createElement('div');
    notificationWrapper.setAttribute('id', 'notification_wrapper');
    notificationWrapper.setAttribute('style', 'position:fixed;top:20px;width:100%;z-index:100;visibility:visible;animation:cssAnimation 0s 3s forwards;');
    notificationWrapper.innerHTML = '<div class="notification '+type+'"><div class="container"><p>'+responseText+'</p></div></div>';
    navbar.after(notificationWrapper);
}