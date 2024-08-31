  jQuery(function(){

    jQuery.each(jQuery(".print-tab .print-tab-menu > li"), function(index, value){
      var menu = jQuery(value).data('tab-menu');
      var tabID = jQuery(value).parent().parent().data('tab-id');
      var hash = window.location.hash.split("#").join('');
      
      if(hash.length > 0){
          
        if(menu == hash){
          jQuery('.print-tab[data-tab-id="' + tabID + '"] .print-tab-menu > li[data-tab-menu="' + menu + '"]').addClass('active');
          jQuery('.print-tab[data-tab-id="' + tabID + '"] .print-tab-content > div[data-tab-content="' + menu + '"]').addClass('view');
        }
        
      }else{
        jQuery('.print-tab[data-tab-id="' + tabID + '"] .print-tab-menu > li:eq(0)').addClass('active');
        jQuery('.print-tab[data-tab-id="' + tabID + '"] .print-tab-content > div:eq(0)').addClass('view');
      }
    });
    


    jQuery(".print-tab .print-tab-menu > li").click(function(event){
      var jQuerythis = jQuery(this),
        jQuerydata = jQuerythis.data('tab-menu'),
        jQuerytabID = jQuerythis.parent().parent().data('tab-id');
      if(!jQuery(this).hasClass("active")){

        window.location.hash = jQuerydata;
        
        jQuery('.print-tab[data-tab-id="' + jQuerytabID + '"] .print-tab-menu > li').removeClass('active');
        jQuery(this).addClass('active');
        
        jQuery('.print-tab[data-tab-id="' + jQuerytabID + '"] .print-tab-content > div.view').removeClass('view');
        jQuery('.print-tab[data-tab-id="' + jQuerytabID + '"] .print-tab-content > div[data-tab-content="' + jQuerydata + '"]').addClass('view');
      }
    });
  });