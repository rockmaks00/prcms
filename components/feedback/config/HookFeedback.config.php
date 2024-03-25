<?
$aNodes = Engine::GetInstance()->Node_GetNodesByComponent("feedback");
foreach ($aNodes as $i => $oNode) {
	$aFeedbackNodes[$oNode->getId()] = "id".$oNode->getId()." (".$oNode->getUrl().") ".$oNode->getTitle();
	if( !$i ) $aFeedbackNodeDefault = $oNode->getId();
}
unset($aNodes);

$this->SetConfig("feedback", "feedback", array(
	"template" => array(
		"title" => "Шаблон",
		"type" => "select",
		"values" => array(
			"default"=>"По умолчанию (default)"			
		),
		"default" => "default"
	),
	"node" => array(
		"title" => "Раздел обратной связи",
		"type" => "select",
		"values" => $aFeedbackNodes,
		"default" => $aFeedbackNodeDefault
	)
));