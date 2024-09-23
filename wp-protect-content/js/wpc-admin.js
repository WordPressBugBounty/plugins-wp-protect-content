/* Custom Share Buttons With Floting Sidebar admin js*/
jQuery(document).ready(function(){
	    jQuery(".wpc-tab").hide();
		jQuery("#div-wpc-general").show();
	    jQuery(".wpc-tab-links").click(function(){
		var divid=jQuery(this).attr("id");
		jQuery(".wpc-tab-links").removeClass("active");
		jQuery(".wpc-tab").hide();
		jQuery("#"+divid).addClass("active");
		jQuery("#div-"+divid).fadeIn();
		});
});
