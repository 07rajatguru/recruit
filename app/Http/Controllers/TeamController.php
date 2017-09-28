<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Team;
use App\TeamMates;
use Illuminate\Support\Facades\Input;

class TeamController extends Controller
{
    public function index(){
        $teams = \DB::table('team')->get();

        $team_response = array();
        $i=0;
        foreach ($teams as $key=>$value){
            $users = \DB::table('team_mates')
                    ->join('users',"users.id","=","team_mates.user_id")
                    ->where("team_mates.team_id",$value->id)
                    ->select("users.*")
                    ->get();


            $j = 0;
            foreach ($users as $key1=>$value1){
                $team_response[$value->id]['team_name'] = $value->team_name;
                $team_response[$value->id]['users'][$j] = $value1->name;
                $i++;
                $j++;
            }
        }

        return view('adminlte::team.index',compact('team_response'));
    }

    public function create(){

        $action = 'add';
        $users = User::getAllUsers();

        return view('adminlte::team.create',compact('action','users'));
    }

    public function store(Request $request){

        $user_id = \Auth::user()->id;

        $input = $request->all();

        $users = $input['user_ids'];

        $team = new Team();
        $team->team_name = $input['team_name'];
        $team->created_by = $user_id;

        if($team->save()){

            $team_id = $team->id;

            if(isset($users) && sizeof($users)>0){
                foreach ($users as $key=>$value){
                    $team_mates = new TeamMates();
                    $team_mates->team_id = $team_id;
                    $team_mates->user_id = $value;
                    $team_mates->save();
                }
                return redirect()->route('team.index')->with('success','Team Created Successfully');
            }
        }
        else{
            return redirect('team/create')->withInput(Input::all())->withErrors ( $team->errors() );
        }
    }

    public function show(){

    }

    public function edit($id){

        $team = Team::find($id);

        $users = User::getAllUsers();

        $team_mates = \DB::table('team_mates')
            ->join('users',"users.id","=","team_mates.user_id")
            ->where("team_mates.team_id",$id)
            ->pluck("users.id")
            ->toArray();

        $action = "edit" ;


        return view('adminlte::team.edit',compact('action','users','team','team_mates'));
    }

    public function update(Request $request, $id){

        $team = Team :: find($id);
        $team->team_name = $request->input('team_name');
        $users = $request->input('user_ids');

        if($team->save()){

            $team_id = $team->id;

            if(isset($users) && sizeof($users)>0){
                \DB::table("team_mates")->where("team_mates.team_id",$id)->delete();

                foreach ($users as $key=>$value){
                    $team_mates = new TeamMates();
                    $team_mates->team_id = $team_id;
                    $team_mates->user_id = $value;
                    $team_mates->save();
                }
                return redirect()->route('team.index')->with('success','Team Updated Successfully');
            }
            else{
                \DB::table("team_mates")->where("team_mates.team_id",$id)->delete();
            }
        }
        else{
            return redirect('team/'.$id.'/edit')->withInput(Input::all())->withErrors ( $team->errors() );
        }
    }

    public function destroy(){

    }
}
