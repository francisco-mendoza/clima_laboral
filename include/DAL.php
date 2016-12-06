<?php  
class DAL{	
  public function __construct(){
	$this->conexion();
  }
  public static function conexion(){
    $conn = mysql_connect(DB_HOST, DB_USER, DB_PASS)
      or die("<br> No se pudo conectar a MySQL server");
    mysql_select_db(DB_NAME,$conn)
      or die("<br> No se pudo seleccionar la base de datos");
    return $conn;
  }
  
  private function query($sql){
    $this->dbconnect(); 
    $res = mysql_query($sql);
    if ($res){
      if (strpos($sql,'SELECT') === false){
        return true;
      }
    } else{
      if (strpos($sql,'SELECT') === false){
        return false;
      } else{
        return null;
      }
    }
    $results = array();
 
    while ($row = mysql_fetch_array($res)){ 
      $result = new DALQueryResult(); 
      foreach ($row as $k=>$v){
        $result->$k = $v;
      }
      $results[] = $result;
    }
    return $results;      
  }  
  
}
?>