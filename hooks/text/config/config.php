<?
$this->SetConfig("text", null, array(
	"template" => array(
		"title" => "Шаблон",
		"type" => "select",
		"values" => array(
			"default"=>"По умолчанию (default)"
		),
		"default" => "default"
	),
	"text" => array(
		"title" => "Текст",
		"type" => "editor",
		"default"=> ""
	),
	"class" => array(
		"title" => "Класс стилей",
		"type" => "text",
		"default"=> ""
	)
));