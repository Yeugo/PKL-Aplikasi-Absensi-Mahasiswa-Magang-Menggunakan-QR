<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\URL;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'title',
        'description',
        'start_time',
        'batas_start_time',
        'end_time',
        'batas_end_time',
        'code'
    ];

    protected $appends = ['data'];

    protected function data(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $now = now();
                $startTime = Carbon::parse($this->start_time);
                $batasStartTime = Carbon::parse($this->batas_start_time);

                $endTime = Carbon::parse($this->end_time);
                $batasEndTime = Carbon::parse($this->batas_end_time);

                $isHolidayToday = Holiday::query()
                    ->where('holiday_date', now()->toDateString())
                    ->get();

                return (object) [
                    "start_time" => $this->start_time,
                    "batas_start_time" => $this->batas_start_time,
                    "end_time" => $this->end_time,
                    "batas_end_time" => $this->batas_end_time,
                    "now" => $now->format("H:i:s"),
                    "is_start" => $startTime <= $now && $batasStartTime >= $now,
                    "is_end" => $endTime <= $now && $batasEndTime >= $now,
                    'is_using_qrcode' => $this->code ? true : false,
                    'is_holiday_today' => $isHolidayToday->isNotEmpty()
                ];
            },
        );
    }
}
