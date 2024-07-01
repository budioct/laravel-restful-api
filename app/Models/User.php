<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model implements Authenticatable
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

    // getAuthIdentifierName() // ingin tahu kira kira Identifier Name atau id untuk user itu apa
    public function getAuthIdentifierName()
    {
        return $this->username; // ambil dari column username
    }

    // getAuthIdentifier() // username nya itu siapa
    public function getAuthIdentifier()
    {

       return $this->username; // ambil dari column username
    }

    // getAuthPassword // mendapatkan value passwordnya
    public function getAuthPassword()
    {
        $this->password; // ambil dari column password
    }

    // dibawah ini digunakan untuk OAuth2
    public function getRememberToken()
    {
        return $this->token;
    }

    public function setRememberToken($value)
    {
        return $this->token = $value;
    }

    public function getRememberTokenName()
    {
        return "token";
    }
}
