<?php
include('includes/common.php');
if (preg_match('/'.$_SERVER['SERVER_NAME'].'/',$_SERVER['HTTP_REFERER']) && preg_match('/registration.php/', $_SERVER['HTTP_REFERER'])) {
    placeHeader("Account Created");
    echo "<p>Your account was scheduled for creation! In a few minutes you should receive an email with verification of your new account.</p>
<p><em>If the account doesn't work after five minutes, please ask for help on the <a href='https://forums.themanaworld.org/viewforum.php?f=3'>Forums</a> or <a href='https://webchat.freenode.net/?channels=#themanaworld'>Support (IRC)</a>.</em></p>";
    placeFooter();
} else {
    header("Location: /registration.php");
}
?>
