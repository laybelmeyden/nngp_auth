<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Validators\Failure;

class MainController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('welcome', compact('users'));
    }
    public function createUser(Request $request){
        $request->validate([
            'email' => 'required|unique:users|max:255',
            'password' => 'required|min:8',
        ]);
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->assignRole('user');
        $user->save();
        $to_email = env('MAIL_USERNAME');
        $to_name = 'test';
        $data = array('email' => $user->email);
        \Mail::send('email.mailTest', $data, function ($message) use ($to_email, $data, $to_name) {
            $message->from($to_email);
            $message->to($data['email'], $to_name)->subject('Статус проекта');
        });
        return redirect()->back()->withSuccess('Пользователь успешно добавлен!');
    }

    public function deletedUser(){
        DB::table('users')->delete();
        return redirect('/');
    }
    public function store(Request $request){
        $file = $request->file('file');
        $import = new UsersImport;
        $import->import($file);
        if($import->failures()->isNotEmpty()){
            return redirect()->back()->withFailures($import->failures());
        }
       
        return redirect()->back()->withSuccess('Пользователь успешно добавлен из Excel!');
    }
}
