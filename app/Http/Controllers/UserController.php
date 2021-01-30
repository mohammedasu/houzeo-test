<?php

namespace App\Http\Controllers;

use Event;
use App\User;
use App\Events\UserCreated;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function creat_user(Request $request)
    {
		$data = $request->all();
		$validate = $this->formValidate($request);
		if ($validate->fails()) 
		{
			return response()->json(['success' => false, 'error' => true, 'message' => $validate->errors()->all()],422);
        }
		
		$country = $this->getCountryName($request);
		if(empty($country))
		{
			$country = null;
		}
		
		$data['country'] = $country;
		$user = User::create($data);
		event(new UserCreated($data));
        
		return response()->json(['success' => true, 'error' => false, 'message' => 'User Create Successfully'],200);
    }
	
	public function formValidate(Request $request)
	{
		return Validator::make($request->all(), [
			'name' 	 	=> 'required',
			'email'  	=> 'required|email|unique:users',
			'contact'	=> 'required|digits:10',
			'address'  	=> 'required',
			'city'  	=> 'required',
			'state'  	=> 'required',
			'zip'  		=> 'required',
		]);
	}
	
	public function getCountryName(Request $request)
	{
		$country = '';
		$auth_id = '37b9db49-f915-546e-268a-102ec5a11082';
		$auth_token = 'sPcN8zU4lfXsJmB3yeHr';
		$url = "https://us-zipcode.api.smartystreets.com/lookup?auth-id=".$auth_id."&auth-token=".$auth_token;
		$field = array([
			"city" => $request->city,
			"state" => $request->state,
			"zip" => $request->zip,
		]);
		$json = json_encode($field);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		curl_close ($ch);
		
		if (!empty($server_output)) 
		{ 
			foreach(json_decode($server_output) as $key => $value)
			{
				$country = $value->zipcodes[0]->county_name;
			}
		}

		return $country;
	}
}
