<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\backend\State;
use Illuminate\Http\Request;

use \Cviebrock\EloquentSluggable\Services\SlugService;

class StateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($id)
    {
        $states = State::where('country_id',$id)->get();
        return view('backend.states.index', compact('states'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
       $request->validate([
        'name' => 'required|unique:states,name,NULL,id,deleted_at,NULL',
       ]);
       $state = new State;
       $state->name = strtoupper($request->name);
       $state->country_id = $request->country_id;
       if($state->save()){

          //activity Log By Naresh
          $log = ['module' => 'State Master', 'action' => 'New State Added', 'description' => 'New state Added: ' . $request->state_name];
          captureActivity($log);

        return redirect()->route('admin.state',['id'=>$request->country_id])->with('success','New state Created');
       }else{
        return redirect()->route('admin.state')->with('error','Failed ro create state');
       }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */

     public function edit($id){
        $state = State::where('id',$id)->first();
        $country_id = request('id');
        if($state){
        return view('backend.states.edit', compact('state'));
        }else{
            return redirect()->route('admin.state')->with('error','State Not found');
        }

     }

     public function update(Request $request){
        $request->validate([
            'name'=>'required',
        ]);

        $exist = State::where('id','!=', $request->id)->where('name',$request->name)->first();
        if($exist){
            //state name already preset with different id
            return redirect()->route('admin.state.edit',$request->id)->with('error','State name already present');
        }

        $state = State::where('id', $request->id)->first();
        $original_value = $state->name;
        $state->name = strtoupper($request->name);
        if($state->save()){

            if ($state->getChanges()) {
                //activity Log By Naresh
                $new_changes = $state->getChanges();
                unset($new_changes['updated_at']);
                $log = ['module' => 'State Master', 'action' => 'State Updated', 'description' => 'State Updated : ' . $original_value];
                captureActivityupdate($new_changes, $log);
            }

            return redirect()->route('admin.state.edit',$request->id)->with('success','State Name has been updated');
           }else{
            return redirect()->route('admin.state.edit',$request->id)->with('error','Failed to update State');
           }

     }

     public function delete($id){
        $state = State::where('id', $id)->first();
        if($state){
            if($state->delete()){
                //activity Log by Naresh
                  $log = ['module' => 'State Master', 'action' => 'State Deleted', 'description' => 'State Deleted: ' . $state->state_name];
                  captureActivity($log);
                return redirect()->back()->with('info','State has been deleted');
            }else{
                return redirect()->back()->with('error','failed to delete state');
            }
        }else{
            return redirect()->back()->with('error','State Not found');
        }
     }



}
