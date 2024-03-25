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
	<nav><h2>Меню</h2>
		{hook hookgroup="nav"}
	</nav>
	<section>
		{hook hookgroup="top-content"}
	</section>
	{if $oCurrentNode->getUrl()=="root"}
	<section id="teaser">
		<h1>Текст главной страницы</h1>
		<div class="row-fluid">
			<div class="span12">
	             <h3>На всю ширину</h3>
	             <p>Dantooine. They're on Dantooine. I have traced the Rebel spies to her. Now she is my only link to finding their secret base. The plans you refer to will soon be back in our hands.</p>
	<p>Alderaan? I'm not going to Alderaan. I've got to go home. It's late, I'm in for it as it is. I find your lack of faith disturbing. Kid, I've flown from one side of this galaxy to the other. I've seen a lot of strange stuff, but I've never seen anything to make me believe there's one all-powerful Force controlling everything. There's no mystical energy field that controls my destiny. It's all a lot of simple tricks and nonsense. Your eyes can deceive you. Don't trust them. Dantooine. They're on Dantooine.</p>
	             
	        </div>
		</div>
		<div class="row-fluid">
			<div class="span6">
	             <h3>На пол страницы</h3>
	             <p>Dantooine. They're on Dantooine. I have traced the Rebel spies to her. Now she is my only link to finding their secret base. The plans you refer to will soon be back in our hands.</p>
	<p>Alderaan? I'm not going to Alderaan. I've got to go home. It's late, I'm in for it as it is. I find your lack of faith disturbing. Kid, I've flown from one side of this galaxy to the other. I've seen a lot of strange stuff, but I've never seen anything to make me believe there's one all-powerful Force controlling everything. There's no mystical energy field that controls my destiny. It's all a lot of simple tricks and nonsense. Your eyes can deceive you. Don't trust them. Dantooine. They're on Dantooine.</p>
	             
	        </div>
	        <div class="span6">
	             <h3>На пол страницы</h3>
	             <p>Dantooine. They're on Dantooine. I have traced the Rebel spies to her. Now she is my only link to finding their secret base. The plans you refer to will soon be back in our hands.</p>
	<p>Alderaan? I'm not going to Alderaan. I've got to go home. It's late, I'm in for it as it is. I find your lack of faith disturbing. Kid, I've flown from one side of this galaxy to the other. I've seen a lot of strange stuff, but I've never seen anything to make me believe there's one all-powerful Force controlling everything. There's no mystical energy field that controls my destiny. It's all a lot of simple tricks and nonsense. Your eyes can deceive you. Don't trust them. Dantooine. They're on Dantooine.</p>
	             
	        </div>
		</div>
		<div class="row-fluid">
			<div class="span4">
	             <h3>На треть страницы</h3>
	             <p>Dantooine. They're on Dantooine. I have traced the Rebel spies to her. Now she is my only link to finding their secret base. The plans you refer to will soon be back in our hands.</p>
	<p>Alderaan? I'm not going to Alderaan. I've got to go home. It's late, I'm in for it as it is. I find your lack of faith disturbing. Kid, I've flown from one side of this galaxy to the other. I've seen a lot of strange stuff, but I've never seen anything to make me believe there's one all-powerful Force controlling everything. There's no mystical energy field that controls my destiny. It's all a lot of simple tricks and nonsense. Your eyes can deceive you. Don't trust them. Dantooine. They're on Dantooine.</p>
	             
	        </div>
	        <div class="span4">
	             <h3>На треть страницы</h3>
	             <p>Dantooine. They're on Dantooine. I have traced the Rebel spies to her. Now she is my only link to finding their secret base. The plans you refer to will soon be back in our hands.</p>
	<p>Alderaan? I'm not going to Alderaan. I've got to go home. It's late, I'm in for it as it is. I find your lack of faith disturbing. Kid, I've flown from one side of this galaxy to the other. I've seen a lot of strange stuff, but I've never seen anything to make me believe there's one all-powerful Force controlling everything. There's no mystical energy field that controls my destiny. It's all a lot of simple tricks and nonsense. Your eyes can deceive you. Don't trust them. Dantooine. They're on Dantooine.</p>
	             
	        </div>
	        <div class="span4">
	             <h3>На треть страницы</h3>
	             <p>Dantooine. They're on Dantooine. I have traced the Rebel spies to her. Now she is my only link to finding their secret base. The plans you refer to will soon be back in our hands.</p>
	<p>Alderaan? I'm not going to Alderaan. I've got to go home. It's late, I'm in for it as it is. I find your lack of faith disturbing. Kid, I've flown from one side of this galaxy to the other. I've seen a lot of strange stuff, but I've never seen anything to make me believe there's one all-powerful Force controlling everything. There's no mystical energy field that controls my destiny. It's all a lot of simple tricks and nonsense. Your eyes can deceive you. Don't trust them. Dantooine. They're on Dantooine.</p>
	             
	        </div>
		</div>
		<div class="row-fluid">
			<div class="span6">
	             <h1>This is an H1 header</h1>
	             <h2>This is an H2 header</h2>
	             <h3>This is an H3 header</h3>
	             <h4>This is an H4 header</h4>
	             <h5>This is an H5 header</h5>
	             <h6>This is an H6 header</h6>
	        </div>
	        <div class="span6">
	        	 <h3>Ненумерованый список</h3>
	        	 <ul>
                                    
                    <li>List item one</li>
                    <li>List item two</li>
                    <li>List item three
                    
                        <ul>
                            
                            <li><a href="#">List item four</a></li>
                            <li>List item five</li>
                            <li>List item six</li>
                            
                        </ul>
                    
                    </li>
                    <li>List item seven</li>
                    
                 </ul>
                 <h3>Нумерованный список</h3>
	             <ol>
                                    
                    <li>List item one</li>
                    <li>List item two</li>
                    <li>List item three
                    
                        <ol>
                            
                            <li><a href="#">List item four</a></li>
                            <li>List item five</li>
                            <li>List item six</li>
                            
                        </ol>
                    
                    </li>
                    <li>List item seven</li>
                    
                </ol>
	        </div>
		</div>
	</section>
<div class="getimagetest">
	<div class="row-fluid">
		<div class="span3">
			50*100 crop
		</div>
		<div class="span3">
			50*100 adapt
		</div>
		<div class="span3">
			100*50 crop
		</div>
		<div class="span3">
			100*50 adapt
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid200-200.png", 50, 100, 1)}" /><br>Было 200-200 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid200-200.png", 50, 100, 0)}" /><br>Было 200-200 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid200-200.png", 100, 50, 1)}" /><br>Было 200-200 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid200-200.png", 100, 50, 0)}" /><br>Было 200-200 <br>Получили 
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid100-200.jpg", 50, 100, 1)}" /><br>Было 100-200 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid100-200.jpg", 50, 100, 0)}" /><br>Было 100-200 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid100-200.jpg", 100, 50, 1)}" /><br>Было 100-200 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid100-200.jpg", 100, 50, 0)}" /><br>Было 100-200 <br>Получили 
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid50-200.jpg", 50, 100, 1)}" /><br>Было 50-200 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid50-200.jpg", 50, 100, 0)}" /><br>Было 50-200 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid50-200.jpg", 100, 50, 1)}" /><br>Было 50-200 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid50-200.jpg", 100, 50, 0)}" /><br>Было 50-200 <br>Получили 
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid25-200.jpg", 50, 100, 1)}" /><br>Было 25-200 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid25-200.jpg", 50, 100, 0)}" /><br>Было 25-200 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid25-200.jpg", 100, 50, 1)}" /><br>Было 25-200 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid25-200.jpg", 100, 50, 0)}" /><br>Было 25-200 <br>Получили 
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid200-100.jpg", 50, 100, 1)}" /><br>Было 200-100 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid200-100.jpg", 50, 100, 0)}" /><br>Было 200-100 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid200-100.jpg", 100, 50, 1)}" /><br>Было 200-100 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid200-100.jpg", 100, 50, 0)}" /><br>Было 200-100 <br>Получили 
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid100-100.jpg", 50, 100, 1)}" /><br>Было 100-100 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid100-100.jpg", 50, 100, 0)}" /><br>Было 100-100 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid100-100.jpg", 100, 50, 1)}" /><br>Было 100-100 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid100-100.jpg", 100, 50, 0)}" /><br>Было 100-100 <br>Получили 
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid50-100.jpg", 50, 100, 1)}" /><br>Было 50-100 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid50-100.jpg", 50, 100, 0)}" /><br>Было 50-100 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid50-100.jpg", 100, 50, 1)}" /><br>Было 50-100 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid50-100.jpg", 100, 50, 0)}" /><br>Было 50-100 <br>Получили 
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid25-100.jpg", 50, 100, 1)}" /><br>Было 25-100 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid25-100.jpg", 50, 100, 0)}" /><br>Было 25-100 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid25-100.jpg", 100, 50, 1)}" /><br>Было 25-100 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid25-100.jpg", 100, 50, 0)}" /><br>Было 25-100 <br>Получили 
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid200-50.jpg", 50, 100, 1)}" /><br>Было 200-50 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid200-50.jpg", 50, 100, 0)}" /><br>Было 200-50 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid200-50.jpg", 100, 50, 1)}" /><br>Было 200-50 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid200-50.jpg", 100, 50, 0)}" /><br>Было 200-50 <br>Получили 
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid100-50.jpg", 50, 100, 1)}" /><br>Было 100-50 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid100-50.jpg", 50, 100, 0)}" /><br>Было 100-50 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid100-50.jpg", 100, 50, 1)}" /><br>Было 100-50 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid100-50.jpg", 100, 50, 0)}" /><br>Было 100-50 <br>Получили 
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid50-50.jpg", 50, 100, 1)}" /><br>Было 50-50 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid50-50.jpg", 50, 100, 0)}" /><br>Было 50-50 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid50-50.jpg", 100, 50, 1)}" /><br>Было 50-50 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid50-50.jpg", 100, 50, 0)}" /><br>Было 50-50 <br>Получили 
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid25-50.jpg", 50, 100, 1)}" /><br>Было 25-50 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid25-50.jpg", 50, 100, 0)}" /><br>Было 25-50 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid25-50.jpg", 100, 50, 1)}" /><br>Было 25-50 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid25-50.jpg", 100, 50, 0)}" /><br>Было 25-50 <br>Получили 
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid200-25.jpg", 50, 100, 1)}" /><br>Было 200-25 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid200-25.jpg", 50, 100, 0)}" /><br>Было 200-25 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid200-25.jpg", 100, 50, 1)}" /><br>Было 200-25 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid200-25.jpg", 100, 50, 0)}" /><br>Было 200-25 <br>Получили 
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid100-25.jpg", 50, 100, 1)}" /><br>Было 100-25 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid100-25.jpg", 50, 100, 0)}" /><br>Было 100-25 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid100-25.jpg", 100, 50, 1)}" /><br>Было 100-25 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid100-25.jpg", 100, 50, 0)}" /><br>Было 100-25 <br>Получили 
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid50-25.jpg", 50, 100, 1)}" /><br>Было 50-25 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid50-25.jpg", 50, 100, 0)}" /><br>Было 50-25 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid50-25.jpg", 100, 50, 1)}" /><br>Было 50-25 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid50-25.jpg", 100, 50, 0)}" /><br>Было 50-25 <br>Получили 
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid25-25.jpg", 50, 100, 1)}" /><br>Было 25-25 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid25-25.jpg", 50, 100, 0)}" /><br>Было 25-25 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid25-25.jpg", 100, 50, 1)}" /><br>Было 25-25 <br>Получили 
		</div>
		<div class="span3">
			<img src="{ModuleImage::AdaptImage("/grid/grid25-25.jpg", 100, 50, 0)}" /><br>Было 25-25 <br>Получили 
		</div>
	</div>
</div>
<script>
	$(function(){
		$(".getimagetest").find("img").each(function(){
			$(this).load(function(){
				var w = $(this).width(),
					h = $(this).height();
				$(this).parent().css("padding-bottom", 50).append(w+'*'+h);
			})
		})
	})
</script>

	{else}
	<section id="body" class="type">
		<h1>{$aTemplate.page_title}</h1>
	{/if}