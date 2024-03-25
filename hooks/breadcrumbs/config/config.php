<?
$this->SetConfig("breadcrumbs", null, array(
	"template" => array(
		"title" => "Шаблон",
		"type" => "select",
		"values" => array(
			"default"=>"По умолчанию (default)"
		),
		"default" => "default"
	),
	"showroot" => array(
		"title" => "Показывать главную",
		"type" => "checkbox",
		"default" => 1
	),
));