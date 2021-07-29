<?php
require_once('Pager.php');
$params = [ 'itemData' => $job_list, 'perPage' => 20, 'delta' => 5, 'mode' => 'Jumping', 'clearIfVoid' => true,
            'path' => URL['JOB_HOME'], 'fileName' => 'list', 'fixFileName' => false, 'append' => true, 'urlVar' => 'page_id', 
            'linkClass' => 'text-decoration-none blue', 'curPageLinkClassName' => 'text-decoration-none black',
            'prevImg' => '&lt;&lt;', 'nextImg' => '&gt;&gt;',
            'firstPagePre' => '', 'firstPagePost' => '', 'lastPagePre' => '', 'lastPagePost' => '',
            'spacesBeforeSeparator' => 1, 'spacesAfterSeparator' => 1 ];
$pager = & Pager::factory($params);
$item_num = $pager->numItems();
$page_data = $pager->getPageData();
$page_links = $pager->getLinks();
$title = 'Example Job List';
include_once(SCRIPT_DIR.'/view/common/authorized_header.php');
?>
  <main class="bg-white black">
    <div class="margin-t0-rauto-b0-lauto width-max">
      <table class="margin-t0-rauto-b0-lauto black-gray">
        <tr>
          <th colspan="6" style="padding: 0 0 0.2rem 0; background-color: var(--white);">
            <div class="element-hcenter-vcenter float-right border-radius-5px bg-green cursor-pointer" style="width: 32px; height: 32px;">
              <div class="reload-label white" onclick="location.reload();"></div>
            </div>
          </th>
        </tr>
        <tr class="content-center"><th>No</th><th>Accept&nbsp;Time</th><th>Job&nbsp;ID</th><th>Finished</th></tr>
<?php
if ($item_num !== 0) {
  foreach ($page_data as $index => $job) {
    $content_no = $index + 1;
    $content_accept_time = $job->accept_time;
    $content_job_id = $job->job_id;
    $content_finished = null;
    if ($job->finished) {
      $content_job_id = '<a href="'.URL['JOB_RESULT'].'?job_id='.$job->job_id.'" class="text-decoration-none blue">'.$job->job_id.'</a>';
      $content_finished = '&#10003;';
    }
    echo <<<EOT
        <tr class="content-center">
          <td>{$content_no}</td>
          <td>{$content_accept_time}</td>
          <td>{$content_job_id}</td>
          <td>{$content_finished}</td>
        </tr>
EOT;
    echo PHP_EOL;
  }
  echo <<<EOT
        <tr><th colspan="4" class="content-center" style="padding: 0; background-color: var(--white);">{$page_links['all']}</th></tr>
EOT;
  echo PHP_EOL;
} else {
  echo <<<EOT
        <tr><td colspan="4" class="content-center">No data</td></tr>
EOT;
  echo PHP_EOL;
}
?>
      </table>
    </div>
  </main>
<?php include_once(SCRIPT_DIR.'/view/common/footer.php'); ?>
</body>
</html>
