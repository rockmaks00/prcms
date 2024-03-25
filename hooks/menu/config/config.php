<?
$aMenus = Engine::GetInstance()->Menu_GetMenu();
$aValues = array();
$sDefault="";
foreach ($aMenus as $i => $oMenu) {
	if(!$i) $sDefault = $oMenu->getName();
	$aValues[$oMenu->getName()] = ( i ? $oMenu->getTitle() : "По умолчанию (".$oMenu->getTitle().")");
}
$this->SetConfig("menu", null, array(
	"template" => array(
		"title" => "Шаблон",
		"type" => "select",
		"values" => array(
			"default"=>"По умолчанию (default)"
		),
		"default" => "default"
	),
	"menu" => array(
		"title" => "Меню",
		"type" => "select",
		"values" => $aValues,
		"default"=> $sDefault
	)
));