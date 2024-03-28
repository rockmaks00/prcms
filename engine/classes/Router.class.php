<?
class Router extends EngineObject
{
	static public $argv = null;
	static protected $aArgvNodes = array();
	static protected $oInstance = null;
	protected $oEngine = null;
	static protected $oNode = null;
	protected $sRequestUri = null;
	static protected $sAction = null;
	static protected $sActionAdmin = null;
	static protected $aParams = array();
	static protected $oComponent = null;
	static protected $sCurrentTemplate = null;
	static protected $sNodeUrl = null;

	public static function getInstance()
	{
		if (isset(self::$oInstance) and (self::$oInstance instanceof self)) {
			return self::$oInstance;
		} else {
			self::$oInstance = new self();
			return self::$oInstance;
		}
	}

	private function __construct()
	{
		$this->oEngine = Engine::getInstance();
		$this->oEngine->Init();
	}

	public function Exec()
	{
		Router::LoadUrl($_SERVER["REQUEST_URI"]);
		$this->oEngine->Template_DefineTemplate();
		$this->oEngine->Template_Display(self::$oComponent->GetTemplate());
	}

	public static function LoadUrl($sUrl)
	{
		$oEngine = Engine::getInstance();
		Router::SetAction(null);
		self::$aParams = array();
		self::$argv = array();

		$r = preg_split("/\?/", $sUrl, 2);
		if (!empty($r[0])) {
			$_path_uri = $r[0] . "/";
		}

		$rgv = preg_split("/\//", $_path_uri);
		foreach ($rgv as $rgv1) {
			if ($rgv1 || is_numeric($rgv1)) self::$argv[] = ($rgv1);
		}
		if (self::$argv[0] == "index.php") self::$argv = array();
		$oNode = $oEngine->Node_GetNodeById(1);
		foreach (self::$argv as $i => $sUrl) {
			$tmp = $oEngine->Node_GetNodeByUrl($sUrl, $oNode->getId());
			if (!$tmp) {
				self::$sAction = self::$argv[$i];
				for ($j = $i + 1; $j < count(self::$argv); $j++) {
					self::$aParams[] = self::$argv[$j];
				}
				break;
			} else {
				$sRouterNodeUrl = "";
				for ($j = 0; $j <= $i; $j++) {
					$sRouterNodeUrl .= self::$argv[$j] . "/";
				}
				Router::SetNodeUrl($sRouterNodeUrl);
				self::$aArgvNodes[] = $tmp;
				$oNode = $tmp;
			}
		}
		self::$oNode = $oNode;
		self::$sActionAdmin = self::$argv[3];

		$sClass = "Component" . ucfirst(self::$oNode->getComponentObject()->getName());
		include_once("components/" . self::$oNode->getComponentObject()->getName() . "/classes/" . $sClass . ".class.php");
		self::$oComponent = new $sClass();
		self::$oComponent->Exec();

		$oStat = Engine::GetEntity('Stat');
		$oStat->setNode($oNode->getId());
		$oStat->setIp($_SERVER['REMOTE_ADDR']);
		$oEngine->Stat_Add($oStat);
	}

	/*public static function GetMenuCurrency( $oNode ){
		$oCurrentNode = self::$oNode;
		if( $oNode->getId() == $oCurrentNode->GetId() ) return true;
		
		while( $oCurrentNode->getParent() !=0 ){
			if( $oNode->getId() == $oCurrentNode->GetId() ) return true;
			$oCurrentNode = $this->Node_GetNodeById( $oCurrentNode->getParent() );
		};
		return false;
	}*/

	protected function ParseUrl()
	{
	}

	public static function GetParams()
	{
		return self::$aParams;
	}

	public static function SetParams($aParams)
	{
		self::$aParams = $aParams;
	}

	public static function GetParam($iIndex)
	{
		return self::$aParams[$iIndex];
	}

	public static function GetAction()
	{
		return self::$sAction;
	}
	public static function SetAction($sAction)
	{
		self::$sAction = $sAction;
	}

	public static function GetActionAdmin()
	{
		return self::$sActionAdmin;
	}

	public static function SetActionAdmin($sAction)
	{
		self::$sActionAdmin = $sAction;
	}

	public static function GetCurrentNode()
	{
		return self::$oNode;
	}

	public static function SetCurrentNode($oNode)
	{
		self::$oNode = $oNode;
	}

	public static function GetCurrentTemplate()
	{
		return self::$sCurrentTemplate;
	}

	public static function SetCurrentTemplate($sTemplate)
	{
		self::$sCurrentTemplate = $sTemplate;
	}

	public static function GetNodeUrl()
	{
		return self::$sNodeUrl;
	}

	public static function SetNodeUrl($sUrl)
	{
		self::$sNodeUrl = $sUrl;
	}

	public static function GetArgvNodes()
	{
		return self::$aArgvNodes;
	}
}
