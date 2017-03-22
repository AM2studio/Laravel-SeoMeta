<?php

namespace AM2Studio\Laravel\SeoMeta;

use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    protected $fillable = ['model_id', 'model_type', 'key', 'value'];

    public function model()
    {
        return $this->belongsTo($this->model_type, 'model_id');
    }
}
