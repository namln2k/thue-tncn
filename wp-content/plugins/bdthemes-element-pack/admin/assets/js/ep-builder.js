/* eslint-disable prettier/prettier */
!(function ($) {
	'use strict';

	function showModal() {
		$('#bdthemes-templates-builder-modal').show();
	}

	function hideModal() {
		$('#bdthemes-templates-builder-modal').hide();
	}

	function resetModalForm() {
		$('#bdthemes-templates-builder-modal form')[0].reset();
		$('#bdthemes-templates-builder-modal form .template_id').val('');
	}

	function setSubmitBtn(string) {
		$('#bdthemes-templates-builder-modal form .bdt-modal-submit-btn').val(
			string,
		);
	}

	function setError($this) {
		$this.addClass('input-error');
	}

	function removeError($this) {
		$('.input-error').removeClass('input-error');
	}

	$(document).on(
		'click',
		'#bdthemes-templates-builder-modal .bdt-modal-close-button',
		function (e) {
			hideModal();
		},
	);

	$(document).on(
		'click',
		'body.post-type-bdt-template-builder a.page-title-action',
		function (e) {
			e.preventDefault();
			resetModalForm();
			setSubmitBtn('Create Template');
			showModal();
		},
	);

	$(document).on(
		'submit',
		'#bdthemes-templates-builder-modal form',
		function (e) {
			e.preventDefault();
			var $serialized = $(this).serialize();
			removeError();

			$.ajax({
				url: ajaxurl,
				dataType: 'json',
				method: 'post',
				cache: false,
				data: {
					action: 'bdthemes_builder_create_template',
					data: $serialized,
				},
				success: function (response) {
					window.location.href = response.data.redirect;
				},
				error: function (errorThrown) {
					if (errorThrown.status == 422) {
						$.each(
							errorThrown.responseJSON.data.errors_arr,
							function (index, value) {
								setError($('#bdthemes-templates-builder-modal #' + index));
							},
						);
					}
				},
			});
		},
	);

	$(document).on(
		'click',
		'body.post-type-bdt-template-builder .row-actions .bdt-edit-action a',
		function (e) {
			e.preventDefault();
			removeError();
			resetModalForm();
			setSubmitBtn('Update Template');

			$.ajax({
				url: ajaxurl,
				dataType: 'json',
				method: 'post',
				data: {
					action: 'bdthemes_builder_get_edit_template',
					template_id: $(this).data('id'),
				},
				success: function (response) {
					if (response.success) {
						$('#bdthemes-templates-builder-modal form .template_id')
							.val(response.data.id)
							.change();
						$('#bdthemes-templates-builder-modal form #template_name')
							.val(response.data.name)
							.change();
						$('#bdthemes-templates-builder-modal form #template_type')
							.val(response.data.type)
							.change();
						$('#bdthemes-templates-builder-modal form #template_status')
							.val(response.data.status)
							.change();
					}
					showModal();
				},
				error: function (errorThrown) {
					console.log(errorThrown);
					if (errorThrown.status == 422) {
					}
				},
			});
		},
	);
})(jQuery);