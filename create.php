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
	    background-color:yellow;
	}
	</style>
	<script src="./underscore.js"></script>
	<script src="./XORCipher.js"></script>
	<script src="./jquery.js"></script>
	<script type="text/javascript">
	
	function randomString(L){
    var s= '';
    var randomchar=function(){
    	var n= Math.floor(Math.random()*62);
    	if(n<10) return n; //1-10
    	if(n<36) return String.fromCharCode(n+55); //A-Z
    	return String.fromCharCode(n+61); //a-z
    }
    while(s.length< L) s+= randomchar();
    return s;
}
	
	function encryptAndPost() {		
		document.getElementById("hidden_secret").style.display = 'none';
		var rnds = randomString(32);
		client_text_msg = document.getElementById("client_text").value;		
		client_text_msg = client_text_msg.replace(/(?:\r\n|\r|\n)/g, '<br>');
		encrypted_msg = XORCipher.encode(rnds, client_text_msg);
		document.getElementById("keyholder").innerHTML=rnds;
		$.post( "register.php", { msg:  encrypted_msg} ).done(function( data ) {
			msg_id = data.response.id;
			if (!window.location.origin)
     			window.location.origin = window.location.protocol+"//"+window.location.host;
     		sl = window.location.origin
     					+"/safemsg/see.php?id="+msg_id;
     		document.getElementById("secret_link").innerHTML = "<a href=\""+sl+"\">"+sl+"</a>";     		
     		document.getElementById("hidden_secret").style.display = 'block';
		});;
	}		

	function destroy_message() {
		$.get(window.location.origin +"/safemsg/retrieve.php?id="+msg_id);
		document.getElementById("destroy_message").innerHTML = "<p><b>DESTROYED!</b><br/>You may now submit a new message.</p>"
		document.getElementById("secret_details").style.display = 'none';
		msg_id = null;
	}
	</script>
</head>

<body>
	<div class="center">
		<b>Create SafeMessage</b>
		<p>
		Provide your message in the text-box below
		and click the create button to create a safe
		message</p>
		<div id="form_div">			
			<textarea id="client_text" rows="6" cols="30">Your secret message goes here...</textarea>
			<br/><br/>
			<input type="submit" value="Submit" onclick="encryptAndPost();"> 						
		</div>
		<div style="display: none;" id="hidden_secret">
			<div id="secret_details">
				<p>
					<b>Link:</b>
					<span id="secret_link"></span>		
				</p>
				<p>
					<b>Key:</b> <span id="keyholder">Click the above button!</span>
				</p>
			</div>
			<div id="destroy_message">
				<p>...or <a href="javascript:void(0)" onclick="destroy_message();"><b>DESTROY</b></a> it now!</p>
			</div>
		</div>
	</div>
</body>
</html>