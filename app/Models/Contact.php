<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    protected $table = "contacts"; // attribute $table binding model dengan table db
    protected $primaryKey = "id"; // attribute $primaryKey PK dari table db
    protected $keyType = "int"; // attribute $keyType type data PK dari table db
    public $incrementing = true; // attribute $incrementing type data PK auto_increment ketika data di tambah
    public $timestamps = true; // attribute $timestamps implementasi created_at dan updated_at dari laravel

    // $fillable adalah supaya allow Request $request masuk dari http request dan web request. tanpa harus binding data attribute model/entity dan request key
    protected $fillable = [
        "first_name",
        "last_name",
        "email",
        "phone"
    ];

    // balikan dari 1 to M, menjadi M to 1
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", "id"); // belongsTo(Model_Relasi, FK_On_contants, PK_on_contacts) // relasi M to 1
    }

    // relasi 1 to M
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, "contact_id", "id"); // hasMany(Model_Relasi, FK_On_addresses, PK_on_contacts) // relasi 1 to M
    }

}
