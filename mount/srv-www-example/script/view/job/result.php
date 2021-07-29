<?php
$title = 'Example Job Result';
include_once(SCRIPT_DIR.'/view/common/authorized_header.php');
?>
  <main class="bg-white black">
    <div class="margin-t0-rauto-b0-lauto width-max">
      <ul class="margin-t0-r0-b1rem-l0 padding-t0-r0-b0-l0 display-in-a-row list-style-type-none">
        <li><a class="padding-t0_2rem-r0_8rem-b0_2rem-l0_8rem border-radius-5px text-decoration-none bg-green white font-size-0_8rem" href="<?php echo URL['JOB_LIST']; ?>">List&nbsp;Job</a></li>
      </ul>
      <h2 class="margin-t0-r0-b0_5rem-l0 font-size-1rem">Thumbnail <span class="font-size-0_8rem">(Job ID: <?php echo $job_id; ?>)</span></h2>
<?php
if ($thumbnail_available) {
  $thumbnail = (is_null(CONTEXT_PREFIX)) ? '/thumbnails/'.$input_file_name : CONTEXT_PREFIX.'/thumbnails/'.$input_file_name;
  echo <<<EOT
      <img class="margin-t0-r0-b0_5rem-l0" src="{$thumbnail}" alt="Thumbnail of the uploaded file">
EOT;
} else {
  echo <<<EOT
      <p class="pink font-size-0_8rem">No thumbnail available.</p>
EOT;
}
?>
      <h2 class="margin-t0-r0-b0_5rem-l0 font-size-1rem">Meta Information <span class="font-size-0_8rem">(Job ID: <?php echo $job_id; ?>)</span></h2>
<?php
if (! $json = file_get_contents(JOB_RESULT_DIR.'/'.$job_id.'.json')) {
  echo <<<EOT
      <p class="pink font-size-0_8rem">Cannot get meta information.</p>
EOT;
}
$meta_info = json_decode($json, true)[0];
if (is_null($meta_info)) {
  echo <<<EOT
      <p class="pink font-size-0_8rem">Cannot decode json data.</p>
EOT;
} else {
  $tags = ['ExifToolVersion', 'FileSize', 'FileType', 'FileTypeExtension',
           'MIMEType', 'ImageWidth', 'ImageHeight', 'BitDepth',
           'ColorType', 'Compression', 'Filter', 'Interlace',
	   'ProfileName', 'ExifByteOrder', 'UserComment', 'ExifImageWidth',
           'ExifImageHeight', 'XMPToolkit', 'ImageSize', 'Megapixels'];
  echo <<<EOT
      <table class="margin-t0-r0-b0-l0 black-gray">
EOT;
  foreach ($tags as $tag) {
    if (isset($meta_info[$tag])) {
  echo <<<EOT
        <tr><th>$tag</th><td>{$meta_info[$tag]}</td></tr>
EOT;
    } else {
  echo <<<EOT
        <tr><th>$tag</th><td>none</td></tr>
EOT;
    }
  }
  echo <<<EOT
      </table>
EOT;
}
?>
    </div>
  </main>
<?php include_once(SCRIPT_DIR.'/view/common/footer.php'); ?>
</body>
</html>
