<?php
  include('includes/common.php');
  placeHeader("News");

?>

<div style="background-color: rgb(255,220,220); border: 1px solid rgb(240,150,150); padding: 5px; margin: 5px; -moz-border-radius: 10px;">
<b>Notice:</b> As of 17th of January, the server moved from <i>animesites.de</i> to <i>server.themanaworld.org</i>. We thank Ultramichy for hosting us for so long at no cost. The server is now hosted on <a href="http://platinum.linux.pl">platinum.linux.pl</a>, a server which supports nonprofit initiatives.
</div>

<?php

  $number = 6;
  include('cutenews/show_news.php');

  placeFooter();
?>
