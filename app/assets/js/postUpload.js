const token = document.getElementById('token').value;

// Handling the stickers

const stickers = document.getElementById('sticker_container');
const overlay = document.getElementById('overlay');
const btnSnap = document.getElementById('button_snap');

stickers.addEventListener("click", function() {
    if (event.target.tagName == 'IMG') {
        if (overlay.firstChild) {
            overlay.removeChild(overlay.firstChild);
        }
        let selected_sticker = document.createElement('img');
        selected_sticker.setAttribute('src', event.target.src);
        selected_sticker_src = event.target.src;
        selected_sticker.setAttribute('id', 'selected_sticker');
        selected_sticker.className = 'dragme';
        overlay.append(selected_sticker);
        btnSnap.className = 'btn btn-primary';
    }
});

function startDrag(e) {
    if (!e) {
        var e = window.event;
    }

    var targ = e.target ? e.target : e.srcElement;

    if (targ.className != 'dragme') {return};
    offsetX = e.clientX;
    offsetY = e.clientY;
    if(!targ.style.left) { targ.style.left='0px'};
    if (!targ.style.top) { targ.style.top='0px'};
    coordX = parseInt(targ.style.left);
    coordY = parseInt(targ.style.top);
    drag = true;
    document.onmousemove=dragDiv;
}

function dragDiv(e) {
    if (event.target.tagName != 'IMG')
    return;
    if (!drag) {return};
    if (!e) {var e = window.event};
    var targ=e.target?e.target:e.srcElement;
    targ.style.left=coordX+e.clientX-offsetX+'px';
    targ.style.top=coordY+e.clientY-offsetY+'px';
    return false;
}

function stopDrag() {
    drag=false;
}

window.onload = function() {
    document.onmousedown = startDrag;
    document.onmouseup = stopDrag;
}

// Fetch the upload together

const img = document.getElementById('img');
const filename = document.getElementById('filename');
const btnUpload = document.getElementById('button_upload');
img.onchange = function(){
    if(img.files.length > 0)
    {
        filename.innerHTML = img.files[0].name;
        btnUpload.className = 'btn btn-primary';
    }
};

// Take the picture

btnSnap.addEventListener("click", function() {

    const uploadedImg = document.getElementById('uploaded_img');
    const imgWidth = uploadedImg.clientWidth;
    const imgHeight = uploadedImg.clientHeight;
    const img_src = uploadedImg.src;

    if (typeof selected_sticker !== 'undefined') {
        const placement_x = parseInt(selected_sticker.style.left.length != 0 ? selected_sticker.style.left : 0, 10)-20;
        const placement_y = parseInt(selected_sticker.style.top.length != 0 ? selected_sticker.style.top : 0, 10)-30;
        const action = "action=upload_img_montage&placement_x="+placement_x+"&placement_y="+placement_y+"&img_src="+img_src+"&sticker_src="+selected_sticker_src+'&img_width='+imgWidth+'&img_height='+imgHeight+'&token='+token;
        const ajx = new XMLHttpRequest();
        ajx.onreadystatechange = function () {
            if (ajx.readyState == 4 && ajx.status == 200) {
                console.log(ajx.responseText);
                getThumbnails();
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
});

// Creating the thumbnails

function getThumbnails () {
    const action = 'action=get_thumbnails'+'&token='+token;
    const ajx = new XMLHttpRequest();
    ajx.onreadystatechange = function () {
        if (ajx.readyState == 4 && ajx.status == 200) {
            let json = JSON.parse(ajx.responseText);
            addLastThumbnail(json['photo_name'], json['id_post']);
        }
        if (ajx.readyState == 4 && ajx.status == 401) {
            createNotificationWrapper(ajx.responseText, 'text-dark');
        }
    }
    ajx.open('POST', './app/controllers/PostsController.php', true);
    ajx.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajx.send(action);
}

function addLastThumbnail (thumbName, idPost) {
    if (document.querySelectorAll('.thumbnail').length >= 6) {
        const thumbnailsContainer = document.getElementById('thumbnails_container');
        thumbnailsContainer.lastElementChild.remove();
    }
    let newDiv = document.createElement('div');
    newDiv.className = 'thumbnail_container';
    newDiv.setAttribute('div_post', idPost);

    let thumbNOverlay = `  <img class="thumbnail" src="./app/assets/images/post_img/`+thumbName+`" alt="">
    <div class="thumbnail_overlay">
    <a id_post="`+idPost+`" class="delete"></a>
    </div>`;

    newDiv.innerHTML = thumbNOverlay;

    const lastPostsTitle = document.getElementById('last_posts_title');
    lastPostsTitle.after(newDiv);
}


// Notification

function createNotificationWrapper(responseText, type) {
    notificationWrapper = document.createElement('div');
    notificationWrapper.setAttribute('id', 'notification_wrapper');
    notificationWrapper.setAttribute('style', 'position:fixed;top:20px;width:100%;z-index:100;visibility:visible;animation:cssAnimation 0s 3s forwards;');
    notificationWrapper.innerHTML = '<div class="notification '+type+'"><div class="container"><p>'+responseText+'</p></div></div>';
    navbar.after(notificationWrapper);
}


// Delete the picture

document.addEventListener('click', function (event) {
	if (event.target.matches('.delete')) {
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
