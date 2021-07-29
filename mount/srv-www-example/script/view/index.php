<?php
$title = 'Example Top';
include_once(SCRIPT_DIR.'/view/common/authorized_header.php');
?>
  <main class="bg-white black">
    <div class="margin-t0-rauto-b0-lauto width-max">
      <p class="margin-t0-r0-b1rem-l0 padding-t0-r0-b0-l0">This website indicates an example of the simple PNG image uploader.</p>
      <ul class="margin-t0-r0-b0-l0 padding-t0-r0-b0-l0 display-in-a-row list-style-type-none">
        <li><a class="padding-t0_2rem-r0_8rem-b0_2rem-l0_8rem border-radius-5px text-decoration-none bg-green white font-size-0_8rem" href="<?php echo URL['JOB_HOME']; ?>">Run&nbsp;Job</a></li>
        <li><a class="margin-t0-r0-b0-l1rem padding-t0_2rem-r0_8rem-b0_2rem-l0_8rem border-radius-5px text-decoration-none bg-green white font-size-0_8rem" href="<?php echo URL['JOB_LIST']; ?>">List&nbsp;Job</a></li>
      </ul>
    </div>
  </main>
<?php include_once(SCRIPT_DIR.'/view/common/footer.php'); ?>
</body>
</html>
