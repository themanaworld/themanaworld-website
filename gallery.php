<?php
  include('includes/common.php');

  $append = '';
  if (isset($_GET['dir'])) { $append = $_GET['dir'];  }
  placeHeader('Gallery' . $append);

  include('gallery/galerie.php');

  placeFooter();
?>
