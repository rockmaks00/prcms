<?
$aGroups = Engine::GetInstance()->Banner_GetGroups();
$aValues = array();
foreach ($aGroups as $i => $oGroup) {
	if( !$i ) $iDefault = $oGroup->getId();
	$aValues[$oGroup->getId()] = ( $i ? $oGroup->getTitle() : "По умолчанию (".$oGroup->getTitle().")");
}
$this->SetConfig("banner", null, array(
	"template" => array(
		"title" => "Шаблон",
		"type" => "select",
		"values" => array(
			"default"=>"По умолчанию (default)"
		),
		"default" => "default"
	),
	"group" => array(
		"title" => "ID Группы баннеров",
		"type" => "select",
		"values" => $aValues,
		"default" => $iDefault
	),
	"width" => array(
		"title" => "Ширина баннера, px",
		"type" => "text",
		"default" => 200
	),
	"height" => array(
		"title" => "Высота баннера, px",
		"type" => "text",
		"default" => 150
	),
	"crop" => array(
		"title" => "Обрезать",
		"type" => "checkbox",
		"default" => 1
	)
));