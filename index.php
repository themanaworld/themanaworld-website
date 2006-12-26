<?php
  include('includes/common.php');
  placeHeader("News");

?>
<!--
<div style="background-color: rgb(255,220,220); border: 1px solid rgb(240,150,150); padding: 5px; margin: 5px; -moz-border-radius: 10px;">
<b>Notice:</b> We changed our update host to http://updates.themanaworld.org. Please update the value of <i>updatehost</i> in your config.xml. This is the default with the 0.0.22 release.
</div>
-->
<?php

  $number = 6;
  include('cutenews/show_news.php');

  placeFooter();
?>
