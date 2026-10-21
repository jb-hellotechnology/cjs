$(document).ready(function(){	
  var pOrganisationID = $('#organisationID').val();
  
  $.post( "/perch/addons/apps/cjs_workdays/offices/get/", { organisationID: pOrganisationID }, function(data){
    var obj = JSON.parse(data);
    $('#organisationName').val(obj.organisationName);
    $('#contactName').val(obj.contactName);
    $('#contactTelephone').val(obj.contactTelephone);
    $('#contactEmail').val(obj.contactEmail);
    $('#contactJobTitle').val(obj.contactJobTitle);
    $('#contactNotes').val(obj.notes);
  });
});

$('#organisationID').change(function(){
  var pOrganisationID = $('#organisationID').val();
  
  $.post( "/perch/addons/apps/cjs_workdays/offices/get/", { organisationID: pOrganisationID }, function(data){
    var obj = JSON.parse(data);
    $('#organisationName').val(obj.organisationName);
    $('#contactName').val(obj.contactName);
    $('#contactTelephone').val(obj.contactTelephone);
    $('#contactEmail').val(obj.contactEmail);
    $('#contactJobTitle').val(obj.contactJobTitle);
    $('#contactNotes').val(obj.notes);
  });
});