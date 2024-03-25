$(document).ready(function(){
	var points = []
		ctx = document.getElementById('canv').getContext('2d'),
		div = $("div.com_pattern_default"),
		map = $("map", div),
		areas = $("area", map),

		drawArea = function(ctx, points, color){
			if( !color ) color = "def";
			ctx.moveTo(points[0]['x'],points[0]['y']);
			ctx.beginPath();
			for(var i in points){
				if( i ) ctx.lineTo(points[i]['x'],points[i]['y']);
			}
			ctx.fillStyle = style[color];
			ctx.fill();
		},
		drawMap = function(ctx, points, exception){
			ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
			for(var i in points){
				var color = ( i == exception ? "hov" : "def" );
				drawArea(ctx, points[i], color);
			}
		},

		style = {};
		style.act = "rgba(255, 100, 100, 0.5)";
		style.hov = "rgba(100, 255, 100, 0.5)";
		style.def = "rgba(100, 100, 255, 0.5)";

	for(var i in coords){
		points[i] = [];
		for (var m in coords[i]) {
			var dir = m%2 ? "y" : "x",
				key = Math.floor( m/2 );
			if( !points[i][key] ) points[i][key] = [];
			points[i][key][dir] = coords[i][m];
		}
	}

	areas.hover(function(){
				var index = $(this).index();
				drawMap(ctx, points, index);
			}, function(){
				drawMap(ctx, points);
	})
	.click(function(){return false;})
	.each(function(){
		$(this).qtip({
			content: {
				title: $(this).data("title"),
				text: $(this).data("desc")
			},
				position: {
				target: "mouse",
				adjust: {x:10,y:10},
			}
		});
	})


	drawMap(ctx, points);
})