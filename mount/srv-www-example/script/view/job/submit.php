<?php
$title = 'Example Job Submit Result';
include_once(SCRIPT_DIR.'/view/common/authorized_header.php');
?>
  <main class="bg-white black">
    <div class="margin-t0-rauto-b0-lauto width-max">
      <div id="job_submit_result" class="loading">Loading&thinsp;<span>.</span>&thinsp;<span>.</span>&thinsp;<span>.</span></div>
    </div>
  </main>
<?php include_once(SCRIPT_DIR.'/view/common/footer.php'); ?>
</body>
<script>
const jobSubmitResult = document.getElementById('job_submit_result');

(function() {
  let httpRequest = new XMLHttpRequest();
  makeRequest('<?php echo AJAX_URL["QSUB"]; ?>', '<?php echo $input_file_name; ?>');

  function makeRequest(url, inputFileName) {
    httpRequest = new XMLHttpRequest();
    if (!httpRequest) {
      jobSubmitResult.innerHTML = `<p class="margin-t0-r0-b0-l0 pink">Could not generate XMLHTTP instance.</p>`;
      return false;
    }
    httpRequest.onreadystatechange = displayJobSubmitResult;
    httpRequest.open('POST', url);
    httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    httpRequest.send('input_file_name=' + encodeURIComponent(inputFileName));
  }

  function displayJobSubmitResult() {
    try {
      if (httpRequest.readyState === XMLHttpRequest.DONE) {
        if (httpRequest.status === 200) {
          const response = JSON.parse(httpRequest.responseText);
          const job_id = response.job_id;
          const jobSubmitResultHtml = `
      <p class="margin-t0-r0-b1rem-l0">Your job ${job_id} has been submitted.</p>
      <p class="margin-t0-r0-b1rem-l0">You can check the result on the following page after running.</p>
      <a href="<?php echo URL['JOB_LIST']; ?>" class="padding-t0_2rem-r0_8rem-b0_2rem-l0_8rem border-radius-5px text-decoration-none bg-green white font-size-0_8rem">List&nbsp;Job</a>
`;
          jobSubmitResult.innerHTML = jobSubmitResultHtml;
        } else if (httpRequest.status === 558 || httpRequest.status === 561 || httpRequest.status === 562 || httpRequest.status === 571) {
          jobSubmitResult.innerHTML = `
      <div class="margin-t0-r0-b1rem-l0"><a href="<?php echo URL['JOB_HOME']; ?>" class="padding-t0_2rem-r0_8rem-b0_2rem-l0_8rem border-radius-5px text-decoration-none bg-green white font-size-0_8rem">Back&nbsp;to&nbsp;Run&nbspJob</a></div>
      <p class="margin-t0-r0-b0-l0 pink">${httpRequest.statusText}.</p>
`;
        } else {
          jobSubmitResult.innerHTML = `<p class="margin-t0-r0-b0-l0 pink">There was a problem with the request.</p>`;
        }
      }
    } catch (e) {
alert(e);
      jobSubmitResult.innerHTML = `<p class="margin-t0-r0-b0-l0 pink">Catch an error: ${e.description}</p>`;
    }
  }
})();
</script>
</html>
