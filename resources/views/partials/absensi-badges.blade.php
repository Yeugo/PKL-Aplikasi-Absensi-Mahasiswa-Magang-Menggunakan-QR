@if ($absensi->data->is_holiday_today)
<span class="badge text-bg-success ">Hari Libur</span>
@else

@if ($absensi->data->is_start)
<span class="badge text-bg-primary ">Jam Masuk</span>
@elseif($absensi->data->is_end)
<span class="badge text-bg-warning ">Jam Pulang</span>
@else
<span class="badge text-bg-danger ">Tutup</span>
@endif
@endif