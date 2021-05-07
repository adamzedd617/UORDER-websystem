/*global $, jQuery, alert*/
$(document).ready(function(){
	"use strict";
	//Nice Scroll
	$("html").nicescroll();
	
	$('.carousel').carousel({
		interval:6000
	});
});