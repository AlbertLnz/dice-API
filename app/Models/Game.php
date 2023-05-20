<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    public $dice1;
    public $dice2;
    public $numberResult;
    public $textResult;

    public function __construct(){
        $this->dice1 = $this->throwDice();
        $this->dice2 = $this->throwDice();
        $this->numberResult = $this->dice1 + $this->dice2;
        $this->textResult = $this->stringResult();
    }

    public function throwDice():int{
        return rand(1,6);
    }

    public function stringResult():string{
        if($this->numberResult == 7){
            return "Win";
        }else{
            return "Lose";
        }
    }

    
    //One To Many Inverse Relationship
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    
}
