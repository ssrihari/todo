<?php
# Logging in with Google accounts requires setting special identity, so this example shows how to do it.
session_start();

require 'openid.php';
require 'user.php';

try {
    # Change 'localhost' to your domain name.
    $openid = new LightOpenID(getenv('HOST_URI'));
    if(!$openid->mode) {
        if(isset($_GET['login'])) {
            $openid->identity = 'https://www.google.com/accounts/o8/id';
            $openid->required = array('namePerson', 'contact/email');
            // $openid->returnUrl = 'http://localhost/todo';
            header('Location: ' . $openid->authUrl());
        }
    } elseif($openid->mode == 'cancel') {
        echo 'User has canceled authentication!';
    } else {
        if ($openid->validate()) {
            $attrs = $openid->getAttributes();
            $user = getUser($openid->identity);
            if(!$user) {
                createUser('{$openid->identity}', $attrs['contact/email']);
                $user = getUser('{$openid->identity}');
                if(!$user) die("Could not create user");
            }
            $email_ar = explode("@", $attrs['contact/email']);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nick']= '$email_ar[0]';
            $host_uri = getenv('HOST_URI');
            header("Location:  http://$host_uri");
        }
        else {
            echo "Could not log in :( ";
        }
    }
} catch(ErrorException $e) {
    echo $e->getMessage();
}
?>
