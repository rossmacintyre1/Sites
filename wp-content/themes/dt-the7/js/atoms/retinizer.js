

jQuery(document).ready(function($) {
	
	var $document = $(document),
		$window = $(window),
		$html = $("html"),
		$body = $("body"),
		$page = $("#page");
	/* #Retina images using srcset polyfill
================================================== */
	
		window.retinizer = function() {
			 if ($body.hasClass("srcset-enabled")) {
				var $coll = $("img:not(.retinized)").filter("[srcset]"),
					ratio = window.devicePixelRatio ? window.devicePixelRatio : 1;
			
			// 	$coll.each(function() {
			// 		var $this = $(this),
			// 			srcArray = $this.attr("srcset").split(","),
			// 			srcMap = [],
			// 			src = "";

			// 			srcArray.forEach(function(el, i) {
			// 				var temp = $.trim(el).split(" ");
			// 				srcMap[temp[1]] = temp[0];
			// 			});
					
			
			// 			if (ratio >= 1.5) {
			// 				if (!(typeof srcMap["2x"] == "undefined")) src = srcMap["2x"];
			// 				else src = srcMap["1x"];
			// 			}
			// 			else {
			// 				if (!(typeof srcMap["1x"] == "undefined")) src = srcMap["1x"];
			// 				else src = srcMap["2x"];
			// 			};
			// 		// if($this.parents(".iso-container").length > 0){

			// 		// 	$this.attr("src", "data:image/svg+xml;charset=utf-8,%3Csvg xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg' viewBox%3D'0 0 546 526'%2F%3E").addClass("retinized");
			// 		// 	$this.attr("datalayzr", src);
			// 		// 	$this.attr("srcset", "");
			// 		// }else{
			// 			$this.attr("src", src).addClass("retinized");
			// 		// }
			// 	});
		
			// 	// Retina logo in floating menu
				
				// if (! (typeof dtLocal.themeSettings.floatingHeader.logo.src == "undefined")) {
				// 	var logoArray = dtLocal.themeSettings.floatingHeader.logo.src.split(","),
				// 		logoMap = [];
			
				// 	logoArray.forEach(function(el, i) {
				// 		var temp = $.trim(el).split(" ");
				// 		logoMap[temp[1]] = temp[0];
				// 	});
				
			
				// 	if (ratio >= 1.5) {
				// 		if (!(typeof logoMap["2x"] == "undefined")) dtLocal.themeSettings.floatingHeader.logo.src = logoMap["2x"];
				// 		else dtLocal.themeSettings.floatingHeader.logo.src = logoMap["1x"];
				// 	}
				// 	else {
				// 		if (!(typeof logoMap["1x"] == "undefined")) dtLocal.themeSettings.floatingHeader.logo.src = logoMap["1x"];
				// 		else dtLocal.themeSettings.floatingHeader.logo.src = logoMap["2x"];
				// 	};
				// };
			};
		};
		retinizer();
$.fn.layzrInitialisation = function(container) {
  return this.each(function() {
      var $this = $(this);

      var layzr = new Layzr({
        container: container,
        selector: '.lazy-load',
        attr: 'data-src',
        attrSrcSet: 'data-srcset',
        retinaAttr: 'data-src-retina',
        hiddenAttr: 'data-src-hidden',
        threshold: 30,
        before: function() {
          // For fixed-size images with srcset; or have to be updated on window resize.
          this.setAttribute("sizes", this.width+"px");
        },
        callback: function() {

          	this.classList.add("is-loaded");
         	var $this =  $(this);
         	// $this.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(e) {
				setTimeout(function(){
					$this.parent().removeClass("layzr-bg");
				}, 350)
			//});
        }
      });
    });
};
$(".layzr-loading-on, .vc_single_image-img").layzrInitialisation();

/*Call visual composer function for preventing full-width row conflict */
if($('div[data-vc-stretch-content="true"]').length > 0 && $('div[data-vc-full-width-init="false"]').length > 0){
	vc_rowBehaviour();

}
