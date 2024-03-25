<?
$this->SetConfig("catalog", array(
	"template" => array(
		"title" => "Шаблон",
		"type" => "select",
		"values" => array(
			"default"=>"По умолчанию (default)"
		),
		"default" => "default"
	),
	"im" => array(
		"title" => "Интернет-магазин",
		"type" => "select",
		"values" => array(
			"Y"=>"По умолчанию (Да)",
			"N"=>"Нет"
		),
		"default" => "Y"
	)
));