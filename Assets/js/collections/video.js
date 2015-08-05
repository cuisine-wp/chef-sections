define([
	
	'jquery',
	'fitvids'

], function( $ ){

	$( document ).ready( function(){

		$('.video-wrapper').fitVids();
	
		$('.video-still').on( 'click tap', function( evt ){
	
			evt.preventDefault();
	
			var _wrapper = $( this ).parent();
			var _video = _wrapper.find( 'iframe' );	
			var _src = _video.attr( 'src' );
	
			$( this ).hide();
	
			_video.attr( 'src', _src + '&autoplay=1' );
			_wrapper.addClass( 'playing' );
	
	
		});

	});

});