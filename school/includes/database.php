<?php

class Database
{
  /* Public  */
  public function __construct($ihostname, $idatabaseName, $ilogin, $ipassword, $iport)
  {
    $this->hostname = $ihostname;
    $this->port = $iport;
    $this->databaseName = $idatabaseName;
    $this->login = $ilogin;
    $this->password = $ipassword;
    $this->Connect();
  }

  public function Connect()
  {
    if ($this->connected)
      return 1;
    $this->connection = 
    new mysqli($this->hostname, $this->login, $this->password, $this->databaseName, $this->port);
    // Check connection
    if ($this->connection->connect_error) {
      die("Connection failed ($this->hostname:$this->port): " . $this->connection->connect_error);
    }
    //echo "Connected successfully";
  }

  public function Close()
  {
    $this->connection->close();
  }

  public function Query($query, $echoQuery=0)
  {
    $this->Connect();
    if ($echoQuery)
      echo "<br>Query: ".$query."<br>";
    return $this->connection->query($query);
  }

  public function real_escape_string($inString)
  {
    return $this->connection->real_escape_string($inString); // escape special characters in query string
  }

  public function GetNumRows($queryResult)
  {
    return $this->connection->num_rows($queryResult);
  }

  public function GetConnection()
  {
    return $this->connection;
  }

  private $connected = 0;
  /* Private */
  private $hostname;    // db server
  private $port;
  private $databaseName;
  private $login;
  private $password;
  private $connection; // mysql object
}

?>