<?
$aNodes = Engine::GetInstance()->Node_GetNodesByComponent("news");
foreach ($aNodes as $i => $oNode) {
	$aNewsNodes[$oNode->getId()] = "id".$oNode->getId()." (".$oNode->getUrl().") ".$oNode->getTitle();
	if( !$i ) $iNewsNodeDefault = $oNode->getId();
}
unset($aNodes);

$this->SetConfig("news", "news", array(
	"template" => array(
		"title" => "Шаблон",
		"type" => "select",
		"values" => array(
			"default"=>"По умолчанию (default)",
			"attendance"=>"Участники"
		),
		"default" => "default"
	),
	"limit" => array(
		"title" => "Колличество элементов",
		"type" => "text",
		"default" => 10
	),
	"node" => array(
		"title" => "ID раздела сайта",
		"type" => "select",
		"values" => $aNewsNodes,
		"default" => $iNewsNodeDefault
	)
));