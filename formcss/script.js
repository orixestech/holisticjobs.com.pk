$(document).ready(function(){
						   
/////////////////// Contact Form /////////////////////						   
						   
	$('#contact-form').jqTransform();
	$("button").click(function(){
		$(".formError").hide();
	});

	var use_ajax=true;
	$.validationEngine.settings={};

	$("#contact-form").validationEngine({
		inlineValidation: false,
		promptPosition: "centerRight",
		success :  function(){use_ajax=true},
		failure : function(){use_ajax=false;}
	 })

	$("#contact-form").submit(function(e){
			if(use_ajax==true){
				ajaxpage("ajaxpage.php?type=contactusform&name="+$("#name").val()+"&email="+$("#email").val()+"&subject="+$("#subject").val()+"&message="+$("#message").val(),"contectoutput","contectwaiting");
			}
	})
	
////////////////////////////////////////////////////////



////////////// Register-Form //////////////////////////
	$('#register-form').jqTransform();
	$("button").click(function(){
		$(".formError").hide();
	});

	var use_ajax=true;
	$.validationEngine.settings={};

	$("#register-form").validationEngine({
		inlineValidation: false,
		promptPosition: "centerRight",
		success :  function(){use_ajax=true},
		failure : function(){use_ajax=false;}
	 })

	$("#register-form").submit(function(e){
			if(use_ajax==true){
				ajaxpage("ajaxpage.php?type=registerform&mem_name="+$("#mem_name").val()+"&mem_email="+$("#mem_email").val()+"&mem_password="+$("#mem_password").val()+"&mem_phone="+$("#mem_phone").val(),"contectoutput","contectwaiting");
			}
	})
////////////////////////////////////////////////////////


/////////////////// Login Form /////////////////////						   
						   
	$('#login-form').jqTransform();
	$("button").click(function(){
		$(".formError").hide();
	});

	var use_ajax=true;
	$.validationEngine.settings={};

	$("#login-form").validationEngine({
		inlineValidation: false,
		promptPosition: "centerRight",
		success :  function(){use_ajax=true},
		failure : function(){use_ajax=false;}
	 })

	$("#login-form").submit(function(e){
			if(use_ajax==true){
				//alert("Login Page");
				ajaxpage("ajaxpage.php?type=loginform&mem_email="+$("#mem_email").val()+"&mem_password="+$("#mem_password").val(),"LOGINoutput","LOGINwaiting");
			}
	})
	
////////////////////////////////////////////////////////


////////////// Edit My Profile  //////////////////////////
	$('#myprofile-form').jqTransform();
	$("button").click(function(){
		$(".formError").hide();
	});

	var use_ajax=true;
	$.validationEngine.settings={};

	$("#myprofile-form").validationEngine({
		inlineValidation: false,
		promptPosition: "centerRight",
		success :  function(){use_ajax=true},
		failure : function(){use_ajax=false;}
	 })

	$("#myprofile-form").submit(function(e){
			if(use_ajax==true){
				ajaxpage( "ajaxpage.php?type=myprofileform&mem_name="+$("#mem_name").val()+"&mem_password="+$("#mem_password").val()+"&mem_phone="+$("#mem_phone").val(), "contectoutput", "myprofilewaiting");
			}
	})
////////////////////////////////////////////////////////










});