{{-- Bootstrap 5.2 JS --}}
<script src="{{ asset('bootstrap5/js/bootstrap.bundle.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"
    integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous">
</script>

<script>
    feather.replace({ 'aria-hidden': 'true' })
</script>

<script>
    // Menambahkan event listener untuk tombol edit
    $('#kegiatanModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Tombol Edit
        var id = button.data('id');  // Ambil ID kegiatan
        var modal = $(this);

        // Jika ID tidak ada, maka ini adalah modal untuk tambah data
        if (id) {
            // Set judul modal
            modal.find('.modal-title').text('Edit Kegiatan Peserta Magang');

            // Mengambil data kegiatan menggunakan AJAX
            $.ajax({
                url: '/home/kegiatan/' + id + '/edit', // URL untuk edit data
                method: 'GET',
                success: function(data) {
                    // Mengisi form dengan data yang diterima
                    modal.find('#kegiatanForm').attr('action', '/home/kegiatan/' + id);  // Ubah action form ke route update
                    modal.find('#judul').val(data.judul);
                    modal.find('#deskripsi').val(data.deskripsi);
                    modal.find('#tgl_kegiatan').val(data.tgl_kegiatan);
                    modal.find('#waktu_mulai').val(data.waktu_mulai);
                    modal.find('#waktu_selesai').val(data.waktu_selesai);
                }
            });
        } else {
            // Jika ID kosong, maka ini adalah modal untuk tambah data
            modal.find('.modal-title').text('Tambah Kegiatan Peserta Magang');
            modal.find('#kegiatanForm').attr('action', '{{ route('home.kegiatan.store') }}');
            modal.find('#judul').val('');
            modal.find('#deskripsi').val('');
            modal.find('#tgl_kegiatan').val('');
            modal.find('#waktu_mulai').val('');
            modal.find('#waktu_selesai').val('');
        }
    });
</script>

{{-- Main JS --}}
<script type="module" src="{{ asset('js/main.js') }}"></script>
{{-- Livewire JS --}}
@livewireScripts