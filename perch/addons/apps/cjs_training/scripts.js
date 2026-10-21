$(window).ready(function(){

  var pOrganisationID = $('#tOrganisationID').val();

  $.post( "/perch/addons/apps/cjs_training/offices/get/", { organisationID: pOrganisationID }, function(data){
    var obj = JSON.parse(data);
    $('#organisationName').val(obj.organisationName);
    $('#contactName').val(obj.contactName);
    $('#contactTelephone').val(obj.contactTelephone);
    $('#contactEmail').val(obj.contactEmail);
  });
	
})
$('#tOrganisationID').change(function(){
  var pOrganisationID = $('#tOrganisationID').val();

  $.post( "/perch/addons/apps/cjs_training/offices/get/", { organisationID: pOrganisationID }, function(data){
    var obj = JSON.parse(data);
    $('#organisationName').val(obj.organisationName);
    $('#contactName').val(obj.contactName);
    $('#contactTelephone').val(obj.contactTelephone);
    $('#contactEmail').val(obj.contactEmail);
  });
})