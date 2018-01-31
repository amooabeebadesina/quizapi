<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{

    protected $hidden = ['created_at', 'updated_at', 'question_id'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

}
