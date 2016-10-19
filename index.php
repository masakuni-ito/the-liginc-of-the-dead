<?php
require_once __DIR__ . '/vendor/autoload.php';

$members = array();
$client = new Goutte\Client();
$crawler = $client->request('GET', 'https://liginc.co.jp/member');
$crawler->filter('article.author_member_item')->each(function($node) use (&$members) {
	if ($node->filter('img.author_member_item--photo1')->count() && $node->filter('span.author_member_item--name')->count()) {
		$tmp = array();
		$tmp['url'] = $node->filter('img.author_member_item--photo1')->attr('data-original');
		$tmp['name'] = $node->filter('span.author_member_item--name')->text();
		$members[] = $tmp;
	}
});

?><!DOCTYPE html>
<html lang="ja">
<head>
<style>
body {
	margin: 0px;
	padding: 0px;
	text-align: center;
	color: #6A3386;
}
img { position: fixed; }
#header {
	height: 80px;
	font-size: 25px;
	background-color: #EB6100;
}

</style>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
var zombies = <?php echo json_encode($members); ?>;
var strongers = ['ゴウ', 'たか', 'づや', '龍﨑 コウ', '先生']
var messiah = ['俺'];
var rump = zombies.length;
var shot = [];
var score = 0;
var count = 3;

$(function() {
	$('#indicator').text('Click and defeat LIG members!!');
	setTimeout(onYourMark, 5000);
});

function onYourMark() {
	$('#indicator').text(count);
	count--;
	if (count >= 0) {
		setTimeout(onYourMark, 1000);
	} else {
		start();
	}
}

function start() {
	$('#indicator').text('Go!!');

	$.each(zombies, function() {
		var delayTime = Math.floor( Math.random() * 20000 );
		var imgNode = $("<img>")
			.attr("src", this.url)
			.css('top', 100 + Math.floor(Math.random() * 600))
			.css('left', Math.floor(Math.random() * ($(window).width() - 50)))
			.css('width', '50px')
			.css('height', '50px')
			.hide()
			.delay(delayTime).fadeIn('fast', function() {
				$(this).delay(1000).fadeOut(2000, function() {
					$(this).remove();
					if (--rump <= 0) {
						shot.push('');
						$('#indicator').text('');
						$('body').animate({ 'background-color': '#E5A83F' }, 'slow');
						$('#content').append($('<div>').css('margin-bottom', '20px').css('font-size', '40px').html('SCORE<br />' + score + "点"));
						$('#content').append($('<div>').html(shot.join('を倒した<br />')));
					}
				});
			})
			.on('click', {name: this.name }, shoot);

		$('#content').append(imgNode);
	});
}

function shoot(event) {

	var name = event.data.name;
	shot.push(name);

	$('body').queue([]);
	if (strongers.indexOf(name) >= 0) {
		$('body').effect('shake', {direction: "up", times: 5, distance: 40}, 100);
		score -= 500000000;
	} else if (messiah.indexOf(name) >= 0) {
		$('body').effect('shake', {direction: "up", times: 2, distance: 5}, 300);
		$('#header').css('background-color', '#EBCA1B');
		score += 10000000000;
	} else {
		$('body').effect('shake', {direction: "up", times: 2, distance: 5}, 300);
		score++;
	}

	$(event.currentTarget).hide();
	$('#indicator').text('SCORE: ' + score);
}

</script>
<title>THE LIG.INC OF THE DEAD</title>
</head>
<body>
<div id="header">
	<div>THE LIG.INC OF THE DEAD</div>
	<div id="indicator"></div>
</div>
<div id="content"></div>
</body>
</html>
