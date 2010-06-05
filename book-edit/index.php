<html>
	<head>
		<script type="text/javascript" src="http://lib/?_=jquery.js"></script>
		<script type="text/javascript">
			$(function() {
				$('#nojs').hide();
				$('#menu').show();
				
				$('#refresh').click(function() {
					$.get('thebook/refresh.php', function(d) {
						$('#refstat').html('Written '+d+' bytes');
					});
				});
				
				$.get('file.php?g=list', function(data) {
					var files = data.split(';');
					for( i in files ) {
						$('#filelist').append('<li><a>'+files[i]+'</a></li>');
					}
					$('#filelist a').click(editMe);
				});
				
				$('#back').click(function() {
					$('#editor').hide();
					$('#menu').show();
				});
				
				$('#save').click(saveMe);
			});
			
			function editMe() {
				var file = $(this).text();
				$('#editor').data('file', file);
				$.get('file.php?g=raw&f='+file, function(data) {
					$('#menu').hide();
					$('#edittext').height( $(window).height() * 0.8 ).html(data);
					$.get('file.php?g=htm&f='+file, function(data) {
						$('#editview').html(data);
					});
					$('#editor').show();
				});
			}
			
			function saveMe() {
				var file = $('#editor').data('file');
				$.post( 'file.php?g=save&f='+file,
					{ data: $('#edittext').val() },
					function(data) {
						$('#status').text('Written '+data+' bytes.');
						$.get('file.php?g=htm&f='+file, function(data) {
							$('#editview').html(data);
						});
					}
				);
			}
		</script>
		<style type="text/css">
			@import url(thebook/page.css);
		
			#menu, #editor {
				display: none;
			}
			
			#filelist {
				list-style-type: none;
			}
			
			#refstat {
				color: grey;
				font-size: small;
			}
			
			#edittext {
				width: 500px;
				font-size: small;
				font-family: monospace;
			}
			
			#back {
				float: right;
			}
			
			#status {
				color: grey;
			}
			
			#save {
				background-color: transparent;
				border: 1px black solid;
				border-radius: 5px;
				-moz-border-radius: 5px;
				-webkit-border-radius: 5px;
				padding: 5px;
			}
		</style>

		<title>Editor</title>
	</head>

	<body>
		<div id="container">
			<div id="nojs">You need javascript.</div>
			<div id="menu">
				<p>Files:</p>
				<ul id="filelist"></ul>
				<a id="refresh">Refresh passcod.local's copy</a>
				<p id="refstat">Ready</p>
			</div>
			<div id="editor">
				<p><span id="status">Ready</span><a id="back" style="float: right;">Back</a></p>
				<textarea id="edittext"></textarea>
				<br />
				<p><button id="save">Save & Preview</button></p>
				<hr />
				<br />
				<div id="editview"></div>
			</div>
		</div>
	</body>
</html>
