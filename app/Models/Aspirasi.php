<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aspirasi extends Model
{
    use HasFactory;

    protected $casts = [
        'submitted_to_yayasan_at' => 'datetime',
        'validated_at' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'kategori_id',
        'judul',
        'deskripsi',
        'foto_path',
        'status',
        'submitted_by_admin_id',
        'submitted_to_yayasan_at',
        'validated_by_yayasan_id',
        'validated_at',
        'catatan_yayasan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function umpanBalik()
    {
        return $this->hasOne(UmpanBalik::class);
    }

    public function submittedByAdmin()
    {
        return $this->belongsTo(User::class, 'submitted_by_admin_id');
    }

    public function validatedByYayasan()
    {
        return $this->belongsTo(User::class, 'validated_by_yayasan_id');
    }

    public function logs()
    {
        return $this->hasMany(AspirasiLog::class)->latest();
    }

    /**
     * Scope untuk memfilter aspirasi berdasarkan kriteria tertentu (Fungsi/Prosedur)
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['status'] ?? false, function ($query, $status) {
            return $query->where('status', $status);
        });

        $query->when($filters['kategori'] ?? false, function ($query, $kategori) {
            return $query->where('kategori_id', $kategori);
        });

        $query->when($filters['tanggal'] ?? false, function ($query, $tanggal) {
            return $query->whereDate('created_at', $tanggal);
        });

        $tanggalAwal = $filters['tanggal_awal'] ?? null;
        $tanggalAkhir = $filters['tanggal_akhir'] ?? null;

        if ($tanggalAwal && $tanggalAkhir) {
            $query->whereDate('created_at', '>=', $tanggalAwal)->whereDate('created_at', '<=', $tanggalAkhir);
        } elseif ($tanggalAwal) {
            $query->whereDate('created_at', '>=', $tanggalAwal);
        } elseif ($tanggalAkhir) {
            $query->whereDate('created_at', '<=', $tanggalAkhir);
        }
    }
}
