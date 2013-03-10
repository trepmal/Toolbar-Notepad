jQuery(document).ready( function($) {

	var tbbtn = $('#wp-admin-bar-toolbar-notepad'),
		tbnp_wrap = $('.tbnp-page-wrap'),
		tbnp_ntpd = $('.tbnp-notepad'),
		tbnp_txta = $('.tbnp-page-wrap textarea'),
		tbnp_save = $('.tbnp-page-wrap .save'),
		tbnp_edit = $('.tbnp-page-wrap .edit');

	tbbtn.click( function( ev ) {
		ev.preventDefault();
		tbnp_wrap.toggle();
	});

	tbnp_edit.click( function(ev) {
		ev.preventDefault();
		tbnp_edit.hide();
		tbnp_save.show();
		tbnp_txta.removeAttr('readonly');
	});

	tbnp_save.click( function(ev) {
		ev.preventDefault();
		tbnp_save.hide();
		tbnp_edit.show();
		tbnp_txta.attr('readonly', 'readonly');
		tbnp_save.after( '<img id="tbnp-load" src="' + tbnp.loading + '" />');
		$.post( tbnp.ajaxurl, {
			action: 'save_notes',
			nonce: tbnp.nonce,
			notes: tbnp_txta.val()
		}, function( response ) {
			// console.log( response );
			$('#tbnp-load').remove();
		});
	});

});