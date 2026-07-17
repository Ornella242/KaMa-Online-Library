<script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/overlay-scrollbar/js/overlayscrollbars.min.js') }}"></script>
<script src="{{ asset('assets/vendor/tiny-slider/tiny-slider.js') }}"></script>
<script src="{{ asset('assets/vendor/glightbox/js/glightbox.js') }}"></script>
<script src="{{ asset('assets/vendor/flatpickr/js/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/vendor/choices/js/choices.min.js') }}"></script>
<script src="{{ asset('assets/vendor/apexcharts/js/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/stepper/js/bs-stepper.min.js') }}"></script>
<script src="{{ asset('assets/vendor/quill/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/vendor/dropzone/js/dropzone.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/functions.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('adminSidebarOverlay');
    const menuToggle = document.getElementById('adminMenuToggle');

    const closeSidebar = () => {
        sidebar?.classList.remove('is-open');
        overlay?.classList.remove('is-visible');
        menuToggle?.setAttribute('aria-expanded', 'false');
    };

    menuToggle?.addEventListener('click', () => {
        const open = sidebar?.classList.toggle('is-open');
        overlay?.classList.toggle('is-visible', Boolean(open));
        menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    overlay?.addEventListener('click', closeSidebar);
    sidebar?.querySelector('.kama-admin-sidebar-close')?.addEventListener('click', closeSidebar);

    document.querySelectorAll('.toggle-password').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.target);
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
            button.classList.toggle('bi-eye');
            button.classList.toggle('bi-eye-slash');
        });
    });

    const deleteUserModal = document.getElementById('deleteUserModal');
    deleteUserModal?.addEventListener('show.bs.modal', (event) => {
        const trigger = event.relatedTarget;
        const userId = trigger?.dataset.userId;
        document.getElementById('deleteUserName').textContent = trigger?.dataset.userName || '';
        document.getElementById('deleteUserForm').action =
            @json(url('/admin/users')).replace(/\/$/, '') + '/' + userId;
    });

    const category = document.getElementById('category_id');
    const subcategory = document.getElementById('subcategory_id');
    category?.addEventListener('change', async () => {
        subcategory.innerHTML = '<option value="">Chargement…</option>';
        subcategory.disabled = true;
        try {
            const response = await fetch(`/writer/categories/${category.value}/subcategories`, {
                headers: {'Accept': 'application/json'}
            });
            if (!response.ok) throw new Error();
            const items = await response.json();
            subcategory.innerHTML = '<option value="">Sélectionnez une sous-catégorie</option>';
            items.forEach((item) => subcategory.add(new Option(item.name, item.id)));
        } catch {
            subcategory.innerHTML = '<option value="">Impossible de charger les sous-catégories</option>';
        } finally {
            subcategory.disabled = false;
        }
    });

    const coverInput = document.getElementById('coverImageInput');
    coverInput?.addEventListener('change', () => {
        const file = coverInput.files?.[0];
        if (!file) return;
        const image = document.getElementById('coverPreviewImage');
        const placeholder = document.getElementById('coverPlaceholder');
        image.src = URL.createObjectURL(file);
        image.classList.remove('d-none');
        placeholder?.classList.add('d-none');
    });

    const typeInputs = document.querySelectorAll('input[name="type"]');
    const fileInput = document.getElementById('bookFileInput');
    const updateBookType = () => {
        const type = document.querySelector('input[name="type"]:checked')?.value;
        if (!type) return;
        const audio = type === 'audio';
        const pagesField = document.getElementById('pagesField');
        const durationField = document.getElementById('durationField');
        pagesField?.classList.toggle('d-none', audio);
        durationField?.classList.toggle('d-none', !audio);

        if (fileInput) {
            fileInput.name = audio ? 'audio_file' : 'ebook_file';
            fileInput.accept = audio ? '.mp3,audio/mpeg' : '.pdf,application/pdf';
        }
        const title = document.getElementById('uploadTitle');
        const description = document.getElementById('uploadDescription');
        const format = document.getElementById('acceptedFormat');
        if (title) title.textContent = audio ? 'Téléverser votre livre audio' : 'Téléverser votre ebook';
        if (description) description.textContent = audio
            ? 'Sélectionnez le fichier MP3 de votre livre audio.'
            : 'Sélectionnez le fichier PDF de votre ebook.';
        if (format) format.textContent = audio ? 'MP3 uniquement' : 'PDF uniquement';

        const previewContainer = document.getElementById('previewTypeContainer');
        const previewType = document.getElementById('preview_type');
        const textPreview = document.getElementById('textPreview');
        const pagesPreview = document.getElementById('pagesPreview');
        previewContainer?.classList.toggle('d-none', audio);
        if (audio && previewType) previewType.value = 'text';
        textPreview?.classList.toggle('d-none', !audio && previewType?.value === 'pages');
        pagesPreview?.classList.toggle('d-none', audio || previewType?.value !== 'pages');
    };
    typeInputs.forEach((input) => input.addEventListener('change', updateBookType));
    document.getElementById('preview_type')?.addEventListener('change', updateBookType);
    updateBookType();

    const editor = document.querySelector('.quilleditor');
    if (editor && typeof Quill !== 'undefined') {
        const quill = new Quill(editor, {
            modules: {toolbar: '.quilltoolbar'},
            theme: 'snow'
        });
        const hiddenDescription = document.getElementById('long_description');
        if (hiddenDescription?.value) quill.root.innerHTML = hiddenDescription.value;
        quill.on('text-change', () => {
            if (hiddenDescription) hiddenDescription.value = quill.root.innerHTML;
        });
    }

    document.querySelectorAll('.delete-book-btn').forEach((button) => {
        button.addEventListener('click', () => {
            const form = button.closest('.delete-book-form');
            Swal.fire({
                title: 'Supprimer ce livre ?',
                text: 'Cette action est définitive.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                reverseButtons: true,
                customClass: {
                    popup: 'kama-delete-popup',
                    actions: 'kama-delete-actions',
                    confirmButton: 'kama-delete-btn kama-delete-confirm',
                    cancelButton: 'kama-delete-btn kama-delete-cancel'
                },
                buttonsStyling: false
            }).then((result) => result.isConfirmed && form?.submit());
        });
    });
});
</script>

@stack('scripts')
