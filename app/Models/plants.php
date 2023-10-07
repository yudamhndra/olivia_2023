<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class plants extends Model
{
    use HasFactory;
    protected $table = 'plants';
    protected $primaryKey = 'id';

    protected $fillable = ['id_users', 'plant_img','plant_name', 'condition', 'disease'];


}
