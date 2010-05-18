<html>
	<head>
		<script type="text/javascript" src="http://lib/?_=jquery.js"></script>		
		<script type="text/javascript">
$(function() {
	$('#button').click(buttClick);
	
	$('#no').keyup(transNos);
	transNos();
});

var transNos = function() {
	$('#nobis').html( $('#no').val() );
};

var buttClick = function() {
	if ( $('#no').val() != 0 )
	{
		$('#wordlist').html('');
		$('#counter').html('0');
		$('#wordcount').html('0');
		
		var i = 0;
		while( i < $('#no').val() )
		{	
			$.get('randword.php', function(data) {
				$('#wordlist').append('<span>'+data+'</span> ');
				$('#counter').html(Number($('#counter').html())+1);
				$('#wordcount').html($.trim($('#wordlist').text()).split(' ').length);
			});
			i++;
		}
	}
};
		</script>

		<title>Word(s) Source</title>
		<style type="text/css">
@font-face {
  font-family: Tagesschrift; /* from Yanone, CC-BY */
  src: url(YanoneTagesschrift.ttf);
}

body {
	font-family: Tagesschrift, serif;
}

#button {
	-moz-border-radius: 15px;
	border: 2px black solid;
	width: 15em;
	padding: 10px;
	text-align: center;
}

#button:hover {
	border-color: #222;
	background-color: #EEE;
}

#no {
	background: transparent;
	border: 1px grey solid;
	-moz-border-radius: 5px;
	width: 2em;
	text-align: center;
}
#nobis {
	font-weight: bold;
}

#wordlist {
	padding: 30px;
	border: 5px #efefef solid;
	-moz-border-radius: 5px;
	width: 40em;
	font-size: 1.3em;
	font-weight: bold;
	word-spacing: 25px;
	line-height: 3;
}
#wordlist > span:hover, #wordlist > span a:hover {
	color: grey;
	cursor: normal;
}

a, a:hover { color: black; text-decoration: none; }
		</style>
	</head>

	<body>
		<p>Number of words: <input type="text" value="10" id="no" /></p>
		<div id="button">Gimme (about) <span id="nobis"></span> Words</div>
		<br />
		<div id="wordlist">
		</div>
		<br />
		<p>Fetched <span id="counter">0</span> entries. So far <span id="wordcount">0</span> words.</p>
<?php if  ( !in_array  ('curl', get_loaded_extensions()) ) { ?>
		<p style="color: grey; font-size: x-small;"><i>This server does not have cURL, so you will have to wait longer to get the words. Unfortunately, you probably can't do anything about it.</i></p>
<?php } ?>
	</body>
</html>
