<?php
$title = 'Example Run Job';
include_once(SCRIPT_DIR.'/view/common/authorized_header.php');
?>
  <main class="bg-white black">
    <div class="margin-t0-rauto-b0-lauto width-max">
      <p class="margin-t0-r0-b1rem-l0">You can upload an PNG image and display the meta information.</p>
      <form method="post" enctype="multipart/form-data" action="<?php echo ACTION_URL['JOB_SUBMIT']; ?>">
        <label for="avatar" class="display-block width-7_5rem content-center margin-t0-r0-b1rem-l0 padding-t0_2rem-r0_8rem-b0_2rem-l0_8rem border-radius-5px bg-green white font-size-0_8rem cursor-pointer">Select an image</label>
        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />
        <input type="file" id="avatar" class="display-none opacity-0" name="avatar" accept="image/png" onclick="document.getElementById('avatar').value = ''; return printFileStatus();" onchange="return printFileStatus();">
        <table id="input_file_information" class="display-none black-gray margin-t0-r0-b1rem-l0">
          <tr><th>File&nbsp;Name</th><td id="file_name"></td></tr>
          <tr><th>File&nbsp;Size</th><td id="file_size"></td></tr>
          <tr><th>File&nbsp;Type</th><td id="file_type"></td></tr>
          <tr><th>File&nbsp;Status</th><td id="file_status"></td></tr>
        </table>
        <input type="submit" id="submit_button" class="display-none margin-t0-r0-b0-l0 padding-t0_2rem-r0_8rem-b0_2rem-l0_8rem border-0 border-radius-5px bg-green white font-size-0_8rem cursor-pointer" value="Submit">
      </form>
    </div>
  </main>
<?php include_once(SCRIPT_DIR.'/view/common/footer.php'); ?>
</body>
<script>
  const thousandSeparator = (n) => { return new Intl.NumberFormat('en', {minimumFractionDigits: 1, maximumFractionDigits: 1}).format(n); }
  const maxFileSizeB = <?php echo MAX_FILE_SIZE; ?>;  // Byte
  const maxFileSizeKb = (maxFileSizeB / 1024).toFixed(1);
  const input = document.getElementById('avatar');
  const informationTable = document.getElementById('input_file_information');
  const prevFileName = document.getElementById('file_name');
  const prevFileSize = document.getElementById('file_size');
  const prevFileType = document.getElementById('file_type');
  const prevFileStatus = document.getElementById('file_status');
  const submitButton = document.getElementById('submit_button');

  function printFileStatus() {
    if (input.files.length === 0) {
      prevFileName.textContent = '';
      prevFileSize.textContent = '';
      prevFileType.textContent = '';
      prevFileStatus.textContent = '';
      informationTable.classList.add('display-none');
      submitButton.classList.add("display-none");
    } else if (input.files.length === 1) {
      const file = input.files[0];
      const fileName = file.name;
      const fileSize = file.size;
      const fileSizeKb = fileSize / 1024;
      const fileType = file.type;
      prevFileName.textContent = fileName;
      prevFileSize.textContent = `${thousandSeparator(fileSizeKb)} KB`;
      prevFileType.textContent = fileType;
      if (fileSize === 0) {
        prevFileStatus.innerHTML = `<span class="pink">Empty file</span>`;
        informationTable.classList.remove('display-none');
        submitButton.classList.add("display-none");
        return false;
      } else if (fileSize > maxFileSizeB) {
        prevFileStatus.innerHTML = `<span class="pink">Over ${thousandSeparator(maxFileSizeKb)} KB</span>`;
        informationTable.classList.remove('display-none');
        submitButton.classList.add("display-none");
        return false;
      }
      if (fileType !== 'image/png') {
        prevFileStatus.innerHTML = `<span class="pink">File type is not image/png</span>`;
        informationTable.classList.remove('display-none');
        submitButton.classList.add("display-none");
        return false;
      }
      prevFileStatus.textContent = `Maybe no problem`;
      informationTable.classList.remove('display-none');
      submitButton.classList.remove("display-none");
    }
    return true;
  }
</script>
</html>
