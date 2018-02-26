<?php
$_GET['title'] = "Post - ".truncateString($data['Post']['title'], 100, false);
$_GET['description'] = truncateString($data['Post']['description'], 264, false);
//print_r($data);
?>

<ul id="comments">
    <li>
        <p></p>
        <p></p>
        <p></p>
    </li>
</ul>

<form>
    <input type="text" id="comment" requried autofocus/>
    <button type="button" onclick="Comment()">Comment</button>
    <div class="hidden" id="SignIn">
        <input type="text" id="uname" requried autofocus/>
        <input type="password" id="password" requried/>
        <button type="button" onclick="Login('custom')">Sign In</button>
        <button type="button" onclick="Login('facebook')">Facebook Login</button>
        <button type="button" onclick="Login('google')">Google Login</button>
    </div>
</form>