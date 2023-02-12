<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actions extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'actions';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'action_id';

    public function Users()
    {
        return $this->belongsTo(Users::class, 'user_id', 'user_id');
    }
}