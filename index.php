<?
header("HTTP/1.0 200 Ok");
header('Content-Type: text/html; charset=utf-8');
$mt=microtime();
require_once("config/init.php");
require_once("engine/classes/Config.class.php");
Config::LoadFromFile("config/config.db.php");
Config::LoadFromFile("config/config.php");
require_once("engine/classes/Engine.class.php");

$oRouter=Router::getInstance();
$oRouter->Exec();
if (Config::Get("app.debug")){ 
	print("<table bgcolor=white cellpadding=10><tr><td valign=top><p>Loadtime:<br>".(microtime()-$mt)."</p></td>");
	$aDbStat=ModuleDatabase::GetStat();
	$aCacheStat=ModuleCache::GetStat();
	print("<td valign=top><p>DB:<br>select: ".round($aDbStat['select'])."<br>selectrow: ".round($aDbStat['selectrow'])."<br>exec: ".round($aDbStat['exec'])."<br>query: ".$aDbStat['query']."</p></td>");
	print("<td valign=top><p>Cache:<br>get: ".round($aCacheStat['get'])."<br>set: ".round($aCacheStat['set'])."</p></td></tr></table>");
}
// getImage('files/patterns/e2a5c985.jpg' ,'max');
// Engine::getInstance()->Cache_Clean();