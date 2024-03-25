<!DOCTYPE html>
<html lang="ru">
<head>
	<title>{$aTemplate.title}</title>
	{literal}
	<!--[if IE]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	{/literal}
	<meta charset="UTF-8">
	{$aTemplate.meta}
	<link rel='StyleSheet' href='/components/admin/templates/default/assets/fonts/font.css' type='text/css'>
	<link rel='StyleSheet' href='{$aTemplate.path}css/bootstrap-responsive.css' type='text/css'>
	<link href="//bootstrap-ru.com/assets/css/bootstrap.css" rel="stylesheet">
	<link rel='StyleSheet' href='{$aTemplate.path}css/styles.css' type='text/css'>
	==css==
	
	<script type="text/javascript" src="http://www.google.ru/jsapi"></script>
	<script type="text/javascript">
		google.load("jquery", "1");
	</script>
	==js==
	<link href="/favicon.ico" rel="shortcut icon" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="wrapper">
	<header>
		<h2>Шапка</h2>
	</header>
	<section id="body" class="type">
		<h1>{$aTemplate.page_title}</h1>
	
	