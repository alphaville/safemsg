<html>
<head>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<meta content="utf-8" http-equiv="encoding">
	<style type="text/css">
	.center{
	    position:absolute;
	    display:block;
	    height:400px;
	    width:400px;
	    left:50%;
	    top:50%;
	    margin-top:-200px;
	    margin-left:-200px;
	    text-align: center;
	    background-color:orange;
	}
	</style>
	<script src="./jquery.js"></script>
	<script src="./underscore.js"></script>
	<script src="./XORCipher.js"></script>
	<script type="text/javascript">
		var msg_id = getParameterByName("id");		
		$.get("./retrieve.php?id="+msg_id).done(function( data ) {
			document.getElementById("found").style.display = 'block';
			document.getElementById("notfound").style.display = 'none';
			enc_msg = data.response.msg;
		}).fail(function( data ) {
			document.getElementById("notfound").style.display = 'block';
			document.getElementById("found").style.display = 'none';
		});
		function getParameterByName(name) {
		    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
		    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		        results = regex.exec(location.search);
		    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
		}
		function decryptAndDisplay(){
			key = document.getElementById("client_key").value;
			alert(XORCipher.decode(key, enc_msg));
			key = null;
			enc_msg = null;
		}
	</script>
</head>
<body>
	<div class="center">
		<b>Read SafeMessage</b>	
		<br/><br/>
		<div style="display: none;" id="notfound">
			Message not found!!!
		</div>
		<div style="display: none;" id="found">
			<p>Provide your key to decipher the message:</p>
			<div id="form_div">
				<input type="text" id="client_key" value="key"></input><br/>	
				<input type="submit" value="Submit" onclick="decryptAndDisplay();">
			</div>
		</div>	
		<div id="goback">
			<p>Or <a href="/safemsg/create.php"><b>create</b></a> a new message!</p>
		</div>	
		
	</div>
</body>
</html>