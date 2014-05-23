<?php 
// Email Template Header
// Image Mapped for Html Email
// Base/Default Links
function getEmailTemplate($emailText) {
    $boundary = uniqid('np');
    $headers .= "MIME-Version: 1.0\r\nContent-type: multipart/alternative;boundary=".$boundary."\r\n";
    $data = "This is a MIME encoded message."; 
 
    $data .= "\r\n\r\n--" . $boundary . "\r\n";
    $data .= "Content-type: text/plain;charset=utf-8\r\n\r\n";
    $data .= "###############################################################\n";
    $data .= "# ___________.__                _____                         #\n";
    $data .= "# \__    ___/|  |__   ____     /     \  ____    ____  ____    #\n";
    $data .= "#   |    |   |  |  \_/ __ \   /  \ /  \ \__ \  / __ \ \__ \   #\n";
    $data .= "#   |    |   |   Y  \  ___/  /    Y    \/ __ \|  | \ \/ __ \_ #\n";
    $data .= "#   |____|   |___|__/\____>  \____|____(______/__|  \(______/ #\n";
    $data .= "#             __      __            .__       .___            #\n";
    $data .= "#            /  \    /  \___________|  |    __| _/            #\n";
    $data .= "#            \   \/\/   /  _ \_  __ \  |   / __ |             #\n";
    $data .= "#             \        (  <_> )  | \/  |__/ /_/ |             #\n";
    $data .= "#              \__/\  / \____/|__|  |____/\____ |             #\n";
    $data .= "#                   \/                         \/             #\n";
    $data .= "#                                                             #\n";
    $data .= "###############################################################\n";
    $data .= $emailText;

    $data .= "\r\n\r\n--" . $boundary . "\r\n";
    $data .= "Content-type: text/html;charset=utf-8\r\n\r\n";

    $data .= '<html><body>
<img id="Image-Maps-Com-image-maps-2014-05-22-121954" src="https://www.themanaworld.org/images/TMW-email-temaplate-mailer.png" border="0" width="640" height="272" orgWidth="640" orgHeight="272" usemap="#image-maps-2014-05-22-121954" alt="" />
<map name="image-maps-2014-05-22-121954" id="ImageMapsCom-image-maps-2014-05-22-121954">
<area  alt="Home Page" title="The Mana World" href="https://www.themanaworld.org/" shape="rect" coords="0,0,197,201" style="outline:none;" target="_self"     />
<area  alt="Home Page" title="The Mana World" href="https://www.themanaworld.org/" shape="rect" coords="197,0,465,168" style="outline:none;" target="_self"     />
<area  alt="Official Client Mana Plus" title="Official Client Mana Plus" href="http://manaplus.org" shape="rect" coords="465,0,640,168" style="outline:none;" target="_self"     />
<area  alt="The Mana World News" title="The Mana World News" href="https://www.themanaworld.org/index.php" shape="rect" coords="196,168,275,201" style="outline:none;" target="_self"     />
<area  alt="The Mana World Wiki" title="The Mana World Wiki" href="https://wiki.themanaworld.org/index.php/Main_Page" shape="rect" coords="274,168,375,201" style="outline:none;" target="_self"     />
<area  alt="The Mana World Forums" title="The Mana World Forums" href="https://forums.themanaworld.org/" shape="rect" coords="372,168,490,201" style="outline:none;" target="_self"     />
<area  alt="The Mana World Support (IRC)" title="The Mana World Support (IRC)" href="https://webchat.freenode.net/?channels=#themanaworld" shape="rect" coords="488,168,640,201" style="outline:none;" target="_self"     />
<area  alt="About The Mana World" title="About The Mana World" href="https://www.themanaworld.org/about.php" shape="rect" coords="0,201,84,272" style="outline:none;" target="_self"     />
<area  alt="Creative Commons" title="Creative Commons" href="http://creativecommons.org/" shape="rect" coords="84,201,121,272" style="outline:none;" target="_self"     />
<area  alt="GNU General Public License" title="GNU General Public License " href="http://www.gnu.org/copyleft/gpl.html" shape="rect" coords="121,201,155,272" style="outline:none;" target="_self"     />
<area  alt="Open Source Initiative" title="Open Source Initiative" href="http://opensource.org/" shape="rect" coords="155,202,189,272" style="outline:none;" target="_self"     />
<area  alt="The Mana World on Facebook" title="The Mana World on Facebook" href="https://www.facebook.com/themanaworld" shape="rect" coords="191,204,308,272" style="outline:none;" target="_self"     />
<area  alt="The Mana World on G+" title="The Mana World on G+" href="https://plus.google.com/+TheManaWorldOrg" shape="rect" coords="310,204,344,272" style="outline:none;" target="_self"     />
<area  alt="The Mana World on Youtube" title="The Mana World on Youtube" href="https://www.youtube.com/user/TheManaWorld" shape="rect" coords="345,203,379,271" style="outline:none;" target="_self"     />
<area  alt="Official Client Mana Plus" title="Official Client Mana Plus" href="http://manaplus.org" shape="rect" coords="382,203,501,272" style="outline:none;" target="_self"     />
<area  alt="Mac OS Downloads" title="Mac OS Downloads" href="http://download.evolonline.org/manaplus/macosx/" shape="rect" coords="502,203,537,272" style="outline:none;" target="_self"     />
<area  alt="Windows Installer" title="Windows Installer" href="http://manaplus.org/windowsinstaller" shape="rect" coords="537.8000001907349,202.8000030517578,572.8000001907349,271.8000030517578" style="outline:none;" target="_self"     />
<area  alt="Linux Versions" title="Linux Versions" href="http://manaplus.org/" shape="rect" coords="575,203,640,272" style="outline:none;" target="_self"     />
<area shape="rect" coords="638,270,640,272" alt="Image Map" style="outline:none;" title="Image Map" href="http://www.image-maps.com/index.php?aff=mapped_users_0" />
</map>
'.nl2br($emailText).'
<div style="font-size:10px;">
   &copy; 2004-2014 The Mana World
</div>
</body>
</html>';
    $data .= "\r\n\r\n--" . $boundary . "--";
    return array($headers,$data);
}
?>
