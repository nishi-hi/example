<?php
$title = 'Example Error';
include_once(SCRIPT_DIR.'/view/common/authorized_header.php');
?>
  <main class="bg-white black">
    <div class="margin-t0-rauto-b0-lauto width-max">
      <p class="margin-t0-r0-b0-l0 pink"><?php echo $error->getMessage(); ?></p>
    </div>
  </main>
<?php include_once(SCRIPT_DIR.'/view/common/footer.php'); ?>
</body>
</html>
