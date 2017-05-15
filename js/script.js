/**
 * Created by Cronos on 5/14/2017.
 */
$('#travelAs').change(function(){
    if ($('#travelAs option:selected').val() == 'Families'){
        $("#kids").show();
        $("label[for='kids']").show();
        $(".input_fields_wrap").show();
    }else{
        $("#kids").hide();
        $("label[for='kids']").hide();
        $(".input_fields_wrap").hide();
    }
});

$('.drop-field')
    .dropdown()
;

$('form').submit(function (e) {
    var submit = true;
//    TODO:fix!!
//        $('select').siblings().each(function () {
//            if($(this).attr('id')=='country' && $(this).attr('id')=='months'){
//                if ($(this).siblings().length < 6) {
//                    $(this).parent().addClass('error');
//                    submit = false;
//                }
//                else {
//                    $(this).parent().removeClass('error');
//                }
//            }else {
//                if ($(this).siblings().length < 4) {
//                    $(this).parent().addClass('error');
//                    submit = false;
//                }
//                else {
//                    $(this).parent().removeClass('error');
//                }
//            }
//        })


    if($('#country').siblings().length<6){
        $('#country').parent().addClass('error');
        submit = false;
    }else{
        $('#country').parent().removeClass('error');
    }

    if($('#months').siblings().length<6){
        $('#months').parent().addClass('error');
        submit = false;
    }else{
        $('#months').parent().removeClass('error');
    }
    if($('#travelAs').siblings().length<4){
        $('#travelAs').parent().addClass('error');
        submit = false;
    }else{
        $('#travelAs').parent().removeClass('error');
    }
    if($('#rooms').siblings().length<4){
        $('#rooms').parent().addClass('error');
        submit = false;
    }else{
        $('#rooms').parent().removeClass('error');
    }
    if($('#housing').siblings().length<4){
        $('#housing').parent().addClass('error');
        submit = false;
    }else{
        $('#housing').parent().removeClass('error');
    }
    if($('#meal').siblings().length<4){
        $('#meal').parent().addClass('error');
        submit = false;
    }else{
        $('#meal').parent().removeClass('error');
    }
    if($('#facilities').siblings().length<4){
        $('#facilities').parent().addClass('error');
        submit = false;
    }else{
        $('#facilities').parent().removeClass('error');
    }
    if(submit == false){
        e.preventDefault()
    }
});


$('#kids').keyup(function() {
    // Limit input to 1 character int
    var max = parseInt($(this).attr('max'));
    var min = parseInt($(this).attr('min'));
    if ($(this).val() > max || $(this).val().length >1){
        $(this).val(max);
    }
    else if ($(this).val() < min){
        $(this).val(min);
    }

    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    $(wrapper).empty();
    $(wrapper).append('<p>Enter the birth date of each kid:</p>');
    for(var x=1;x <= $('#kids').val();x++){
        $(wrapper).append('<br><div class="ui input"><input type="date" name="kidsBirthdays[]" required /></div>'); //add input box
    }
});


