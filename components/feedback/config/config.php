<?
$this->SetConfig("feedback", array(
	"template" => array(
		"title" => "Шаблон",
		"type" => "select",
		"values" => array(
			"default"=>"По умолчанию (default)"
		),
		"default" => "default"
	),
	"show_results" => array(
		"title" => "Режим гостевой",
		"type" => "checkbox",
		"default" => 0
	),
	"success" => array(
		"title" => "Сообщение об успешной доставке",
		"type" => "text",
		"default" => "Заявка принята, Спасибо за ваше обращение"
	),
	"error" => array(
		"title" => "Сообщение об ошибке при доставке",
		"type" => "text",
		"default" => "К сожалению, по ниезвестным причинам отправить вашу заявку не удалось.. Попробуйте снова!"
	)
));