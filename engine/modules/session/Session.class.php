<?
class ModuleSession extends Module {
	public function Init() {
		session_name(Config::Get('session.name'));			
		session_set_cookie_params(
			Config::Get('session.expire'),
			Config::Get('session.path'),
			Config::Get('session.host')
		);
		if(!session_id()) {
			session_regenerate_id();
		}
		session_start();
	}
	
	public function Get($var) {
		return isset($_SESSION[$var]) ? $_SESSION[$var] : null;
	}
	
	public function GetId() {
		return session_id();
	}
	
	public function Set($var, $val) {
		$_SESSION[$var]=$val;		
	}
	
	public function Delete($var) {
		unset($_SESSION[$var]);
	}
	
	public function Destroy() {
		unset($_SESSION);
		session_destroy();
	}
}