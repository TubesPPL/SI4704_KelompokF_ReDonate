document.addEventListener('DOMContentLoaded', function () {

    const form = document.querySelector('form[action*="profile/update"]');
    const photoInput = document.getElementById('photo');
    const previewImage = document.getElementById('previewImage');
    const updateBtn = document.querySelector('.btn-update');

    photoInput?.addEventListener('change', function (e) {
        const file = e.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                const newImage = e.target.result;

                if (previewImage) {
                    previewImage.src = newImage;
                }

                const avatars = document.querySelectorAll(
                    '.profile-avatar img, .user-avatar-sm'
                );

                avatars.forEach(img => {
                    img.src = newImage;
                });
            };

            reader.readAsDataURL(file);
        }
    });

    form?.addEventListener('submit', function () {

        if (updateBtn) {
            updateBtn.disabled = true;
            updateBtn.innerHTML = `
                <span class="loading"></span>
                Menyimpan...
            `;
        }

    });

    const deactivateForm = document.querySelector('form[action*="deactivate"]');

    deactivateForm?.addEventListener('submit', function (e) {

        const password = deactivateForm.querySelector('input[name="confirm_password"]').value;

        if (!password) {
            alert('Masukkan password dulu!');
            e.preventDefault();
            return;
        }

        const confirmAction = confirm('Yakin ingin NONAKTIFKAN akun?');

        if (!confirmAction) {
            e.preventDefault();
        }
    });

    const deleteForm = document.querySelector('form[action*="destroy"]');

    deleteForm?.addEventListener('submit', function (e) {

        const confirmAction = confirm(
            '⚠️ PERINGATAN!\n\nAkun akan DIHAPUS PERMANEN.\nSemua data akan hilang.\n\nLanjutkan?'
        );

        if (!confirmAction) {
            e.preventDefault();
        }
    });

});