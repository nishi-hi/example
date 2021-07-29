<?php

require_once('include/error.php');
require_once('include/session.php');
require_once('include/struct.php');

class Model {

  public function __construct() {
    session_set_save_handler(new MySessionHandler(SESSION_DB_FILE), true);
    //session_start(['use_strict_mode' => true, 'cookie_httponly' => true, 'cookie_secure' => true, 'sid_length' => '48', 'sid_bits_per_character' => '6', 'gc_maxlifetime' => MAXLIFETIME]);
    session_start(['use_strict_mode' => true, 'cookie_httponly' => true, 'sid_length' => '48', 'sid_bits_per_character' => '6', 'gc_maxlifetime' => MAXLIFETIME]);
    session_regenerate_id(true);
  }

  public function redirect($url, $query_string=null) {
    if (is_null($query_string)) {
      header('Location: '.REQUEST_SCHEME.'://'.HTTP_HOST.$url);
    } else {
      header('Location: '.REQUEST_SCHEME.'://'.HTTP_HOST.$url.'?'.$query_string);
    }
    exit;
  }

  private function send_error_header($error, $exit=false) {
    header('HTTP/1.1 '.$error->getCode().' '.$error->getMessage());
    if ($exit) exit;
  }
      
  private function display_error($error) {
    $this->send_error_header($error);
    include_once('view/common/error.php');
  }

  public function trim_query_string($uri) {
    return explode('?', htmlspecialchars($uri, ENT_QUOTES, 'UTF-8'), 2)[0];
  }

  public function set_session_updated() {
    $_SESSION['updated'] = time();
  }

  public function set_session_referer() {
    $_SESSION['referer'] = $this->trim_query_string(REQUEST_URI);
  }

  public function login() {
    $user_name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    try {
      $db = new SQLite3(DB_FILE, SQLITE3_OPEN_READONLY);
      $numRow = $db->querySingle("select count(*) from user where user_name = '{$user_name}'");
      $hashedPassword = $db->querySingle("select password from user where user_name = '{$user_name}'");
    } catch (Exception $e) {
      return false;
    } finally {
      $db->close();
    }
    if ($numRow === 0) return false;
    if (! password_verify($password, $hashedPassword)) return false;
    session_regenerate_id(true);
    $_SESSION['user_name'] = $user_name;
    return true;
  }

  public function logout() {
    $_SESSION =[];
    if (! empty($_SESSION)) return false;
    return true;
  }

  public function is_login() {
    if (! isset($_SESSION['user_name'])) return false;
    return true;
  }

  public function is_session_alive() {
    if (! isset($_SESSION['updated']) || $_SESSION['updated'] <= time() - MAXLIFETIME) {
      $this->logout();
      return false;
    }
    return true;
  }

  public function check_page_permission($method) {
    if (REQUEST_METHOD !== $method) $this->redirect(URL['HOME']);
    if (! $this->is_login()) $this->redirect(URL['LOGIN_HOME']);
    if (! $this->is_session_alive()) $this->redirect(URL['LOGIN_HOME'], 'session_timeout=1');
    $this->set_session_referer();
    $this->set_session_updated();
  }

  public function examine_file_upload() {
    try {
      // No file is uploaded.
      if (! (isset($_FILES) && isset($_FILES['avatar']))) throw new ExampleException(ExampleException::FILE_UPLOAD_1);
      // More than one files are posted.
      if (count($_FILES) > 1) throw new ExampleException(ExampleException::FILE_UPLOAD_2);
      // Number of the uploaded file is greater than one.
      if (is_array($_FILES['avatar']['name'])) throw new ExampleException(ExampleException::FILE_UPLOAD_3);
      // Size of the uploaded file is greater than MAX_FILE_SIZE Byte.
      if ($_FILES['avatar']['size'] > MAX_FILE_SIZE) throw new ExampleException(ExampleException::FILE_UPLOAD_4);
      // Type of the uploaded file may not be text/plain.
      if ($_FILES['avatar']['type'] !== 'image/png') throw new ExampleException(ExampleException::FILE_UPLOAD_5);
      // An error occurs during the file upload.
      if ($_FILES['avatar']['error'] !== 0) throw new ExampleException(ExampleException::FILE_UPLOAD_6);
      // Posted file is not an uploaded file.
      if (! is_uploaded_file($_FILES['avatar']['tmp_name'])) throw new ExampleException(ExampleException::FILE_UPLOAD_7);
      return true;
    } catch (Exception $e) {
      $this->display_error($e);
    }
  }

  public function put_input_file() {
    try {
      $tmp_name = $_FILES['avatar']['tmp_name'];
      $input_file_name = time().'-'.basename($tmp_name).'.png';
      $input_file_path = JOB_INPUT_DIR.'/'.$input_file_name;
      $move_result = move_uploaded_file($tmp_name, $input_file_path);
      if (! $move_result) throw new ExampleException(ExampleException::FILE_UPLOAD_9);
      return $input_file_name; 
    } catch (Exception $e) {
      $this->display_error($e);
    }
  }

  private function examine_input_file($input_file_path) {
    if (exif_imagetype($input_file_path) != IMAGETYPE_PNG) return false;
    return true;
  }

  public function submit_job($input_file_name) {
    try {
      $input_file_path = JOB_INPUT_DIR.'/'.$input_file_name;
      if (! $this->examine_input_file($input_file_path)) throw new ExampleException(ExampleException::FILE_UPLOAD_8);
      $output = [];
      $retval = null;
      $job_id = time();
      exec("/usr/bin/timeout 5 /bin/bash -c 'sleep 2 && exiftool -j ".$input_file_path." 1> ".JOB_RESULT_DIR.'/'.$job_id.'.json'."'", $output, $retval);
      if ($retval !== 0) throw new ExampleException(ExampleException::EXECUTE_COMMAND_1);
      if (count($output) !== 0) throw new ExampleException(ExampleException::EXECUTE_COMMAND_2);
    } catch (Exception $e) {
      $this->send_error_header($e, true);
      return false;
    }
    try {
      $time = time();
      $db = new SQLite3(DB_FILE, SQLITE3_OPEN_READWRITE);
      if (! $db->exec("insert into job values ({$job_id},{$time},'{$input_file_name}','{$_SESSION["user_name"]}', 0)")) throw new ExampleException(ExampleException::EXECUTE_QUERY_1);
      return $job_id;
    } catch (Exception $e) {
      $this->display_error($e);
    } finally {
      $db->close();
    }
  }

  public function update_job_finished() {
    try {
      $db = new SQLite3(DB_FILE, SQLITE3_OPEN_READWRITE);
      $numRows = $db->querySingle("select count(*) from job where user_name = '{$_SESSION['user_name']}' and finished = 0");
      if ($numRows !== 0) {
        $rows = $db->query("select job_id from job where user_name = '{$_SESSION['user_name']}' and finished = 0");
        while ($row = $rows->fetchArray()) {
          if (is_readable(JOB_RESULT_DIR.'/'.$row['job_id'].'.json')) {
            if (! $db->exec("update job set finished = 1 where job_id = '{$row['job_id']}'")) throw new ExampleException(ExampleException::EXECUTE_QUERY_1);
          }
        }
      }
      return true;
    } catch (Exception $e) {
      $this->display_error($e);
    } finally {
      $db->close();
    }
  }

  public function get_job_list() {
    try {
      $job_list = [];
      $db = new SQLite3(DB_FILE, SQLITE3_OPEN_READONLY);
      $rows = $db->query("select job_id, time, finished from job where user_name = '{$_SESSION['user_name']}' order by job_id desc");
      while ($row = $rows->fetchArray()) {
        $job = new Job();
        $job->job_id = $row['job_id'];
        $job->accept_time = date('Y-m-d H:i:s', $row['time']);
        if ($row['finished'] === 1) $job->finished = true;
        $job_list[] = $job;
      }
      return $job_list;
    } catch (Exception $e) {
      $this->display_error($e);
    } finally {
      $db->close();
    }
  }

  public function is_job_finished($job_id) {
    try {
      $db = new SQLite3(DB_FILE, SQLITE3_OPEN_READONLY);
      $numRows = $db->querySingle("select count(*) from job where job_id = '{$job_id}' and finished = 1");
      if ($numRows === 1) return true;
      return false;
    } catch (Exception $e) {
      $this->display_error($e);
    } finally {
      $db->close();
    }
  }

  public function check_job_permission($job_id) {
    try {
      $db = new SQLite3(DB_FILE, SQLITE3_OPEN_READONLY);
      $user_name = $db->querySingle("select user_name from job where job_id = '{$job_id}'");
      if ($user_name !== $_SESSION['user_name']) $this->redirect(URL['HOME']);
    } catch (Exception $e) {
      $this->display_error($e);
    } finally {
      $db->close();
    }
  }

  public function get_input_file_name($job_id) {
    try {
      $db = new SQLite3(DB_FILE, SQLITE3_OPEN_READONLY);
      $input_file_name = $db->querySingle("select input_file from job where job_id = '{$job_id}'");
      if (empty($input_file_name)) throw new ExampleException(ExampleException::EXECUTE_QUERY_2);
      return $input_file_name;
    } catch (Exception $e) {
      $this->display_error($e);
    } finally {
      $db->close();
    }
  }

  public function make_thumbnail($input_file_name) {
    $output = [];
    $retval = null;
    exec('gm convert -thumbnail 100 '.JOB_INPUT_DIR.'/'.$input_file_name.' '.THUMBNAIL_DIR.'/'.$input_file_name, $output, $retval);
    if ($retval !== 0) return false;
    if (count($output) !== 0) return false;
    return true;
  }

}
?>
