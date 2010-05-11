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
		
		var i = 0;
		while( i < $('#no').val() )
		{	
			$.get('randword.php', function(data) {
				$('#wordlist').append(data+' ');
			});
			i++;
		}
	}
};
		</script>

		<title>Word(s) Sources</title>
		<style type="text/css">
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
		</style>
	</head>

	<body>
		<p>Number of words: <input type="text" value="10" id="no" /></p>
		<div id="button">Gimme (about) <span id="nobis"></span> Words</div>
		<br />
		<div id="wordlist">
		</div>
	</body>
</html>
