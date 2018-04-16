jQuery( document ).ready( function ($) {
	
	//set switch value from hidden input
	$(".wbl_switch").each(function() {
		var $value = $(this).children(".switch_value").children("input").val();
		$("li",  $(this)).each(function(){
			if ((!$(this).hasClass("switch_value")) && ($(this).data("val") == $value)){
				$(this).addClass("active");
			}
		});
	});
	
	//active menu items control
	$(".wbl_menu li").click(function(){
		if (!$(this).hasClass("wbl_menu_section")){
			$(".wbl_menu li").removeClass("active");
			$(this).addClass("active");
			wblContentHeight();
			event.preventDefault();
		}
	});
	
	//tabs control
	$(".wbl_menu_items li").each(function(){
		if ( $(this).hasClass("active") ){
			var $activeTab = $(this).children("a").data("href");
			$(".wbl_tab").css("display","none");
			$("#"+$activeTab+".wbl_tab").css("display","block");
		}
	});
	
	$("a").click(function(){
		if ($(this).data("href")){
			var $tab = $(this).data("href");
			$(".wbl_tab").css("display","none");
			$("#"+$tab+".wbl_tab").css("display","block");
		}
	});
	
	//switch (radiobutton) control
	$(".wbl_switch li").click(function(){
		$(this).siblings().removeClass("active");
		$(this).addClass("active");
		$(this).siblings(".switch_value").children("input").attr("value",$(this).data("val"));
		event.preventDefault();
	});
	
	//message-boxes closing contol
	$(".wbl_success span,.wbl_error span,.wbl_warning span").click(function(){
		$(this).parent().fadeOut(300, function(){
			$(this).remove();
		});
	});
	
	//mobile menu generation
	function mobileMenu(){
		$(".wbl_menu li").each(function(){
			if (!$(this).hasClass("wbl_menu_section")){
				var $iconClass = $(this).children("a").attr("class");
				var $linkHref = $(this).children("a").attr("href");
				var $insertSpan = "<a href=\""+$linkHref+"\" class=\"wbl_mobile_link\"><span class=\"dashicons "+$iconClass+"\"></span></a>";
				$(this).children("a").before($insertSpan);
				$(this).children("a").not(".wbl_mobile_link").addClass("wbl_hide_mobile");
			} else {
				$(this).find("span").not(".wbl_counter").addClass("wbl_hide_mobile");
			}
		});
	}
	mobileMenu();
	
	function wblContentHeight(){
		var $height = $("#wbl_left_menu").height();

		if ( $height < $(".wbl_main").height() ){
			$height = $(".wbl_main").height();
		}
		if ( $height < $(".right_sidebar").height() ){
			$height = $(".right_sidebar").height();
		}
		$("#wbl_left_menu, .wbl_main, .right_sidebar").css("min-height", $height);
	}
	wblContentHeight();
	
});