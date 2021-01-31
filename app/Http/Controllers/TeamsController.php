<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Http\Resources\TeamResource;
use App\Http\Resources\RankingsResource;
class TeamsController extends Controller
{
    public function create_team(Request $request){
        Team::create([
            'name'=> $request->input('team_name')
        ]);
        
        return json_encode([
            "team_name"=>$request->input('team_name')
        ]);
    }

    public function create_match(Request $request){
        $team1=Team::findOrFail($request->input('team_a_id'));
        $team2=Team::findOrFail($request->input('team_b_id'));
        if($request->input('team_a_score') > $request->input('team_b_score')){
            $team1->won=$team1->won + 1;
            $team2->lost=$team2->lost + 1;
        }elseif($request->input('team_a_score') < $request->input('team_b_score')){
            $team2->won=$team1->won + 1;
            $team1->lost=$team1->lost + 1;
        }else{
            $team2->drawn=$team1->drawn + 1;
            $team1->drawn=$team1->drawn + 1;
        }
        $team1->save();
        $team2->save();
        return json_encode([
            "team_a_id"=>$team1->id,
            "team_b_id"=>$team2->id,
            "team_a_score"=>$request->input('team_a_score'),
            "team_b_score"=>$request->input('team_b_score'),
        ]);
    }
    public function get_teams(){
        $teams=Team::paginate(10);
        return TeamResource::collection($teams);
    }
    public function get_rankings(){
        $rankings=DB::table('teams')
        ->select('id','name','won','lost','drawn')
        ->orderBy(DB::raw("`won` + `drawn`"), 'desc')
        ->paginate(5);
        $position=($rankings->currentPage() - 1) * $rankings->perPage() + 1;
        $new_rankings=[];
        foreach($rankings as $rank){
            $rank->position=$position;
            $rank->played=$rank->won+$rank->lost+$rank->drawn;
            $rank->points=($rank->won*3+$rank->drawn);
            $position++;
        }
        return RankingsResource::collection($rankings);

    }
}
