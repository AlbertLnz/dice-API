<?php

namespace App\Http\Services;

use App\Models\Game;

class UserService{

    public function percentageWinRate($allGamesFiltred, $userPosition){

        $count = Game::where('user_id', $userPosition+1)->count(); //15
        $percentageWin=0;

        for($i=0 ; $i<$count ; $i++){

            if($allGamesFiltred[$i]['numberResult'] == 7){
                $percentageWin++;
            }
        }

        return number_format(($percentageWin/$count)*100, 4);
    }

}

?>