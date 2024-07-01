<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = "users"; // attribute $table binding model dengan table db
    protected $primaryKey = "id"; // attribute $primaryKey PK dari table db
    protected $keyType = "int"; // attribute $keyType type data PK dari table db
    public $incrementing = true; // attribute $incrementing type data PK auto_increment ketika data di tambah
    public $timestamps = true; // attribute $timestamps implementasi created_at dan updated_at dari laravel
}
