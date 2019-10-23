<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingDoc extends Model
{
    public $table = "training_doc";

    public static function getTrainingDocCount($training_id){

      $query = TrainingDoc::query();
      $query = $query->where('training_id',$training_id);
      $response = $query->count();

      return $response;
  	}
}
