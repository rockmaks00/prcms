<?
$this->SetConfig("news", array(
	"template" => array(
		"title" => "Шаблон",
		"type" => "select",
		"values" => array(
			"default"=>"По умолчанию (default)",
			"another"=>"Другой шаблон"
		),
		"default" => "default"
	),
	"onpage" => array(
		"title" => "Новостей на странице",
		"type" => "select",
		"values" => array(
			"0"=>"Все",
			"5"=>"5",
			"10"=>"10",
			"20"=>"20",
			"50"=>"50",
		),
		"default" => "0"
	)
));