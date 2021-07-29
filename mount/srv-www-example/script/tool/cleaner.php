<?php

require dirname(__FILE__, 2).'/include/const.php';

class Cleaner {

  private $lock_dir;
  private $lock_file_name;
  private $lock_file_path;

  public function __construct() {
    $this->lock_dir = dirname(__FILE__).'/lock';
    $this->lock_file_name = '.'.basename(__FILE__).'.lock';
    $this->lock_file_path = $this->lock_dir.'/'.$this->lock_file_name;
  }

  private function check_dir_perm($dir) {
    if (! is_dir($dir)) {
      fwrite(STDERR, "{$dir} doesn't exist".PHP_EOL);
      exit(1);
    }
    if (! (is_readable($dir) && is_writable($dir))) {
      fwrite(STDERR, "Cannot read/write {$dir}".PHP_EOL);
      exit(2);
    }
  }

  private function write_new_file($file_path) {
    if (! file_exists($file_path)) {
      if (! $handle = fopen($file_path, 'w')) {
        fwrite(STDERR, "Cannot open file ({$file_path})".PHP_EOL);
        exit(4);
      }
      if (fwrite($handle, null) === false) {
        fwrite(STDERR, "Cannot write to file ({$filename})".PHP_EOL);
        exit(5);
      }
      fclose($handle);
    } else {
      fwrite(STDERR, "{$file_path} exists".PHP_EOL);
      fwrite(STDERR, 'Check the state of another process, then delete it if there is no problem'.PHP_EOL);
      exit(3);
    }
  }

  public function unlink_file($file_path) {
    if (file_exists($file_path)) {
      if (! unlink($file_path)) {
        fwrite(STDERR, "Cannot delete {$file_path}".PHP_EOL);
        exit(7);
      }
    } else {
      fwrite(STDERR, "{$file_path} doesn't exist".PHP_EOL);
      exit(6);
    }
  }

  private function clean_old_session_data($now) {
    try {
      $maxlifetime_ago = $now->modify('- '.MAXLIFETIME.' sec')->getTimestamp();
      $db = new SQLite3(SESSION_DB_FILE, SQLITE3_OPEN_READWRITE); 
      if (! $db->exec("delete from session where session_updated <= {$maxlifetime_ago}")) throw new Exception('Cannot delete rows of old sessions');
    } catch (Exception $e) {
      fwrite(STDERR, $e->getMessage());
      exit(8);
    } finally {
      $db->close();
    }
  }

  private function clean_old_job_data($now) {
    try {
      $jobs = [];
      $midnight_yesterday = $now->modify('yesterday')->getTimestamp();
      $db = new SQLite3(DB_FILE, SQLITE3_OPEN_READWRITE); 
      $rows = $db->query("select job_id, input_file from job where time < {$midnight_yesterday}");
      while ($row = $rows->fetchArray()) { 
        $jobs[] = ['job_id' => $row['job_id'], 'input_file' => $row['input_file']];
      }
      if (! $db->exec("delete from job where time < {$midnight_yesterday}")) throw new Exception('Cannot delete rows of old jobs.');
    } catch (Exception $e) {
      fwrite(STDERR, $e->getMessage());
      exit(9);
    } finally {
      $db->close();
    }
    if (empty($jobs)) return true;
    foreach ($jobs as $job) {
      $input_file_path = JOB_INPUT_DIR.'/'.$job['input_file'];
      $thumbnail_file_path = THUMBNAIL_DIR.'/'.$job['input_file'];
      $result_file_path = JOB_RESULT_DIR.'/'.$job['job_id'].'json';
      $file_paths = [$input_file_path, $thumbnail_file_path, $result_file_path];
      array_map(array($this, 'unlink_file'), $file_paths);
    }
  }

  public function main() {
    $this->check_dir_perm($this->lock_dir);
    $this->write_new_file($this->lock_file_path);
    $now = new DateTimeImmutable();
    $this->clean_old_session_data($now);
    $this->clean_old_job_data($now);
    $this->unlink_file($this->lock_file_path);
  }
}

$cleaner = new Cleaner();
$cleaner->main();
?>
