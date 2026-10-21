document.addEventListener('DOMContentLoaded', function () {
	const form = document.getElementById('content-edit');
	if (!form) return;
	
	// Create and inject the modal
	const modal = document.createElement('div');
	modal.id = 'ajax-save-modal';
	modal.innerHTML = `
		<div id="ajax-save-modal-inner">
			<p id="ajax-save-modal-message">Saving...</p>
			<div id="ajax-save-modal-actions" style="display:none;">
				<button id="ajax-save-close-btn">OK</button>
			</div>
		</div>
	`;
	document.body.appendChild(modal);
	
	const modalEl      = document.getElementById('ajax-save-modal');
	const modalMessage = document.getElementById('ajax-save-modal-message');
	const modalActions = document.getElementById('ajax-save-modal-actions');
	const previewBtn   = document.getElementById('ajax-save-preview-btn');
	const closeBtn     = document.getElementById('ajax-save-close-btn');

	closeBtn.addEventListener('click', function () {
		modalEl.classList.remove('is-visible');
	});

	// Intercept form submit
	form.addEventListener('submit', function (e) {
		e.preventDefault();
	
		modalMessage.textContent = 'Saving...';
		modalActions.style.display = 'none';
		modalEl.classList.add('is-visible');
	
		const formData = new FormData(form);
	
		// Use the form's own action URL, not window.location.href
		const actionUrl = form.getAttribute('action') || window.location.href;
	
		fetch(actionUrl, {
			method: 'POST',
			body: formData,
			// Remove the XMLHttpRequest header - Perch doesn't expect AJAX requests
		})
		.then(response => {
			if (!response.ok) throw new Error('Server error: ' + response.status);
			return response.text();
		})
		.then((html) => {
			// Parse the response and extract the new token
			const parser = new DOMParser();
			const doc = parser.parseFromString(html, 'text/html');
			const newToken = doc.querySelector('input[name="token"]');
			
			if (newToken) {
				// Update the token in the live form so the next save works
				const currentToken = form.querySelector('input[name="token"]');
				if (currentToken) {
					currentToken.value = newToken.value;
				}
			}
		
			modalMessage.textContent = 'Saved. Please check the live page to ensure the changes have been saved.';
			modalActions.style.display = 'flex';
		})
		.catch(err => {
			modalMessage.textContent = 'Something went wrong. Please try saving again.';
			console.error(err);
		});
	});
});