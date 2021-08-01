<?php

require_once('include/const.php');
require_once('model.php');

class Controller {

  private $url;
  private $model;

  public function __construct() {
    $this->model = new Model();
    // e.g. /login?login_failed=1 to /login
    $this->url = $this->model->trim_query_string(REQUEST_URI);
  }

  public function route() {
    switch ($this->url) {
    case URL['LOGIN_HOME']:
      if (REQUEST_METHOD !== 'GET') $this->model->redirect(URL['LOGIN_HOME']);
      $this->model->set_session_updated();
      $this->model->set_session_referer();
      include_once('view/login/index.php');
      break;
    case ACTION_URL['LOGIN_VERIFY']:
      if (! (REQUEST_METHOD === 'POST' && $_SESSION['referer'] === URL['LOGIN_HOME'])) $this->model->redirect(URL['HOME']);
      $this->model->set_session_updated();
      $this->model->set_session_referer();
      if ($this->model->login() === false) $this->model->redirect(URL['LOGIN_HOME'], 'login_failed=1');
      $this->model->redirect(URL['HOME']);
      break;
    case ACTION_URL['LOGOUT']:
      if (! (REQUEST_METHOD === 'GET' && isset($_SESSION['user_name']))) $this->model->redirect(URL['HOME']);
      $this->model->logout();
      $this->model->redirect(URL['LOGIN_HOME']);
      break;
    case URL['HOME']:
      $this->model->check_page_permission('GET');
      include_once('view/index.php');
      break;
    case URL['JOB_HOME']:
      $this->model->check_page_permission('GET');
      include_once('view/job/index.php');
      break;
    case ACTION_URL['JOB_SUBMIT']:
      if ($_SESSION['referer'] !== URL['JOB_HOME']) $this->model->redirect(URL['HOME']);
      $this->model->check_page_permission('POST');
      $this->model->examine_file_upload();
      $input_file_name = $this->model->put_input_file();
      include_once('view/job/submit.php');
      break;
    case AJAX_URL['SUBMIT']:
      if ($_SESSION['referer'] !== ACTION_URL['JOB_SUBMIT']) $this->model->redirect(URL['HOME']);
      $this->model->check_page_permission('POST');
      $input_file_name = $_POST['input_file_name'];
      $job_id = $this->model->submit_job($input_file_name);
      include_once('ajax/submit.php');
      break;
    case URL['JOB_LIST']:
      $this->model->check_page_permission('GET');
      $this->model->update_job_finished();
      $job_list = $this->model->get_job_list();
      include_once('view/job/list.php');
      break;
    case URL['JOB_RESULT']:
      $this->model->check_page_permission('GET');
      $job_id = (isset($_GET['job_id'])) ? $_GET['job_id'] : $this->model->redirect(URL['HOME']) ;
      $this->model->check_job_permission($job_id);
      $this->model->update_job_finished();
      if (! $this->model->is_job_finished($job_id)) $this->model->redirect(URL['HOME']);
      $input_file_name = $this->model->get_input_file_name($job_id);
      $thumbnail_available = $this->model->make_thumbnail($input_file_name);
      include_once('view/job/result.php');
      break;
    default:
      $this->model->redirect(URL['HOME']);
    }
  }
}
?>
