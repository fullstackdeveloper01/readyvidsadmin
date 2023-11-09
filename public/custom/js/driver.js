$('.image').click(function(){

    $('#image_row').html('  <img id="image_set" src="'+this.src+'">');
    
});

var today = new Date();
$('#dob_driver').datepicker({
    format: 'yyyy-mm-dd',//'dd-mm-yyyy',
    autoclose:true,
    endDate: "today",
    maxDate: today
}).on('changeDate', function (ev) {
        $(this).datepicker('hide');
});

$('#nominee_dob').datepicker({
    format: 'yyyy-mm-dd',//'dd-mm-yyyy',
    autoclose:true,
    endDate: "today",
    maxDate: today
}).on('changeDate', function (ev) {
        $(this).datepicker('hide');
});

$('#driving_license_expiry_date').datepicker({
    format: 'yyyy-mm-dd',//'dd-mm-yyyy',
    autoclose:true,
    startDate: today
   
}).on('changeDate', function (ev) {
        $(this).datepicker('hide');
});

$('#isPermanent_driver').change(function(){
    if($('#isPermanent_driver').is(":checked")){
        $('#current_address_driver').val($('#address_driver').val());
        $('#current_address1_driver').val($('#address1_driver').val());
        $('#current_district_driver').val($('#district_driver').val());
        $('#current_state_driver').val($('#state_driver').val());
        $('#current_pincode_driver').val($('#pincode_driver').val());
    }else{
        $('#current_address_driver').val('');
        $('#current_address1_driver').val('');
        $('#current_district_driver').val('');
        $('#current_state_driver').val('');
        $('#current_pincode_driver').val('');
    }
});


$('#same_nominee').change(function(){
    if($('#same_nominee').is(":checked")){
        $('#emergency_contact_number').val($('#nominee_mobile').val());
        $('#emergency_contact_name').val($('#nominee_name').val());
      
    }else{
        $('#emergency_contact_number').val('');
        $('#emergency_contact_name').val('');
       
    }
});

var base_url= "<?php echo env('BASE_URL',''); ?>";
$('#city').change(function(){
    var city =$('#city').val();
    $.ajax({
        method: 'post',
        url: '/api/getZone',
        data: {city:city},
    }).then(response => {
        if (response.status == true) {
            var zone_result = response.data;
            var html='';
            for(var index=0;index<zone_result.length;index++){
                html += '<label class="form-control-label" for="zone_area">'+ zone_result[index].zone_name+'</label></br><label class="form-control-label" for="zone_area">'+ zone_result[index].zone_area+'</label><input type="radio" name="zone_area_name" id="'+zone_result[index].id +'" value="'+zone_result[index].id +'"></br>';
            }
            console.log(html);
            $('#zone').html(html);
        } else {
            
        
        }
        
    }).catch(function (error) {
        console.log(error);
    });
});


$('.image_file').change(function() { debugger;
    var MAX_FILE_SIZE = 2 * 1024 * 1024; // 
    if(this.files[0].type=='image/jpg' || this.files[0].type=='image/jpeg' || this.files[0].type=='image/png'){
            fileSize = this.files[0].size;
        if (fileSize > MAX_FILE_SIZE) {
            this.setCustomValidity("File must not exceed 2 MB!");
            this.reportValidity();
        } else {
            this.setCustomValidity("");
        }
    }else{
        this.setCustomValidity("File type must be image!");
        this.reportValidity();
    }
    
    
});

// $('#driver_save').click(function(){
//     $('#profile_photo').type()
//     var MAX_FILE_SIZE = 2 * 1024 * 1024; // 
//     fileSize = this.files[0].size;
//     if (fileSize > MAX_FILE_SIZE) {
//         this.setCustomValidity("File must not exceed 2 MB!");
//         this.reportValidity();
//     } else {
//         this.setCustomValidity("");
//     }
// });