<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<link href="favicon.png" rel="shortcut icon" />
		<script type="text/javascript" src="http://lib/?_=jquery.js"></script>
		<script type="text/javascript">
			$(function() {
				$('#editor').hide();
				
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
					$.getScript('showdown.js', function() {
						$('#filelist a').click(editMe);
					});
				});
				
				$('#back').click(function() {
					$('#editor').hide();
					$('#refstat').text('Ready');
					$('#menu').show();
				});
				
				$('#save').click(saveMe);
			});
			
			function editMe() {
				var mark = new Showdown.converter();
				var file = $(this).text();
				$('#editor').data('file', file);
				$.get('file.php?g=raw&f='+file, function(data) {
					$('#menu').hide();
					$('#edittext').height( $(window).height() * 0.8 ).val(data);
					$('#edittext').keyup(viewMe);
					$('#editview').html( mark.makeHtml(data) );
					$('#status').text('Ready');
					$('#editor').show();
				});
			}
			
			function saveMe() {
				var file = $('#editor').data('file');
				$.post( 'file.php?g=save&f='+file,
					{ data: $('#edittext').val() },
					function(data) {
						$('#status').text('Written '+data+' bytes.');
					}
				);
			}
			
			function viewMe() {
				var mark = new Showdown.converter();
				var md = $('#edittext').val();
				$('#editview').html( mark.makeHtml(md) );
				checkLines();
			}
			
			function checkLines() {
				var md = $('#edittext').val(); 
				var lines = md.split("\n");
				var over80 = '';
				
				i = 0;
				for( i in lines ) {
					if ( lines[i].length > 79 ) {
						over80 += i+', ';
					}
				}
				
				if ( over80 != '' ) {
					$('#lines').text('These lines are over 80 chars: '+over80);
				}
				else
				{
					$('#lines').text('');
				}
			}
		</script>
		<style type="text/css">
			@import url(thebook/page.css);
			
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
				float: right;
			}
			
			#date {
				text-align: center;
			}
			
			#lines {
				font-size: small;
				color: #b00;
			}
		</style>

		<title>Editor</title>
	</head>

	<body>
		<div id="container">
			<div id="date"><?php echo date('y.W.N'); ?></div>
			<div id="menu">
				<p>Files:</p>
				<ul id="filelist"></ul>
				<a id="refresh">Refresh passcod.local's copy</a>
				<p id="refstat">Ready</p>
			</div>
			<div id="editor">
				<p><span id="status">Ready</span>
				<a id="back" style="float: right;">Back</a></p>
				<textarea id="edittext"></textarea>
				<br />
				<p><span id="lines"></span><button id="save">Save</button></p>
				<hr />
				<br />
				<div id="editview"></div>
			</div>
		</div>
	</body>
</html>
