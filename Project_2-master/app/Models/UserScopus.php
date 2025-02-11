<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserScopus extends Model
{
    use HasFactory;

    protected $table = 'user_scopus'; // ชื่อตารางในฐานข้อมูล

    protected $primaryKey = 'user_ID'; // กำหนด Primary Key เป็น User_ID

    public $incrementing = false; // ปิดการ Auto Increment

    protected $fillable = [
        'user_ID',
        'scopus_ID',
        'citation',
        'h_index',
        'i10_index',
        'citation_5years_ago',
        'h_index_5years_ago',
        'i10_index_5years_ago'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}