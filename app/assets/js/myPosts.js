const token = document.getElementById('token').value;

document.addEventListener('click', function (event) {

	if (event.target.matches('.delete_btn')) {
        if (window.confirm('Are you sure you want to delete this picture?')) {
            const idPost = event.target.getAttribute('id_post');
            const action = 'action=delete_post&id_post='+idPost+'&token='+token;
            const ajx = new XMLHttpRequest();
            ajx.onreadystatechange = function () {
                if (ajx.readyState == 4 && ajx.status == 200) {
                    createNotificationWrapper(ajx.responseText, 'text-success');
                    const divToDelete = document.querySelector("[div_post='"+idPost+"']");
                    divToDelete.remove();
                }
                if (ajx.readyState == 4 && ajx.status == 400) {
                    createNotificationWrapper(ajx.responseText, 'text-danger');
                }
                if (ajx.readyState == 4 && ajx.status == 401) {
                    createNotificationWrapper(ajx.responseText, 'text-dark');
                }
            };
            ajx.open("POST", "./app/controllers/PostsController.php", true);
            ajx.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajx.send(action);
        }
	}
}, false);

function createNotificationWrapper(responseText, type) {
    notificationWrapper = document.createElement('div');
    notificationWrapper.setAttribute('id', 'notification_wrapper');
    notificationWrapper.setAttribute('style', 'position:fixed;top:20px;width:100%;z-index:100;visibility:visible;animation:cssAnimation 0s 3s forwards;');
    notificationWrapper.innerHTML = '<div class="notification '+type+'"><div class="container"><p>'+responseText+'</p></div></div>';
    navbar.after(notificationWrapper);
}