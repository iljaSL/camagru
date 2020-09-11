<strong>This first web project at Hive Helsinki is about creating a small web application allowing to make some basic photo and video editing using your webcam and some predefined images. Any fancy frameworks were forbidden. I used the MVC structure for this project</strong>

 <img src="https://github.com/iljaSL/camagru/blob/master/app/assets/images/site/siteimage.png" width="1000" height="600"/>


<h3>Stack</h3>
<strong>Frontend: HTML, CSS/Bootstrap 4, Javascript</strong><br>
<strong>Backend: PHP, MySQL, Apache</strong>

<h3>How to install</h3>
<ul>
  <li>Dowload (for example from <a href="https://bitnami.com/stack/mamp">bitnami</a>) and install the MAMP stack</li>
  <li>Git clone the repo inside the htdocs folder</li>
  <li>Set up the Database password as "rootpasswd" or change the password inside the config/database.php file </li>
  <li>Start the Apache Server and the Database</li>
  <li>Execute the setup.php file</li>
  <li>Enjoy the site on http://localhost:8080/camagru/index.php</li>
</ul>

<h3>Features</h3>
<h5><strong>Security first!</strong></h5>
<ul>
  <li>Passwords are encrypted in the database.</li>
  <li>Protected agains cross-site scripting</li>
  <li>Protected agains Cross-site request forgery with the help of tokens</li>
  <li>Protected agains SQL injections</li>
  <li>A high complexity password is required from the user</li>
  <li>Registration and the reset of the password needs to be confirmed with a unique link that is sent to the user's email</li>
</ul>
<h5>Site features</h5>
<ul>
  <li>User is able to register, login, view account and set preferences, reset password, change username, change email</li>
  <li>The gallery can be viewed by everybody, the user needs to be logged in, in order to create a post, like a post and leave a comment</li>
  <li>User can take a picture with her/his camera, edit it and upload it in a life view. Otherwise the picture can just be uploaded wiht or without a 'sticker'</li>
  <li>The user can delete her/ his post.</li>
  <li>The user gets notified via email if someone comments on her/his post</li>
  <li>AJAX is used</li>
 <li>Infinity scroll</li>
</ul>
