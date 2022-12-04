<?php
include_once "database.php";
include_once "config.php";

class Application
{
  /* Public  *****************************/
  public function __construct()
  {
    global $DB_HOSTNAME;
    global $DB_NAME;
    global $DB_LOGIN;
    global $DB_PASSWORD;
    global $DB_PORT;
    $this->dbHostname = $DB_HOSTNAME;
    $this->dbPort = $DB_PORT;
    $this->dbName = $DB_NAME;
    $this->dbLogin = $DB_LOGIN;
    $this->dbPassword = $DB_PASSWORD;
    session_start();
    $this->database = new Database($this->dbHostname, $this->dbName, $this->dbLogin, $this->dbPassword, $this->dbPort);
  }
  public function GetCourses()
  {
    $query = "SELECT * FROM course";
    return $this->database->Query($query);
  }
  public function HTML_GetCourses()
  {
    $result = $this->GetCourses();
    while ($row = $result->fetch_assoc())
    {
      $rawTime = strtotime($row["time"]);
      $visualTime = date("F j, Y, G:i", $rawTime);
      echo ' <div class="col-lg-6">
              <div class="meeting-item">
                <div class="thumb">
                  <div class="price">
                    <span>$'.$row["price"].'</span>
                  </div>
                  <a href="meeting-details.php?id='.$row["id"].'"><img src="'.$this->GetRandomCourseImageURL().'" alt="'.$row["name"].'"></a>
                </div>
                <div class="down-content">
                  <div class="date">
                    <h6>'.$visualTime.'</h6>
                  </div>
                  <a href="meeting-details.html"><h4>'.$row["name"].'</h4></a>
                  <p>'.$row["description"].'</p>
                </div>
              </div>
            </div>';
    }
  }
  public function GetCourse($id)
  {
    $query = "SELECT * FROM course WHERE id=$id LIMIT 1";
    return $this->database->Query($query);
  }
  public function CreateCourse($name, $description, $time, $price)
  {
    $query = 
    "INSERT INTO course (name, description, time, price) VALUES ('$name', '$description', '$time', $price);";
    return $this->database->Query($query);
  }
  public function UpdateCourse($id, $name, $description, $time, $price)
  {
    $query = 
    "UPDATE course SET name='$name', description='$description', time='$time', price=$price WHERE id=$id;";
    return $this->database->Query($query);
  }
  public function DeleteCourse($id)
  {
    $query = "DELETE FROM course WHERE id=$id";
    $this->database->Query($query);
  }

  /* Returns
      1 - OK /  login successful
      0 - NOK / login unsuccessful
  */
  public function Login($email, $password)
  {
    $result = $this->GetAccount($email, $password);
    if ($result->num_rows == 1) // valid user
    {
      if ($row = $result->fetch_assoc()) 
      {
        $_SESSION['ACC_EMAIL'] = $row['email'];
        $_SESSION['ACC_LEVEL'] = $row['level'];
        return 1;
      }
    }
    $this->Logout();
    return 0;
  }
  public function Logout()
  {
    session_destroy();
  }

  public function GetRandomCourseImageURL()
  {
    $number = rand(1,4);
    return "assets/images/meeting-0$number.jpg";
  }

  public function IsAdmin()
  {
    if (isset($_SESSION['ACC_LEVEL']) && $_SESSION['ACC_LEVEL'] >= $this->ADMIN_LEVEL)
      return $_SESSION['ACC_LEVEL'];
    return 0;
  }

  public function EscapeString($inString)
  {
    return $this->database->real_escape_string($inString);
  }

  /* Private *****************************/
  private function GetAccount($email, $password)
  {
    $query = "SELECT * FROM account WHERE email='$email' AND password='$password' LIMIT 1";
    return $this->database->Query($query,1);
  }

  /* Returns
      1 - OK / valid credentials
      0 - NOK / invalid credentials
  */
  private function CheckAccountCredentials($email, $password)
  {
    $query = "SELECT COUNT(*) FROM account WHERE email='$email' AND email='$password' LIMIT 1";
    return $this->database->Query($query)->num_rows;
  }
  private $database; // database object
  private $dbHostname;
  private $dbPort;
  private $dbName;
  private $dbLogin;
  private $dbPassword;

  private $ADMIN_LEVEL = 1; // minimum required admin level to open an administration
}

?>