<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    protected $table = "users"; // attribute $table binding model dengan table db
    protected $primaryKey = "id"; // attribute $primaryKey PK dari table db
    protected $keyType = "int"; // attribute $keyType type data PK dari table db
    public $incrementing = true; // attribute $incrementing type data PK auto_increment ketika data di tambah
    public $timestamps = true; // attribute $timestamps implementasi created_at dan updated_at dari laravel

    // $fillable adalah supaya allow Request $request masuk dari http request dan web request. tanpa harus binding data attribute model/entity dan request key
    protected $fillable = [
        "username",
        "password",
        "name"
    ];

    // relasi 1 to M
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, "user_id", "id"); // hasMany(Model_Relasi, FK_On_contants, PK_on_users) // relasi 1 to M
    }

}
