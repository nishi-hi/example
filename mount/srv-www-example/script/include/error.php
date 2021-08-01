<?php
// This class definition quoted from https://www.php.net/manual/ja/language.exceptions.extending.php
class NewException extends Exception {
  public function __construct($message, $code = 0, Exception $previous = null) {
    parent::__construct($message, $code, $previous);
  }
}
   
class ExampleException {
  public const NONE = 0;
  public const FILE_UPLOAD_1 = 551;
  public const FILE_UPLOAD_2 = 552;
  public const FILE_UPLOAD_3 = 553;
  public const FILE_UPLOAD_4 = 554;
  public const FILE_UPLOAD_5 = 555;
  public const FILE_UPLOAD_6 = 556;
  public const FILE_UPLOAD_7 = 557;
  public const FILE_UPLOAD_8 = 558;
  public const FILE_UPLOAD_9 = 559;
  public const EXECUTE_COMMAND_1 = 561;
  public const EXECUTE_COMMAND_2 = 562;
  public const EXECUTE_QUERY_1 = 571;
  public const EXECUTE_QUERY_2 = 572;
  // Error code matches HTTP response code.
  public function __construct($type = self::NONE) {
    switch ($type) {
      case 551:
        throw new NewException('No file is uploaded', $type);
        break;
      case 552:
        throw new NewException('Invalid file is posted', $type);
        break;
      case 553:
        throw new NewException('Number of the uploaded file is greater than one', $type);
        break;
      case 554:
        throw new NewException('Size of the uploaded file is greater than maximum size', $type);
        break;
      case 555:
        throw new NewException('Type of the uploaded file may not be text/plain', $type);
        break;
      case 556:
        throw new NewException('An error occurs during the file upload', $type);
        break;
      case 557:
        throw new NewException('Posted file is not an uploaded file', $type);
        break;
      case 558:
        throw new NewException('Posted file contains wrong data', $type);
        break;
      case 559:
        throw new NewException('Cannot put the input file', $type);
        break;
      case 561:
        throw new NewException('Command execution failed', $type);
        break;
      case 562:
        throw new NewException('Invalid result is returned', $type);
        break;
      case 571:
        throw new NewException('An error occurs during the query execution', $type);
        break;
      case 572:
        throw new NewException('Unexpected result is returned from executed query', $type);
        break;
      default:
        return 0; //No exception is thrown.
    }
  }
}
?>
