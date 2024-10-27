/* start of aha_key.php js  */
jQuery(document).ready(function(){	
	jQuery('input#checkboxG3').on('change', function() {
		jQuery('input#checkboxG3').not(this).prop('checked', false);  
		if (jQuery('input#checkboxG3').is(':checked')) {
			var data = jQuery('input#checkboxG3:checked').val();
			jQuery("#sel_books").remove();
			jQuery(".book_result").append("<form action='' method='post' ><input type='hidden' name='data' value='"+data+"' ><input type='submit' class='button button-primary' id='sel_books' name='submits' value='Select AHAbook' ></form>");
		}else{
			jQuery("#sel_books").remove();
		}
	});

	jQuery("#form_sub").click(function(){
		jQuery("#img_load").css("display","block");
	});
	
	jQuery(".pcatchecks").click(function(){
		var curid = jQuery(this).val() ; 
		var subul = jQuery(this).parent('#'+curid).children('#childul_'+curid+' li input') ; 
		subul.prop('checked',true);
		var  checkallinner = 'remove'; 
		if(jQuery(this).is(':checked')){
			checkallinner = 'check'  ;
		}		
		var get_cats = '';
		get_cats  = checktick(0,''); 
		jQuery(".selected_cat").val(get_cats);
	});


	// .....//

	jQuery("body").on("keyup", "#myInputLIB", function(e){
		var str = jQuery("#myInputLIB").val();
		jQuery(".book_main_con .col-sm-2").each(function(index){
			if(jQuery(this).find("label:first")){
				if(!jQuery(this).find("label:first").text().match(new RegExp(str, "i"))){
					jQuery(this).fadeOut("fast");
				}else{
					jQuery(this).fadeIn("slow");
				}
			}
		});		
	});
	
	jQuery("body").on("keyup", "#myInputcat", function(e){
		var str = jQuery("#myInputcat").val();
		jQuery(".cats_books .sbook").each(function(index){
			if(jQuery(this).find(".stestarea a")){
				if(!jQuery(this).find(".stestarea a").text().match(new RegExp(str, "i"))){
					jQuery(this).fadeOut("fast");
				}else{
					jQuery(this).fadeIn("slow");
				}
			}
		});		
	});
	
	jQuery("body").on("keyup", "#myInputbook", function(e){
		var str = jQuery("#myInputbook").val();
		alert(str);
		jQuery(".book_main_con_all .col-sm-2").each(function(index){
			if(jQuery(this).find("label:first").text()){
				if(!jQuery(this).find("label:first").text().match(new RegExp(str, "i"))){
					jQuery(this).fadeOut("fast");
				}else{
					jQuery(this).fadeIn("slow");
				}
			}
		});		
	});

    var height = jQuery(".main_head").height();
    var line_height = jQuery(".main_head").css('line-height');
    line_height = parseFloat(line_height)
    var rows = height / line_height;
    Math.round(rows);
    if(Math.round(rows) == 1){
    	jQuery( ".main_head" ).append( "<br />" );
    }
});
function checktick(level,childul)
{
	var cat_str = '' ; 
	var sul = '';
	if(childul==''){
		sul = "ul.level_"+level+" li.level_li_"+level; 
	}else{			
		sul = childul+' li.level_li_'+level;		
	}
			
	jQuery(sul).each(function(){
		var cid = jQuery(this).attr('id') ; 
		if(jQuery(this).children('input.check_'+cid+'_level_'+level).is(':checked')){	
			var catval = '';
			var catval = jQuery(this).children('input.check_'+cid+'_level_'+level).val();
			cat_str += catval+',';
		}
	 	var next_level = '' ;
		next_level = level + 1 ;
		if(jQuery(this).children('ul#childul_'+cid).hasClass('level_'+next_level)){
			var subcat = '' ; 
			subcat += checktick(next_level,'ul#childul_'+cid);
			cat_str +=  subcat  ; 
		}
	});
		
	return cat_str; 
}
/* end of aha_key.php js  */