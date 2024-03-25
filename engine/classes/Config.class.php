<?
class Config
{
	protected static $_aData = array();
	public function __construct()
	{
	}
	public static function LoadFromFile($sFile)
	{
		require_once($sFile);
		self::$_aData = array_merge($config, self::$_aData);
		unset($config);
	}
	public static function Get($sParam)
	{ // Config::Get('db.host') вернет $config["db"]["host"]
		$aParams = explode(".", $sParam);
		$sResult = self::$_aData;
		foreach ($aParams as $sPar) {
			$sResult = $sResult[$sPar];
		}
		return $sResult;
	}
}
