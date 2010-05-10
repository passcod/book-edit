<?php
	if ( $_POST['getdata'] == 'true' )
	{
		echo file_get_contents('notes.txt');
		exit();
	}
	
	if ( $_POST['senddata'] == 'true' )
	{
		$written = file_put_contents('notes.txt', $_POST['data']);
		
		if ( $written === false )
		{
			echo "Failed to save";
		}
		else
		{
			echo "Saved on ".date("D, d M Y, H:i:s");
		}
		
		exit();
	}

	$self = $_SERVER['PHP_SELF'];
	//$self = 'http://passcod.webege.com/notes.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Notes</title>
		<link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico" />
		<script type="text/javascript" src="/jquery.js"></script>
		<script type="text/javascript">
			$(function() {
				$("#notes").val("Wait for content to load...");
				refreshNotes();
				
				$("#save").click(saveNotes);
				$("#refresh").click(refreshNotes);
			});
			
			function refreshNotes()
			{
				$("#state").html("Loading...");
				
				$.post('<?php echo $self; ?>', { getdata: "true" }, function(data) {
					$("#notes").val(data);
					$("#state").html("Ready");
				});
			}
			
			function saveNotes()
			{
				$("#state").html("Saving...");
				var notes = $("#notes").val();
				$.post('<?php echo $self; ?>', { senddata: "true", data: notes }, function(response) {
					$("#state").html(response);
				});
			}
		</script>
		<style type="text/css">
			body {
				background: black;
				font-family: Arial, sans-serif;
				color: white;
			}
			
			h1 {
				font-size: 1.5em;
				margin: 3px 0;
			}
			
			textarea, input {
				background: transparent;
				border: 1px white solid;
				-moz-border-radius: 10px;
				color: white;
			}
			
			textarea {
				padding: 11px;
			}
			
			#state {
				font-size: x-small;
				color: grey;
			}
			
			#refresh {
				font-size: small;
				text-decoration: none;
			}
			
			#refresh:hover {
				text-decoration: underline;
			}
		</style>
	</head>
	<body>
		<h1>Notes</h1>
		<textarea cols="70" rows="20" id="notes"></textarea>
		<p id="state"></p>
		<input type="button" id="save" value="Save" /> <a id="refresh">refresh</a>
	</body>
</html>