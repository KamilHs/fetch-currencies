<?php

require_once "dbConfig.php";


class Database
{
  private $connection = null;
  private $currencyDataTableName = "currencydata";
  private $currencyDataItemTableName = "currencydataitem";

  public function __construct($server, $user, $pwd, $database)
  {
    try {

      $this->connection = new PDO("mysql:host=$server;dbname=$database", $user, $pwd);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->initDatabase();
    } catch (PDOException $e) {
      echo "Failed to connect: " . $e->getMessage();
      exit();
    }
  }

  private function initDatabase()
  {
    try {

      $query = "CREATE TABLE IF NOT EXISTS `$this->currencyDataTableName` (
            `Id` int(11) NOT NULL,
            `date` date NOT NULL
          ) ;
          CREATE TABLE IF NOT EXISTS `$this->currencyDataItemTableName` (
            `Id` int(11) NOT NULL,
            `dataId` int(11) NOT NULL,
            `code` text NOT NULL,
            `nominal` int(11) NOT NULL,
            `name` text NOT NULL,
            `value` float(11) NOT NULL
          ) ;
          ALTER TABLE `$this->currencyDataTableName`
            ADD PRIMARY KEY (`Id`);        
          ALTER TABLE `$this->currencyDataItemTableName`
            ADD PRIMARY KEY (`Id`),
            ADD KEY `dataId` (`dataId`);     
          ALTER TABLE `$this->currencyDataTableName`
            MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;     
          ALTER TABLE `$this->currencyDataItemTableName`
            MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;  
          ALTER TABLE `$this->currencyDataItemTableName`
            ADD CONSTRAINT `dataId-id` FOREIGN KEY (`dataId`) REFERENCES `$this->currencyDataTableName` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;
          COMMIT;";

      $this->connection->exec($query);
    } catch (PDOException $e) {
      echo "Failed to init database: " . $e->getMessage();
      exit();
    }
  }

  public function writeData($data)
  {
    if (!$data) {
      return  "Invalid Input";
    }
    $formattedDate = date("Y-m-d", strtotime($data["Date"]));
    $res = $this->isArleadyAdded($formattedDate);
    if (is_numeric($res) &&  $res == 0) {
      $createDailyDataQry = "INSERT INTO `$this->currencyDataTableName` (date) VALUES('$formattedDate')";
      try {
        $this->connection->exec($createDailyDataQry);
        $id = $this->connection->lastInsertId();

        foreach ($data["Currencies"] as $_ => $currency) {
          $insertCurrencyQry = "INSERT INTO $this->currencyDataItemTableName (dataId,nominal,name,code,value) VALUES(
                    $id,
                    $currency->nominal,
                    '$currency->name',
                    '$currency->code',
                    $currency->value);";
          $this->connection->exec($insertCurrencyQry);
        }
      } catch (PDOException $e) {
        return "Failed to write to database: " . $e->getMessage();
      }
      return  "";
    } else {
      return "This data is already fetched";
    }
  }
  private function isArleadyAdded($date)
  {
    try {
      return $this->connection->query("SELECT COUNT(Id) FROM $this->currencyDataTableName WHERE date='$date'")->fetchColumn();
    } catch (PDOException $e) {
      return "Falied to read from database: " . $e->getMessage();
    }
  }

  public function readAllDates()
  {
    try {
      return $this->connection->query("SELECT * FROM $this->currencyDataTableName")->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return "Failed to read from database: " . $e->getMessage();
    }
  }

  public function readCurrencies($date, $id)
  {
    if (!$date || !$id) return null;
    $date = stripslashes(htmlspecialchars($date));
    $id = stripslashes(htmlspecialchars($id));

    if ($this->isArleadyAdded($date) === 0) return null;
    try {
      $stmt = $this->connection->prepare("SELECT * FROM $this->currencyDataItemTableName WHERE dataId=:id");
      $stmt->bindParam(":id", $id);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return "Failed to read from database: " . $e->getMessage();
    }
  }
}


$database = new Database($server, $user, $pwd, $database);
