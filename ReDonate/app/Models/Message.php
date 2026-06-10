<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Tambahan impor

/**
 * Model Message
 * * merepresentasikan pesan obrolan antar pengguna terkait proses request barang.
 */
class Message extends Model
{
    use HasFactory;

    /**
     * atribut yang dapat diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'claim_id',
        'sender_id',
        'body',
        'read_at',
    ];

    /**
     * mendefinisikan tipe data *casting* untuk atribut tertentu.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    /**
     * Relasi ke model Claim.
     * Setiap pesan terkait dengan satu sesi klaim/request barang.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }

    /**
     * Relasi ke model User (sebagai pengirim pesan).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}