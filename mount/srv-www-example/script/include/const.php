<?php

require_once('util.php');

// define('ENV', 'production');
define('ENV', 'staging');

// When include from cleaner.php, this statement is not needed.
if (isset($_SERVER['HTTP_HOST'])) {

  $server_define = function($key) {
    if (isset($_SERVER[$key]) && ! empty($_SERVER[$key])) {
      if ($key === 'CONTEXT_PREFIX') {
        define($key, preg_replace('/\/$/', '', $_SERVER[$key]));
      } else {
        define($key, $_SERVER[$key]);
      }
    } else {
      define($key, null);
    }
  };

  array_map($server_define, ['CONTEXT_PREFIX', 'REQUEST_METHOD', 'REQUEST_SCHEME', 'REQUEST_URI', 'HTTP_HOST']);

  $url = ['HOME' => '/', 'LOGIN_HOME' => '/login', 'JOB_HOME' => '/job', 'JOB_LIST' => '/job/list', 'JOB_RESULT' => '/job/result'];
  $action_url = ['LOGIN_VERIFY' => '/login/verify', 'LOGOUT' => '/logout', 'JOB_SUBMIT' => '/job/submit'];
  $ajax_url = ['SUBMIT' => '/ajax/submit'];

  if (is_null(CONTEXT_PREFIX)) {
    define('URL', $url);
    define('ACTION_URL', $action_url);
    define('AJAX_URL', $ajax_url);
  } else {
    $add_context_prefix = function(&$val, $key) { $val = CONTEXT_PREFIX.$val; };
    $url_cp = $url; array_walk($url_cp, $add_context_prefix);
    $action_url_cp = $action_url; array_walk($action_url_cp, $add_context_prefix);
    $ajax_url_cp = $ajax_url; array_walk($ajax_url_cp, $add_context_prefix);
    define('URL', $url_cp);
    define('ACTION_URL', $action_url_cp);
    define('AJAX_URL', $ajax_url_cp);
  }

}

// Directory parameters
if (ENV === 'production') {
  define('ROOT_DIR', '/usr/proj/example');
  define('DOC_ROOT', ROOT_DIR.'/htdocs');
} else if (ENV === 'staging') {
  define('ROOT_DIR', '/srv/www/example');
  define('DOC_ROOT', ROOT_DIR.'/public');
}
define('SCRIPT_DIR', ROOT_DIR.'/script');
define('DATA_DIR', ROOT_DIR.'/data');
define('DB_DIR', DATA_DIR.'/db');
define('DB_FILE', DB_DIR.'/example.db');
define('SESSION_DB_FILE', DB_DIR.'/session.db');
define('JOB_DIR', DATA_DIR.'/job');
define('JOB_INPUT_DIR', JOB_DIR.'/input');
define('JOB_RESULT_DIR', JOB_DIR.'/result');
define('THUMBNAIL_DIR', DOC_ROOT.'/thumbnails');

// Application parameters
if (isset($_SERVER['HTTP_HOST'])) {
  define('MAX_FILE_SIZE', Util::to_bytes(ini_get('upload_max_filesize')));  // Bytes
  define('MAXLIFETIME', intVal(ini_get('session.gc_maxlifetime')));
} else {
  $ini = parse_ini_file(DOC_ROOT.'/.user.ini');
  define('MAX_FILE_SIZE', Util::to_bytes($ini['upload_max_filesize'])); // Bytes
  define('MAXLIFETIME', intVal($ini['session.gc_maxlifetime']));
}
define('REPORT_INTERVAL', 300);

?>
