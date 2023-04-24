jQuery(document).ready(function ($) {
	"use strict";
	//  TESTIMONIALS CAROUSEL HOOK
	$('#customers-testimonials').owlCarousel({
		loop: true,
		center: true,
		items: 3,
		margin: 0,
		autoplay: true,
		dots: true,
		autoplayTimeout: 8500,
		smartSpeed: 450,
		responsive: {
			0: {
				items: 1
			},
			768: {
				items: 2
			},
			1170: {
				items: 3
			}
		}
	});
});



let banner = document.getElementsByClassName("banner")
//banner.style.alignContent("center");
//banner.style.maxWidth("100");
//banner.style.height("auto");

let win = window.onbeforeunload

window.onbeforeunload = ExitPage;
    function ExitPage()
{
    getit()
    return alert("you dont exit skeltons.com...Do you?"); 
}
function getit() {
console.log("I am in getit")
}


function menu() {

    let htm = "<div>Hello</div>";
    let menuDrop = document.getElementById("menuDrop");
    return menuDrop.append(htm);

}