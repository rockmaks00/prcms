<?
class ModuleDatabase extends Module {
	//static protected $oInstance=null;
	static protected $oDb=null;
	static protected $aStat=array();
	static protected $aTables=null;
	
	public function Init(){
	
	}
	
	public function Connect(){
		/* Соединяемся с базой */
		try {
			self::$oDb = new PDO('mysql:host='.Config::Get("db.host").';dbname='.Config::Get("db.dbname"), Config::Get("db.user"), Config::Get("db.pass"));
			self::$oDb->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );  
			self::$oDb->exec("SET NAMES UTF8");
		} catch (PDOException $e) {
			die("Error!: " . $e->getMessage() . "<br/>");
		}

		$this->aTables = array_map( function($aVar){ return $aVar["Tables_in_".Config::Get("db.dbname")]; }, $this->Select('SHOW TABLES') );
		return $this;
	}
	
	public function Query($sql){
		try {
			$args=func_get_args();
			array_shift($args);
			
			$sql=self::$oDb->prepare($sql);
			$result=$sql->execute($args);
			$lastid=self::$oDb->lastInsertId();
			self::$aStat['query']++;
			
			if ($lastid) return $lastid;
			else return($result);
		}catch (PDOException $e) {
			die("Error!: " . $e->getMessage() . "<br/>");
		}
	}
	public function Select($sql){
		try {
			$args=func_get_args();
			array_shift($args);
			
			$sql=self::$oDb->prepare($sql);
			$sql->execute($args);
			self::$aStat['select']++;
			
			return $sql->fetchAll(PDO::FETCH_ASSOC);

		}catch (PDOException $e) {
			die("Error!: " . $e->getMessage() . "<br/>");
		}
	}
	public function SelectRow($sql){
		try {
			$args=func_get_args();
			array_shift($args);
			
			$sql=self::$oDb->prepare($sql);
			$sql->execute($args);
			self::$aStat['selectrow']++;
			
			return $sql->fetch(PDO::FETCH_ASSOC);

		}catch (PDOException $e) {
			die("Error!: " . $e->getMessage() . "<br/>");
		}
	}
	public function Exec($sql){
		try {
			self::$oDb->exec($sql);
			self::$aStat['exec']++;
			
		} catch (PDOException $e) {
			die("Error!: " . $e->getMessage() . "<br/>");
		}
		return true;
	}
	
	public function GetStat(){
		return self::$aStat;
	}

	public function CheckTableExists($sTable){
		if( is_array($this->aTables) )
			return in_array($sTable, $this->aTables);
		else return false;
	}
	
	public function __destruct(){
		/* Разрываем соединение с базой */
		self::$oDb = null;
	}
}