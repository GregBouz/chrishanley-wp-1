jQuery(document).ready(function(){

	//Navigation active highlight
	jQuery('li.current_page_item').parents('.nav > li').addClass('current_page_item');
	jQuery('li.current-cat').parents('.nav > li').addClass('current-cat');
	
	//Validate Number Fields
	jQuery('.validate_number').each(function (){
		//when key is pressed in the textbox
		jQuery(this).keypress(function (e) {
			//if the letter is not digit then display error and don't type anything
			if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57)) {
				//display error message
				return false;
			}
		});
	});
	
	
	jQuery('#loopedSlider.gallery div.container div.slides div a.thickbox').each(function(){
	
		jQuery(this).mouseenter(function(){
			
			if (jQuery(this).find('div.gallery-hover').length > 0){
				//do nothing
			} else {
				var adddiv = jQuery('<div>').addClass('gallery-hover');
				jQuery(this).append(adddiv);
			}
		});
		
		jQuery(this).mouseleave(function(){
			
			//kill it
			if (jQuery(this).find('div.gallery-hover').length > 0){
				jQuery(this).find('div.gallery-hover').remove();
			} else {
				//do nothing
			}
			
		});
		
		
	});
	
	jQuery('.contact .agent-popup').hide();
		
		
		jQuery('#single-meta .contact span').click(function(){
			
			jQuery('.contact .agent-popup').toggle();
			
		});
	
	jQuery('#mls-search a').live('click', function () {
		
		var searchtext = jQuery('#local-search').text();
		var mlstext = jQuery('#mls-search a').text();
		jQuery('#local-search').html('<a class="red-highlight">' + searchtext + '</a>');
		
		jQuery('#mls-search').html(mlstext);
		jQuery('#mls-search').addClass('current');
		jQuery('#local-search').removeClass('current');
		
		jQuery('#property-mls-search').show();
		jQuery('#property-webref-search').hide();
		jQuery('#property-search').hide();
		
	});
	
	jQuery('#local-search a').live('click', function () {
		
		var searchtext = jQuery('#local-search a').text();
		var mlstext = jQuery('#mls-search').text();
		jQuery('#mls-search').html('<a class="red-highlight">' + mlstext + '</a>');
		
		jQuery('#local-search').html(searchtext);
		jQuery('#local-search').addClass('current');
		jQuery('#mls-search').removeClass('current');
		
		jQuery('#property-mls-search').hide();
		jQuery('#property-webref-search').show();
		jQuery('#property-search').show();
		
	});
	
	jQuery('#property-mls-search').hide();
	
	/* IDX LISTINGS */
	
	jQuery('#idx-listing li hr').each(function(){
	
		jQuery(this).parent('li').remove();
	
	});
	
	jQuery('#main #idx-listing.idx-full ol#dsidx-listings li:even').each(function(){
	
		jQuery(this).addClass('even');
	
	});
	
	jQuery('#main #idx-listing.idx-full ol#dsidx-listings').after('<div class="fix"></div>')
	
	jQuery('#main #idx-listing.idx-full .dsidx-paging-control:last').addClass('bottom');
	
	jQuery('#main #idx-listing.single #dsidx-secondary-data').before('<h3>General Details</h3>');
	
	jQuery('#main #idx-listing.single #dsidx-contact-form .dsidx-contact-form-schedule-yesno').each(function(){
		jQuery(this).parent('td').addClass('checkbox');
	});
	
	jQuery('#main #idx-listing.single #dsidx-contact-form table td label:contains("Comments")').parent('td').addClass('alignleft');
	
	jQuery('#dsidx-contact-form-submit').addClass('button');
	
	jQuery('#main #idx-listing.single #dsidx-contact-form table tr:last td').addClass('form-disclaimer');
	
});