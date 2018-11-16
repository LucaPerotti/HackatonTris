<?php

namespace App\Http\Controllers;

use App\Match;
use App\Services\TrisWinnerChecker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\DB;

class MatchController extends Controller
{
    public function addMatch(Request $request){

        $opponent_id = $request->opponent_id;
        $user = Auth::user();
        $match = new Match;

        $match->user_id = $user->getAuthIdentifier();
        $match->opponent_id = $opponent_id;
        $match->boardstate = json_encode(array(0,0,0,0,0,0,0,0,0));

        $match->save();
    }

    public function invites(){
        $user = Auth::user()->getAuthIdentifier();
        $matchquery = DB::table('matches')->where('opponent_id', $user)
                                                ->where('status', '=', 0);
        $match = $matchquery->get();
        $id = $matchquery->value('user_id');
        $userquery = DB::table('users')->where('id', $id)->select('id', 'username')->get();

        return json_encode(array("match"=>$match,"user"=>$userquery));
    }

    public function accepted(){
        $user = Auth::user()->getAuthIdentifier();
        $matchquery = DB::table('matches')->where('user_id', $user)
            ->where('status', '=', 1);
        $match = $matchquery->get();
        $id = $matchquery->value('opponent_id');
        $userquery = DB::table('users')->where('id', $id)->select('id', 'username')->get();

        return json_encode(array("match"=>$match,"user"=>$userquery));
    }

    public function response(Request $request, Match $match){

        if($request->response==1){
            $match->status = 1;
            $match->save();
        }
        else if($request->response==0){
            $match->delete();
        }
    }


    //$pos è un'intero che corrisponde alla casella cliccata

    public function newMove(Request $request, Match $match){

        $pos = $request->pos;
        $user = Auth::user()->getAuthIdentifier();
        $boardstate = json_decode($match->boardstate);
        if ($match->status == 1) {
            if ($user == $match->user_id && $match->turn%2 == 0) {
                $match->turn++;
                $boardstate[$pos] = 1;

            } else if ($user == $match->opponent_id && ($match->turn%2) == 1) {
                $match->turn++;
                $boardstate[$pos] = 2;
            } else {
                return -1;
            }
        }
        else {
            return -2;
        }

        $match->boardstate = json_encode($boardstate);
        $match->save();

        $winner = $this->checkWinner($boardstate, $match);

        return json_encode(array("winner"=>$winner, "boardstate"=>$match->boardstate));
    }

    // il return di checkWinner ha 4 possibilità: l'id del vincitore, "draw" oppure null, valore di default che indica che la partita continua

    public function checkWinner($move, $match) {

        $checker = new TrisWinnerChecker() ;
        $user1 = User::find($match->user_id);
        $user2 = User::find($match->opponent_id);

        if($checker->checkWin($move)==1){
            $match->winner_id = $match->user_id;
            $user1->wins ++;
            $user1->score = $user1->score+3;
            $user2->losses ++;
            if($user2->score > 0){
                $user2->score --;
            }
            $match->status = 2;
        }
        else if($checker->checkWin($move)==2){
            $match->winner_id = $match->opponent_id;
            $user2->wins ++;
            $user2->score = $user2->score+3;
            $user1->losses ++;
            if($user1->score > 0){
                $user1->score --;
            }
            $match->status = 2;
        }
        else if ($match->turn==9){
            $match->winner_id = 0;
            $user1->draws ++;
            $user2->draws ++;
            $user1->score ++;
            $user2->score ++;
            $match->status = 2;
        }
        $match->save();
        $user1->save();
        $user2->save();

        return $match->winner_id;
    }
}
