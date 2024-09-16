var entityMap = {
	"&": "&amp;",
	"<": "&lt;",
	">": "&gt;",
	'"': '&quot;',
	"'": '&#39;',
	"/": '&#x2F;'
};

function escapeHtml(string) {
	return String(string).replace(/[&<>"'\/]/g, function (s) {
	  return entityMap[s];
	});
}


function LoadEmployeeEducationForm(uid, result){
	ajaxreq('ajaxpage.php', 'action=LoadEmployeeEducationForm&uid='+uid, result);
	setTimeout(function(){ LoadScripts(); LoadEmployeeEducationData('Education #EducationHistoryData');}, 1000);
	
}

function LoadEmployeeEducationData(result){
	ajaxreq('ajaxpage.php', 'action=LoadEmployeeEducationData&', result);
	
}

function ProcessEducationForm(){
	FormType = $('#EducationHistorySection #EducationFormType').val();
	FormUID = $('#EducationHistorySection #EducationFormUID').val();
	
	Institute = $('#EducationHistorySection #EducationInstitute').val();
	if(Institute==''){
		$('#EducationHistorySection #EducationInstitute').addClass('input-error');
		$('#EducationHistorySection #EducationInstitute').focus();
		return false;
	} else {
		$('#EducationHistorySection #EducationInstitute').removeClass('input-error');
	}

	Qualification = $('#EducationHistorySection #EducationQualification').val();
	if(Qualification==''){
		$('#EducationHistorySection #EducationQualification').addClass('input-error');
		$('#EducationHistorySection #EducationQualification').focus();
		return false;
	} else {
		$('#EducationHistorySection #EducationQualification').removeClass('input-error');
	}
	
	OtherQualification = $('#EducationHistorySection #OtherQualification').val();
	if(Qualification=='other' && OtherQualification==''){
		$('#EducationHistorySection #OtherQualification').addClass('input-error');
		$('#EducationHistorySection #OtherQualification').focus();
		return false;
	} else {
		$('#EducationHistorySection #OtherQualification').removeClass('input-error');
	}

	EducationFrom = $('#EducationHistorySection #EducationFrom').val();
	/*if(EducationFrom==''){
		$('#EducationHistorySection #EducationFrom').addClass('input-error');
		$('#EducationHistorySection #EducationFrom').focus();
		return false;
	} else {
		$('#EducationHistorySection #EducationFrom').removeClass('input-error');
	}*/

	EducationTo = $('#EducationHistorySection #EducationTo').val();
	/*if(EducationTo==''){
		$('#EducationHistorySection #EducationTo').addClass('input-error');
		$('#EducationHistorySection #EducationTo').focus();
		return false;
	} else {
		$('#EducationHistorySection #EducationTo').removeClass('input-error');
	}*/

	QuerySting = '&FormType='+FormType+'&Institute='+Institute+'&Qualification='+Qualification+'&OtherQualification='+OtherQualification+'&EducationFrom='+EducationFrom+'&EducationTo='+EducationTo+'';
	
	QuerySting = $('#EmployeeProfile_Education').serialize();
	ajaxreq('ajaxpage.php', 'action=EmployeeEducationSubmit&uid='+FormUID+"&FormType="+FormType+"&"+QuerySting, 'EducationHistorySection #AjaxResult');
	
	setTimeout(function(){ 
		$("#Education #EducationHistorySection").html(''); 
		LoadEmployeeEducationData('Education #EducationHistoryData'); 
		LoadEmployeeEducationForm(0, 'EducationHistorySection');
	}, 1500);
	
}


/* xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx */
function LoadEmployeeExperienceForm(uid, result){
	ajaxreq('ajaxpage.php', 'action=LoadEmployeeExperienceForm&uid='+uid, result);
	setTimeout(function(){ LoadEmployeeExperienceData('Experience #ExperienceHistoryData'); LoadScripts(); }, 1000);
	
}

function LoadEmployeeExperienceData(result){
	ajaxreq('ajaxpage.php', 'action=LoadEmployeeExperienceData&', result);
	
}

function ProcessExperienceForm(){
	FormType = $('#ExperienceHistorySection #ExperienceFormType').val();
	FormUID = $('#ExperienceHistorySection #ExperienceFormUID').val();
	
	ExperienceEmployer = $('#ExperienceHistorySection #ExperienceEmployer').val();
	if(ExperienceEmployer==''){
		$('#ExperienceHistorySection #ExperienceEmployer').addClass('input-error');
		$('#ExperienceHistorySection #ExperienceEmployer').focus();
		return false;
	} else {
		$('#ExperienceHistorySection #ExperienceEmployer').removeClass('input-error');
	}

	ExperienceDesignation = $('#ExperienceHistorySection #ExperienceDesignation').val();
	if(ExperienceDesignation==''){
		$('#ExperienceHistorySection #ExperienceDesignation').addClass('input-error');
		$('#ExperienceHistorySection #ExperienceDesignation').focus();
		return false;
	} else {
		$('#ExperienceHistorySection #ExperienceDesignation').removeClass('input-error');
	}

	OtherDesignation = $('#ExperienceHistorySection #OtherDesignation').val();
	if(ExperienceDesignation=='other' && OtherDesignation==''){
		$('#ExperienceHistorySection #OtherDesignation').addClass('input-error');
		$('#ExperienceHistorySection #OtherDesignation').focus();
		return false;
	} else {
		$('#ExperienceHistorySection #OtherDesignation').removeClass('input-error');
	}


	ExperienceSallery = $('#ExperienceHistorySection #ExperienceSallery').val();
	/*if(ExperienceSallery==''){
		$('#ExperienceHistorySection #ExperienceSallery').addClass('input-error');
		$('#ExperienceHistorySection #ExperienceSallery').focus();
		return false;
	} else {
		$('#ExperienceHistorySection #ExperienceSallery').removeClass('input-error');
	}*/
	
	OtherSallery = $('#ExperienceHistorySection #OtherSallery').val();
	if(ExperienceSallery=='other' && OtherSallery==''){
		$('#ExperienceHistorySection #OtherSallery').addClass('input-error');
		$('#ExperienceHistorySection #OtherSallery').focus();
		return false;
	} else {
		$('#ExperienceHistorySection #OtherSallery').removeClass('input-error');
	}

	ExperienceYear = $('#ExperienceHistorySection #ExperienceYear').val();
	/*if(ExperienceYear==''){
		$('#ExperienceHistorySection #ExperienceYear').addClass('input-error');
		$('#ExperienceHistorySection #ExperienceYear').focus();
		return false;
	} else {
		$('#ExperienceHistorySection #ExperienceYear').removeClass('input-error');
	}*/
	
	OtherExperience = $('#ExperienceHistorySection #OtherExperience').val();
	if(ExperienceYear=='other' && OtherExperience==''){
		$('#ExperienceHistorySection #OtherExperience').addClass('input-error');
		$('#ExperienceHistorySection #OtherExperience').focus();
		return false;
	} else {
		$('#ExperienceHistorySection #OtherExperience').removeClass('input-error');
	}
	
	ExperienceFrom = $('#ExperienceHistorySection #ExperienceFrom').val();
	/*if(ExperienceFrom==''){
		$('#ExperienceHistorySection #ExperienceFrom').addClass('input-error');
		$('#ExperienceHistorySection #ExperienceFrom').focus();
		return false;
	} else {
		$('#ExperienceHistorySection #ExperienceFrom').removeClass('input-error');
	}*/
	
	ExperienceTo = $('#ExperienceHistorySection #ExperienceTo').val();
	if(ExperienceFrom=='' && ExperienceTo!=''){
		alert("Experience From is Blank.");
		$('#ExperienceHistorySection #ExperienceFrom').addClass('input-error');
		$('#ExperienceHistorySection #ExperienceFrom').focus();
		return false;
	} else {
		$('#ExperienceHistorySection #ExperienceFrom').removeClass('input-error');
	}
	
	if( $('#ExperienceHistorySection #CurrentJob_checkbox').prop("checked") ) 
		current = 1;
	else
		current = 0;

	QuerySting = '&FormType='+FormType+'&ExperienceEmployer='+ExperienceEmployer+'&ExperienceDesignation='+ExperienceDesignation+'&OtherDesignation='+OtherDesignation+'&ExperienceSallery='+ExperienceSallery+'&OtherSallery='+OtherSallery+'&ExperienceYear='+ExperienceYear+'&OtherExperience='+OtherExperience+'&ExperienceFrom='+ExperienceFrom+'&ExperienceTo='+ExperienceTo+'&current='+current;
	
	QuerySting = $('#EmployeeProfile_Expereience').serialize();
	
	ajaxreq('ajaxpage.php', 'action=EmployeeExperienceSubmit&uid='+FormUID+"&"+QuerySting, 'ExperienceHistorySection #AjaxResult');
	
	/*data = new window.FormData($('#EmployeeProfile_Expereience')[0]);
	ajaxform('ajaxpage.php', data, 'ExperienceHistorySection #AjaxResult');*/
	
	setTimeout(function(){ 
		$("#Experience #ExperienceHistorySection").html(''); 
		LoadEmployeeExperienceData('Experience #ExperienceHistoryData'); 
		LoadEmployeeExperienceForm(0, 'ExperienceHistorySection');
	}, 1500);
	
}

function ProcessExperienceDelete(uid){
	if(confirm("Do you want to delete.?")){
		ajaxreq('ajaxpage.php', 'action=ExperienceDelete&uid='+uid);
		setTimeout(function(){ 
			LoadEmployeeExperienceData('Experience #ExperienceHistoryData'); 
		}, 1500);
	}
}



/* xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx */
function LoadEmployeeCertificateForm(uid, result){
	ajaxreq('ajaxpage.php', 'action=LoadEmployeeCertificateForm&uid='+uid, result);
	setTimeout(function(){ LoadScripts(); LoadEmployeeCertificateData('Certificate #CertificateHistoryData');}, 1000);
	
}

function LoadEmployeeCertificateData(result){
	ajaxreq('ajaxpage.php', 'action=LoadEmployeeCertificateData&', result);
	
}

function ProcessCertificateForm(){
	FormType = $('#CertificateHistorySection #CertificateFormType').val();
	FormUID = $('#CertificateHistorySection #CertificateFormUID').val();
	
	Institute = $('#CertificateHistorySection #CertificateInstitute').val();
	if(Institute==''){
		$('#CertificateHistorySection #CertificateInstitute').addClass('input-error');
		$('#CertificateHistorySection #CertificateInstitute').focus();
		return false;
	} else {
		$('#CertificateHistorySection #CertificateInstitute').removeClass('input-error');
	}

	Qualification = $('#CertificateHistorySection #CertificateQualification').val();
	if(Qualification==''){
		$('#CertificateHistorySection #CertificateQualification').addClass('input-error');
		$('#CertificateHistorySection #CertificateQualification').focus();
		return false;
	} else {
		$('#CertificateHistorySection #CertificateQualification').removeClass('input-error');
	}

	CertificateDate = $('#CertificateHistorySection #CertificateDate').val();
	/*if(CertificateDate==''){
		$('#CertificateHistorySection #CertificateDate').addClass('input-error');
		$('#CertificateHistorySection #CertificateDate').focus();
		return false;
	} else {
		$('#CertificateHistorySection #CertificateDate').removeClass('input-error');
	}*/

	QuerySting = '&FormType='+FormType+'&Institute='+Institute+'&Qualification='+Qualification+'&CertificateDate='+CertificateDate+'&';
	QuerySting = $('#EmployeeProfile_Certificate').serialize();
	ajaxreq('ajaxpage.php', 'action=EmployeeCertificateSubmit&uid='+FormUID+"&FormType="+FormType+"&"+QuerySting, 'CertificateHistorySection #AjaxResult');
	
	setTimeout(function(){ 
		$("#Certificate #CertificateHistorySection").html(''); 
		LoadEmployeeCertificateData('Education #CertificateHistoryData'); 
		LoadEmployeeCertificateForm(0, 'CertificateHistorySection');
	}, 1500);
	
}
