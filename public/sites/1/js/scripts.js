function oneAction () {
$('.about-2-wrapper').hide();
$('.about-2-content').hide();
$('.about-3-wrapper').hide();
$('.about-3-content').hide();
$('.less').show();
$('.about-1a').hide();
$('.about-1b').show();
$('.about-1-wrapper').addClass('about-1-wrapper-active');
$('.about-1-wrapper').removeClass('about-1-wrapper');
$('.about-1-content').addClass('about-1-content-active');
$('.about-1-content').removeClass('about-1-content');

window.setTimeout(function() {
	var about1Height = $('.about-1b').outerHeight();
	var contentAboutHeight = about1Height+50;
	$('#content-about').height(contentAboutHeight);
	$('#content-about').attr('id','content-about-active');
},500);
}

function twoAction () {
$('.about-1-wrapper').hide();
$('.about-1-content').hide();
$('.about-3-wrapper').hide();
$('.about-3-content').hide();
$('.less').show();
$('.about-2a').hide();
$('.about-2b').show();
$('.about-2-wrapper').addClass('about-2-wrapper-active');
$('.about-2-wrapper').removeClass('about-2-wrapper');
$('.about-2-content').addClass('about-2-content-active');
$('.about-2-content').removeClass('about-2-content');

window.setTimeout(function() {
	var about2Height = $('.about-2b').outerHeight();
	var contentAboutHeight = about2Height+50;
	$('#content-about').height(contentAboutHeight);
	$('#content-about').attr('id','content-about-active');
},500);
}

function threeAction () {
$('.about-1-wrapper').hide();
$('.about-1-content').hide();
$('.about-2-wrapper').hide();
$('.about-2-content').hide();
$('.less').show();
$('.about-3a').hide();
$('.about-3b').show();
$('.about-3-wrapper').addClass('about-3-wrapper-active');
$('.about-3-wrapper').removeClass('about-3-wrapper');
$('.about-3-content').addClass('about-3-content-active');
$('.about-3-content').removeClass('about-3-content');

window.setTimeout(function() {
	var about3Height = $('.about-3b').outerHeight();
	var contentAboutHeight = about3Height+50;
	$('#content-about').height(contentAboutHeight);
	$('#content-about').attr('id','content-about-active');
},500);
}

function resetAction () {

$('#content-about-active').attr('id','content-about');
$('#content-about').removeAttr('style');

$('.less').hide();
$('.about-1b').hide();
$('.about-1a').show();
$('.about-2b').hide();
$('.about-2a').show();
$('.about-3b').hide();
$('.about-3a').show();

$('.about-1-wrapper-active').show();
$('.about-1-content-active').show();
$('.about-2-wrapper-active').show();
$('.about-2-content-active').show();
$('.about-3-wrapper-active').show();
$('.about-3-content-active').show();

$('.about-1-wrapper-active').addClass('about-1-wrapper');
$('.about-1-wrapper-active').removeClass('about-1-wrapper-active');
$('.about-1-content-active').addClass('about-1-content');
$('.about-1-content-active').removeClass('about-1-content-active');

$('.about-2-wrapper-active').addClass('about-2-wrapper');
$('.about-2-wrapper-active').removeClass('about-2-wrapper-active');
$('.about-2-content-active').addClass('about-2-content');
$('.about-2-content-active').removeClass('about-2-content-active');

$('.about-3-wrapper-active').addClass('about-3-wrapper');
$('.about-3-wrapper-active').removeClass('about-3-wrapper-active');
$('.about-3-content-active').addClass('about-3-content');
$('.about-3-content-active').removeClass('about-3-content-active');

$('.about-1-wrapper-active').hide();
$('.about-1-wrapper').show();

$('.about-1-content-active').hide();
$('.about-1-content').show();

$('.about-2-wrapper-active').hide();
$('.about-2-wrapper').show();

$('.about-2-content-active').hide();
$('.about-2-content').show();

$('.about-3-wrapper-active').hide();
$('.about-3-wrapper').show();

$('.about-3-content-active').hide();
$('.about-3-content').show();
}

$('#oneMore').click(oneAction);
$('#twoMore').click(twoAction);
$('#threeMore').click(threeAction);
$('[data-action="reset"]').click(resetAction);

