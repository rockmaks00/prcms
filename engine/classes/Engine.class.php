<?
require_once("engine/classes/EngineObject.class.php");
require_once("engine/classes/Module.class.php");
require_once("engine/classes/Router.class.php");
require_once("engine/classes/Db.class.php");
require_once("engine/classes/Entity.class.php");
require_once("engine/classes/Component.class.php");
require_once("engine/classes/Hook.class.php");

class Engine extends EngineObject {
	static protected $oInstance=null;
	protected $aModules=array();
	protected $aHooks=array();
	protected $aConfigModule=array();
	static protected $oConnect=null;
	
	private function __construct() {
		foreach (array("files/", "cache/") as $sFolder) {
			$sFilePath = $sFolder.".htaccess";
			if( !file_exists($sFilePath) ){
				$rFile = fopen($sFilePath,"a");
				fwrite($rFile, "\r\nphp_flag engine 0\r\nRemoveHandler .phtml .php .php3 .php4 .php5 .php6 .phps .cgi .exe .pl .asp .aspx .shtml .shtm .fcgi .fpl .jsp .htm .html .wml\r\nAddType application/x-httpd-php-source .phtml .php .php3 .php4 .php5 .php6 .phps .cgi .exe .pl .asp .aspx .shtml .shtm .fcgi .fpl .jsp .htm .html .wml");
			}
		}
	}
	
	static public function getInstance() {
		if (isset(self::$oInstance) and (self::$oInstance instanceof self)) {
			return self::$oInstance;
		} else {
			self::$oInstance= new self();
			return self::$oInstance;
		}
	}
	
	public function Init() {
		$this->LoadModules();
		$this->InitModules();
	}
	
	protected function LoadModules() {
		foreach ($this->aConfigModule['autoLoad'] as $sModuleName) {
			$sModuleClass='Module'.$sModuleName;
			if (!isset($this->aModules[$sModuleClass])) {
				$this->LoadModule($sModuleClass);
			}
		}
	}
	
	public function LoadModule($sModuleName,$bInit=false) {
		$oModule = new $sModuleName($this);
		$oModule->Init();
		$this->aModules[$sModuleName]=$oModule;
		return $oModule;
	}
	
	public function LoadHook($sModuleName,$bInit=false) {
		$oModule = new $sModuleName($this);
		$oModule->Init();
		$this->aHooks[$sModuleName]=$oModule;
		return $oModule;
	}

	protected function InitModules() {
		foreach ($this->aModules as $oModule) {
			if(!$oModule->isInit()) {
				$oModule->Init();
				$oModule->SetInit();
			}
		}
	}
	
	public static function GetDb($sClassName,$sName=null,$oConnect=null) {		
		if (preg_match("/^(?:Component\w+_)?Module(\w+)$/i",$sClassName,$aMatch)) {
			if (!$sName) {
				$sName=$aMatch[1];
			}
			$sClass=$sClassName.'_Db'.$sName;
			if (!Engine::$oConnect){
				Engine::$oConnect=Engine::getInstance()->Database_Connect();
			}
			
			return new $sClass(Engine::$oConnect);
		}		
		return null;
	}
	public static function GetEntity($sClassName, $aData=array(), $sName=null) {		
		if (preg_match("/^Component(\w+)\_(\w+)$/i",$sClassName,$aMatch)){
			$sComponent=$aMatch[1];
			$sModule=$aMatch[2];
			if (!$sName){
				$sEntity=$aMatch[2];
			}else{
				$sEntity=$sName;
			}
			$sClass="Component".$sComponent."_Module".$sModule."_Entity".$sEntity;
		}else{
			if (!$sName) {
				$sName=$sClassName;
			}
			$sClass='Module'.$sClassName.'_Entity'.$sName;
		}
		return new $sClass($aData);
	}
	
	public function __call($sName,$aArgs) {
		return $this->_CallModule($sName,$aArgs);
	}
	
	public function GetModule($sName) {
		/**
		 * Поддержка полного синтаксиса при вызове метода модуля
		 */
		$sType="Module";
		if (preg_match("/^Component(\w+)\_Module(\w+)\_(\w+)$/i",$sName,$aMatch)) {
			$sName="Component{$aMatch[1]}_{$aMatch[2]}_{$aMatch[3]}";
			$sType="Module";
		}
		if (preg_match("/^Module(\w+)\_(\w+)$/i",$sName,$aMatch)) {
			$sName="{$aMatch[1]}_{$aMatch[2]}";
			$sType="Module";
		}
		if (preg_match("/^Component(\w+)\_Hook(\w+)\_(\w+)$/i",$sName,$aMatch)) {
			$sName="Component{$aMatch[1]}_{$aMatch[2]}_{$aMatch[3]}";
			$sType="Hook";
		}
		if (preg_match("/^Hook(\w+)\_(\w+)$/i",$sName,$aMatch)) {
			$sName="{$aMatch[1]}_{$aMatch[2]}";
			$sType="Hook";
		}
		
		$aName = explode("_",$sName);

		if(count($aName) == 2) {
			$sModuleName  = $aName[0];
			$sModuleClass = $sType.$aName[0];
			$sMethod = $aName[1];
		} else {
			$sModuleName  = $aName[0].'_'.$aName[1];
			$sModuleClass = $aName[0].'_'.$sType.$aName[1];
			$sMethod = $aName[2];
		}
		if ($sType=="Module"){
			if (isset($this->aModules[$sModuleClass])) {
				$oModule=$this->aModules[$sModuleClass];
			} else {
				$oModule=$this->LoadModule($sModuleClass,true);
			}
		}elseif($sType=="Hook"){
			if (isset($this->aHooks[$sModuleClass])) {
				$oModule=$this->aHooks[$sModuleClass];
			} else {
				$oModule=$this->LoadHook($sModuleClass,true);
			}
		}
		
		return array($oModule,$sModuleName,$sMethod);
	}
	
	public function _CallModule($sName,$aArgs) {
		$sArgs='';
		$aStrArgs=array();
		foreach ($aArgs as $sKey => $arg) {
			$aStrArgs[]='$aArgs['.$sKey.']';
		}
		$sArgs=join(',',$aStrArgs);

		list($oModule,$sModuleName,$sMethod)=$this->GetModule($sName);
		if (!method_exists($oModule,$sMethod)) {
			throw new Exception("No method: ".$sModuleName.'->'.$sMethod.'()');
		}
		$aArgsRef=array();
		foreach ($aArgs as $key=>$v) {
			$aArgsRef[]=&$aArgs[$key];
		}
		$result=call_user_func_array(array($oModule,$sMethod),$aArgsRef);
		return $result;
	}
}

spl_autoload_register(function ($sClassName) {
	if(preg_match("/^Module(\w+)$/i",$sClassName,$aMatch)) {
		$sName = ucfirst($aMatch[1]);
		$sFileClass= 'modules/'.strtolower($sName).'/'.$sName.'.class.php';	
			
		if (file_exists($sFileClass)) {
			require_once($sFileClass);
		} else {
			$sFileClass = str_replace('modules/','engine/modules/',$sFileClass);
			if(file_exists($sFileClass)) require_once($sFileClass);
		}
	}	
	if (preg_match("/^Module(\w+)\_Db(\w+)$/i",$sClassName,$aMatch)) {
		$sFileClass='modules/'.strtolower($aMatch[1]).'/db/'.$aMatch[2].'.db.class.php';		
		if (file_exists($sFileClass)) {
			require_once($sFileClass);			
		} else {
			$sFileClass = str_replace('modules/','engine/modules/',$sFileClass);
			if(file_exists($sFileClass)) require_once($sFileClass);
		}
	}
	if (preg_match("/^Module(\w+)\_Entity(\w+)$/i",$sClassName,$aMatch)) {			
		$sFileClass='modules/'.strtolower($aMatch[1]).'/entity/'.$aMatch[2].'.entity.class.php';
		if (file_exists($sFileClass)) {
			require_once($sFileClass);			
		} else {
			$sFileClass = str_replace('modules/','engine/modules/',$sFileClass);
			if(file_exists($sFileClass)) require_once($sFileClass);
		}
	}
	if(preg_match("/^Component(\w+)_ModuleParams$/i",$sClassName,$aMatch)) {
		$sComponent = ucfirst($aMatch[1]);
		$sModule = ucfirst($aMatch[2]);
		$sFileClass= 'components/'.strtolower($sComponent).'/modules/'.strtolower($sModule).'/'.$sModule.'.class.php';	
		if (file_exists($sFileClass)) {
			require_once($sFileClass);
		}
	}
	if(preg_match("/^Component(\w+)_Module(\w+)$/i",$sClassName,$aMatch)) {
		$sComponent = ucfirst($aMatch[1]);
		$sModule = ucfirst($aMatch[2]);
		$sFileClass= 'components/'.strtolower($sComponent).'/modules/'.strtolower($sModule).'/'.$sModule.'.class.php';	
		if (file_exists($sFileClass)) {
			require_once($sFileClass);
		}
	}
	if(preg_match("/^Component(\w+)_Module(\w+)_Db(\w+)$/i",$sClassName,$aMatch)) {
		$sComponent = ucfirst($aMatch[1]);
		$sModule = ucfirst($aMatch[2]);
		$sDb = ucfirst($aMatch[3]);
		$sFileClass= 'components/'.strtolower($sComponent).'/modules/'.strtolower($sModule).'/db/'.$sDb.'.db.class.php';	
		if (file_exists($sFileClass)) {
			require_once($sFileClass);
		}
	}
	if(preg_match("/^Component(\w+)_Module(\w+)_Entity(\w+)$/i",$sClassName,$aMatch)) {
		$sComponent = ucfirst($aMatch[1]);
		$sModule = ucfirst($aMatch[2]);
		$sEntity = ucfirst($aMatch[3]);
		$sFileClass= 'components/'.strtolower($sComponent).'/modules/'.strtolower($sModule).'/entity/'.$sEntity.'.entity.class.php';	
		if (file_exists($sFileClass)) {
			require_once($sFileClass);
		}
	}	
	if(preg_match("/^Component(\w+)_Hook(\w+)$/i",$sClassName,$aMatch)) {
		$sComponent = ucfirst($aMatch[1]);
		$sHook = ucfirst($aMatch[2]);
		$sFileClass = 'components/'.strtolower($sComponent).'/hooks/Hook'.$sHook.'.class.php';
		if (file_exists($sFileClass)) {
			require_once($sFileClass);
		}
	}

	if(preg_match("/^Component(\w+)_Params_(\w+)$/i",$sClassName,$aMatch)) {
		$sComponent = ucfirst($aMatch[1]);
		$sHook = ucfirst($aMatch[2]);
		$sFileClass = 'components/'.strtolower($sComponent).'/hooks/Hook'.$sHook.'.class.php';
		if (file_exists($sFileClass)) {
			require_once($sFileClass);
		}
	}
	if(preg_match("/^Hook(\w+)$/i",$sClassName,$aMatch)) {
		$sHook = ucfirst($aMatch[1]);
		$sFileClass= 'hooks/'.strtolower($sHook).'/Hook'.$sHook.'.class.php';
		if (file_exists($sFileClass)) {
			require_once($sFileClass);
		}
	}

	if(preg_match("/^Smarty(\w+)$/i",$sClassName,$aMatch)) {
		$file = SMARTY_DIR."sysplugins/".strtolower($sClassName).".php";
		if (file_exists($file)) {
			require $file;
	        return true;
	    }
	    return false;
    }
});