const inputNewPswd = document.getElementById('input_new_pswd');
const btnResetPswd = document.getElementById('button_reset_password');

btnResetPswd.addEventListener("click", function() {
    document.querySelectorAll('.help').forEach(function(a){
        a.remove()
    })
    const action = 'action=reset_password&new_pswd='+inputNewPswd.value+'&email='+email+'&hash='+hash;
    const ajx = new XMLHttpRequest();
    ajx.onreadystatechange = function () {
        if (ajx.readyState == 4 && ajx.status == 200) {
            inputNewPswd.className = "input text-success";
            createNotificationWrapper(ajx.responseText, 'text-success');
        }
        if (ajx.readyState == 4 && ajx.status == 400) {
            inputNewPswd.className = "input text-danger";
            let helper = document.createElement('p');
            helper.className = "help text-danger";
            helper.innerText = ajx.responseText;
            inputNewPswd.after(helper);
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
    notificationWrapper.setAttribute('style', 'position:fixed;top:20px;width:100%;z-index:100;visibility:visible;animation:cssAnimation 0s 6s forwards;');
    notificationWrapper.innerHTML = '<div class="notification '+type+'"><div class="container"><p>'+responseText+'</p></div></div>';
    navbar.after(notificationWrapper);
}