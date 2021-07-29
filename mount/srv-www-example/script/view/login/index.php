<?php
$title = 'Example Login';
include_once(SCRIPT_DIR.'/view/common/unauthorized_header.php');
?>
  <main class="bg-white black">
    <div class="margin-t0-rauto-b0-lauto width-max element-hcenter-vcenter height-100pct">
      <form method="post" action="<?php echo ACTION_URL['LOGIN_VERIFY']; ?>">
        <table class="vanilla margin-t0-rauto-b0_5rem-lauto">
          <tr>
            <th class="padding-t0-r0-b0_2rem-l0 content-left"><label for="user_name" class="margin-t0-r0_5rem-b0-l0">User&nbsp;name</label></th>
            <td class="padding-t0-r0-b0_2rem-l0"><input type="text" id="user_name" name="user_name" class="border-1px-black-solid bg-white filter-brightness1_5"></td>
          </tr>
          <tr>
            <th class="padding-t0-r0-b0_2rem-l0 content-left"><label for="password" class="margin-t0-r0_5rem-b0-l0">Password</label></th>
            <td class="padding-t0-r0-b0_2rem-l0"><input type="password" id="password" name="password" class="border-1px-black-solid bg-white filter-brightness1_5"></td>
          </tr>
        </table>
        <input type="submit" value="Login" class="padding-t0_2rem-r0_8rem-b0_2rem-l0_8rem border-radius-5px border-0 bg-blue white cursor-pointer" />
<?php
if (isset($_GET['login_failed'])) {
  if (intval($_GET['login_failed']) === 1) {
    echo <<<EOT
        <div class="display-inline margin-t0-r0-b0-l0_5rem font-size-0_8rem pink">Login&nbsp;failed</div>
EOT;
    echo PHP_EOL;
  }
}
if (isset($_GET['session_timeout'])) {
  if (intval($_GET['session_timeout']) === 1) {
    echo <<<EOT
        <div class="display-inline margin-t0-r0-b0-l0_5rem font-size-0_8rem pink">Session&nbsp;timed&nbsp;out</div>
EOT;
    echo PHP_EOL;
  }
}
?>
      </form>
    </div>
  </main>
<?php include_once(SCRIPT_DIR.'/view/common/footer.php'); ?>
</body>
</html>
