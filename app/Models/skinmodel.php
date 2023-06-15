<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class skinmodel extends Model
{
    protected $guarded=[];
    public $incrementing = false;
    protected $keyType='string';

    protected $table = 'skindata';
    protected $fillable = ['user_id', 'bodypart', 'symptom', 'since','cancertype','date', 'accu'];
    public $timestamps = false;

    protected static function boot(){
        parent::boot();
        static::creating(function($model){
            if($model->getKey()==null){
                $model->setAttribute($model->getKeyName(), Str::uuid()->toString());
            }
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

