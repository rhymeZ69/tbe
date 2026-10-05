  /* ============================================================
     GLOBAL DELETE CONFIRMATION MODAL
     ============================================================ */
  const deleteModal        = document.getElementById('deleteModal');
  const deleteModalTitle   = document.getElementById('deleteModalTitle');
  const deleteModalMessage = document.getElementById('deleteModalMessage');
  const deleteModalConfirm = document.getElementById('deleteModalConfirm');

  let pendingDeleteForm = null;

  function openDeleteModal(form, itemName, itemType) {
    pendingDeleteForm = form;

    const label = itemName ? `"${itemName}"` : 'this item';
    const noun  = itemType || 'item';

    deleteModalTitle.textContent = `Delete ${noun}?`;
    deleteModalMessage.innerHTML  =
      `You're about to permanently delete <strong>${escapeHtml(label)}</strong>.<br>` +
      `<span class="modal__warning">This action cannot be undone.</span>`;

    deleteModal.classList.add('open');
    deleteModal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';

    setTimeout(() => deleteModalConfirm.focus(), 60);
  }

  function closeDeleteModal() {
    deleteModal.classList.remove('open');
    deleteModal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    pendingDeleteForm = null;
  }

  function escapeHtml(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  /* Delegate click: any .js-delete-btn opens the modal */
  document.addEventListener('click', e => {
    const btn = e.target.closest('.js-delete-btn');
    if (!btn) return;

    e.preventDefault();

    const form = btn.closest('form');
    if (!form) return;

    openDeleteModal(
      form,
      btn.dataset.itemName || '',
      btn.dataset.itemType || 'item'
    );
  });

  /* Confirm → submit the pending form */
  if (deleteModalConfirm) {
    deleteModalConfirm.addEventListener('click', () => {
      if (!pendingDeleteForm) return;

      deleteModalConfirm.disabled = true;
      deleteModalConfirm.innerHTML = 'Deleting…';

      pendingDeleteForm.submit();
    });
  }

  /* Close triggers */
  document.querySelectorAll('[data-close-modal]').forEach(el => {
    el.addEventListener('click', closeDeleteModal);
  });

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && deleteModal?.classList.contains('open')) {
      closeDeleteModal();
    }
  });