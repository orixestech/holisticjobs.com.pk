function ajaxreq(phpurl, phpdata, divid){
	//alert(phpurl+phpdata);
	$.ajax({
	  cache: false, 
	  type: "POST",
	  url: phpurl,
	  beforeSend: function(){
			$("#"+divid).html('Loading....');
			$("#"+divid).fadeIn('fast');
	  },
	  dataType: "html",
	  data:phpdata,
	  success: function(data){
		$("#"+divid).html(data);
		$("#"+divid).fadeIn('slow');
		if($("#"+divid+' #reload').val()==1){
			setTimeout("window.location = location.href;", 2200);
		}
		
		(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=613462515432251&version=v2.0";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
		
	  },error: function(){
		alert('Error Loading AJAX Request...!');
	  }
	});
}


function SlimScroll(selector){
	$(selector).slimScroll({
		height: $(selector).data('height') || 100,
		railVisible:true
	});
}


function SendLoginDetails(type, uid, result){
	if(confirm("Do you want to send password to this "+type+".?")){
		ajaxreq('ajaxpage.php', 'action=SendLoginDetails&type='+type+'&uid='+uid, result);
	} else {
		return false;			
	}
}