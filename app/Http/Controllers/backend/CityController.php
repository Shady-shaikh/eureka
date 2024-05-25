<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;

use App\Models\backend\City;
use App\Models\backend\State;
use Illuminate\Http\Request;


class CityController extends Controller
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
        $cities = City::where('state_id',$id)->get();
        return view('backend.city.index', compact('cities','id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {

       $request->validate([
        // 'city_name'=>'required',
        'city_name' => 'required|unique:cities,city_name,NULL,city_id,deleted_at,NULL',
        'state_id'=>'required'
       ]);

       $city = new City();
       $city->city_name = strtoupper($request->city_name);
       $city->state_id = $request->state_id;
       if($city->save()){

        //Logs by Naresh
        $log_state = State::where('id',$request->state_id)->first();
        $log_state_name = '';
        if(isset($log_state->state_name)){
            $log_state_name = $log_state->state_name;
         }
         $log = ['module' => 'City Master', 'action' => 'New City Added', 'description' => 'New City Added: ' . $request->city_name .'. under State : '.$log_state_name];
         captureActivity($log);

        // logs By Naresh
        return redirect()->route('admin.city',$request->state_id)->with('success','New city Created');
       }else{
        return redirect()->route('admin.city',$request->state_id)->with('error','Failed ro create city');
       }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */

     public function edit($id){

        $city = City::where('city_id',$id)->first();
        if($city){
        return view('backend.city.edit', compact('city'));
        }else{
            return redirect()->back()->with('error','City Not found');
        }
     }

     public function update(Request $request)
     {
         $request->validate([
             'city_id'=>'required',
             'city_name'=>'required',
            ]);

            $exist = City::where('city_id','!=', $request->city_id)->where('city_name',$request->city_name)->first();
            if($exist){
                //state name already preset with different id
                return redirect()->route('admin.city.master',[$exist->state_id])->with('error','City name already present');
            }

            $city = City::where('city_id', $request->city_id)->first();
            $original_value = (isset($city->city_name)?$city->city_name:'');
            $city->city_name = strtoupper($request->city_name);
            if($city->save()){

                if ($city->getChanges()) {
                    //activity Log By Naresh
                    $log_state = State::where('id',$city->state_id)->first();
                    $log_state_name ='';
                    if(isset($log_state->state_name)){
                        $log_state_name = $log_state->state_name;
                     }
                    $new_changes = $city->getChanges();
                    unset($new_changes['updated_at']);
                    $log = ['module' => 'City Master', 'action' => 'City Updated', 'description' => 'City Updated : ' . $original_value.' under State : '.$log_state_name];
                    captureActivityupdate($new_changes, $log);
                }

                return redirect()->back()->with('success','City Name has been updated');
            }else{
                return redirect()->back()->with('error','Failed to update city name');
            }
        }


        public function delete($id){
            $city =  City::where('city_id', $id)->first();
            if($city){
                if($city->delete()){

                    //activity Log By Naresh
                    $log_state = State::where('id',$city->state_id)->first();
                    $log_state_name ='';
                    if(isset($log_state->state_name)){
                        $log_state_name = $log_state->state_name;
                     }
                      //activity Log By Naresh
                        $log = ['module' => 'City Master', 'action' => 'City Master', 'description' => 'City Deleted Deleted: ' . $city->city_name.' , from state : '.$log_state_name];
                        captureActivity($log);
                    return redirect()->route('admin.city',[$city->state_id])->with('info','City has been deleted');
                }else{
                    return redirect()->route('admin.city',[$city->state_id])->with('error','failed to delete city');
                }
            }else{
                return redirect()->back()->with('error','City Not found');
            }
        }




}
