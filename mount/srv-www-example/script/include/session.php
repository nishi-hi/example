<?php
class MySessionHandler implements SessionHandlerInterface {

  private $db;

  public function __construct($db_file) { $this->db = new SQLite3($db_file); }

  public function open($db, $session_name) { return true; }

  public function close() { return true; }

  public function read($session_id) {
    $session_data = '';
    $stmt = $this->db->prepare('select session_data from session where session_id = :session_id');
    if ($stmt) {
      $stmt->bindParam(':session_id', $session_id);
      $res = $stmt->execute();
      if ($res->numColumns()) {
        $row = $res->fetchArray(SQLITE3_ASSOC);
        if ($row !== false) {
          $session_data = $row['session_data'];
        }
      }
    }
    return $session_data;
  }

  public function write($session_id, $session_data) {
    $session_updated = time();
    $stmt = $this->db->prepare('replace into session (session_id, session_updated, session_data) values (:session_id, :session_updated, :session_data)');
    if ($stmt) {
      $converted_session_id = htmlspecialchars($session_id);
      $stmt->bindParam(':session_id', $converted_session_id);
      $stmt->bindParam(':session_updated', $session_updated);
      $stmt->bindParam(':session_data', $session_data);
      $stmt->execute();
    }
    return true;
  }

  public function destroy($session_id) {
    $stmt = $this->db->prepare('delete from session where session_id = :session_id');
    if ($stmt) {
      $stmt->bindParam(':session_id', $session_id);
      $stmt->execute();
    }
    return true;
  }

  public function gc($maxlifetime) {
    $stmt = $this->db->prepare('delete from session where session_updated < :session_updated');
    if ($stmt) {
      $session_updated = time() - $maxlifetime;
      $stmt->bindParam(':session_updated', $session_updated);
      $stmt->execute();
    }
    return true;
  }
}
