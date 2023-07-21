<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Invite;
use App\Models\History;
class AuthController extends Controller
{
    public function update(Request $request)
    {
        $employee=User::where('id',$request->id)->first();
        $employee->update([
            'phone'=>$request->phone,
            'name'=>$request->name,
            'address'=>$request->address,
            'birth_date'=>$request->birth_date,
        ]);

        return response([$employee],201);
    }

    public function register(Request $request){

        $fields = $request->validate([
            'name' =>'required|string',
            'email' => 'required|string|unique:users,email',
            'password'=>'required|string',
            'role'=>'required|string'
        ]);

        $user= User::create([
            'name'=>$fields['name'],
            'email'=>$fields['email'],
            'company_id'=>$request['company_id']?$request['company_id']:null,
            'phone'=>$request['phone']?$request['phone']:null,
            'address'=>$request['address']?$request['address']:null,
            'birthdate'=>$request['address'?$request['address']:null],
            'role'=>$fields['role'],
            'password'=>bcrypt($fields['password'])
        ]);

        $token = $user->createToken('appToken')->plainTextToken;
        $response = [
            'user'=>$user,
            'token'=>$token
        ];
        //update and status history if role is employee AKA confirms the invitation
        if ($request['role'] == "employee") {
            $date = date('d/m/Y H:i', time());
            $invite = Invite::with('company')->where('id',$request['invite_id'])->first();

            $invite->update(['state'=>'confirmed']);
            $companyName = $invite->company->name;
            History::create([
                'message'=>"$date /  [$user->name] has Confirmed the invite to Join [$companyName]"
            ]);
        }

        return response($response,201);
    }
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password'=>'required|string'
        ]);
        $user= User::with('invites')->where('email',$fields['email'])->first();
        if(!$user || !Hash::check($fields['password'],$user->password)){
            return response([
                'message'=>'Invalid credentials'
            ],401);
        }
        $token = $user->createToken('appToken')->plainTextToken;
        $response = [
            'user'=>$user,
            'token'=>$token
        ];

        return response($response,201);

    }
    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return [
            'message'=>'logged out'
        ];
    }
    public function invite(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'company' => 'required|string',
            'company_id' => 'required|integer',

        ]);
        $user = auth()->user();
        //create new invitation
        $existingInvite=Invite::where([
            'company_id'=>$fields['company_id'],
            'employee_name'=>$fields['name'],
            'user_id'=>$user->id,
        ])->first();

        if($existingInvite){
            return response(['message'=>'employee already invited'],201);
        }
        $invite = Invite::create([
            'company_id'=>$fields['company_id'],
            'employee_name'=>$fields['name'],
            'user_id'=>$user->id,
        ]);
        //create new history
        $employeeName = $fields['name'];
        $companyName = $fields['company'];
        $to = $fields['email'];
        $date = date('d/m/Y H:i', time());

         History::create([
            'message'=>"$date / Admin [$user->name] Invited [$employeeName] to Join [$companyName]"
        ]);

        //send email
        Mail::send('emails.invite', [
            'user' => $user,
            'company' => $request->company,
            'invited' => $request->name,
            'company_id'=>$request->company_id,
            'invitedMail'=>$request->email,
            'invitation_id'=>$invite->id
        ], function ($message) use($to) {

            $message->to( $to )
                ->subject("Invitation 👋👋");
        });
        return response(['message'=>'invitation email sent successfully!'], 201);
    }


    public function currentUserInvites(){
            $user=auth()->user();
            $user=User::with('invites.company')->where('id',$user->id)->first();
        return response ($user,200);
    }
    public function getUsers()
    {
        return response(User::with('invites')->where('role','Admin')->get(),200);
    }

    public function registerEmployee(Request $request){
        //validate inputs
        $fields = $request->validate([
            'name' =>'required|string',
            'email' => 'required|string|unique:users,email',
            'password'=>'required|string'
        ]);

        $user= User::create([
            'name'=>$fields['name'],
            'email'=>$fields['email'],
            'role'=>'employee',
            'password'=>bcrypt($fields['password'])
        ]);

        $token = $user->createToken('appToken')->plainTextToken;
        $response = [
            'user'=>$user,
            'token'=>$token
        ];

        return response($response,201);
        //create new employee
        //update invitation state
        //add history

    }
    public function getHistory( Request $request )
    {
        return response(History::all(),200);
    }

    public function cancelInvite($id)
    {

        $invite = Invite::where('id',$id)->first();
        $date = date('d/m/Y H:i', time());
        $employeeName = $invite->employee_name;
        $user = auth()->user();
        History::create([
            'message'=>"$date /  [$user->name] has cancelled  [$employeeName] invitation"
        ]);
        Invite::where('id',$id)->delete();

        return response(['message'=>'invitation has been canceled'],201);

    }
}
