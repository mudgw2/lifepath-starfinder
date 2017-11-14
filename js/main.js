$('document').ready(function () {
	$('i[id^="spinner"]').hide();
})

$('#ajaxform').on('submit', function(e){
	e.preventDefault();
	$('#home').collapse('hide');
	$('#collapseTwo').collapse('hide');
	$('#collapseOne').collapse('show');
});
$('#dungeon_log_button').on('click', function(e){
	$('#home').collapse('hide');
	$('#collapseOne').collapse('show');
	$('#collapseTwo').collapse('show');
	
});

$('body').on('click', function (e) {
    $('[data-toggle="popover"]').each(function () {
        //the 'is' for buttons that trigger popups
        //the 'has' for icons within a button that triggers a popup
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            $(this).popover('hide');
        }
    });
});
$("[data-toggle=popover]").popover({html:true},{container: 'body'});

//callback handler for form submit
$('form[id^="move_form_"]').submit(function(e)
{
	
    var postData = $(this).serializeArray();		
    $.ajax(
    {
        url : "process-move.php",
        type: "POST",
        data : postData,
		datatype: 'json',
		cache: false,
		
		beforeSend: function(){
        // Code to display spinner
			//$('i:first-child').show();
		},
        success:function(response, textStatus, jqXHR) 
        {
			var json = $.parseJSON(response);
			
			console.log("ajax success");
			console.log(json);
			$("#exits-panel").remove();
			
			$.get('template.php', function(template) {
			var view = {desc:json.desc,uid:json.uid};
			var html = Mustache.to_html(template, view);
				// and now append the html anywhere you like
				$('#area_container_'+ json.from_uid).append(html);
			
			});
			
			//$('#area_container_'+ json.from_uid).append('<div class="row"><div class="well"><p style="clear:both;" id="passage_'+ json.uid +'">You enter the passage: '+ json.desc +'</p></div></div>');
			
			//Dim and block previous area
			//$('#area_container_'+ json.from_uid).fadeTo('slow',.6);
			//$('#area_container_'+ json.from_uid).append('<div style="position: absolute;top:0;left:0;width: 100%;height:100%;z-index:2;opacity:0.4;filter: alpha(opacity = 50)"></div>');
		},
		complete: function(){
		$("#collapseTwo").load('dungeon-log.php');
			//$('i:first-child').hide();
		},
        error: function(jqXHR, textStatus, errorThrown) 
        {
            //if fails      
        }
    }).done(function () {
        
      });
    e.preventDefault(); //STOP default action
});
