<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function showForm()
    {
        $users = User::with('role')->orderBy('id', 'desc')->get();
        $roles = Role::get();
        
        return view('user', compact('users','roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => [
                'required',
                'digits:10',
                'regex:/^[7-9]\d{9}$/',
            ],
            'description' => 'required|string',
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role_id' => 'required|integer',
        ], [
            'phone.regex' => 'The phone number must be a valid Indian number starting with 7, 8, or 9.',
        ], [
            'name' => 'Full Name',
            'email' => 'Email Address',
            'phone' => 'Phone Number',
            'description' => 'Description',
            'profile_image' => 'Profile Picture',
            'role_id' => 'User Role',
        ]);


        $data = $request->except('profile_image');

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/profiles'), $filename);
            $data['profile_image'] = $filename;
        }

        User::create($data);
        $users = User::with('role')->orderBy('id', 'desc')->get();
        $html = '';
        foreach ($users as $k => $user) {
            $html .= '<tr>' .
                '<td>' . (++$k) . '</td>' .
                '<td>' .
                    ($user->profile_image ? 
                        '<img src="' . asset('images/profiles/' . $user->profile_image) . '" alt="Image" width="50">' : 
                        '<p>No Image</p>'
                    ) .
                '</td>' .
                '<td>' . ($user->name ?? '') . '</td>' .
                '<td>' . ($user->email ?? '') . '</td>' .
                '<td>' . ($user->phone ?? '') . '</td>' .
                '<td>' . ($user->role->display_name ?? '') . '</td>' .
            '</tr>';
        }
        if ($request->ajax()) {
            return response()->json(['message' => 'Car created successfully!','html'=>$html], 200);
        } else {
            return redirect()->back()->with('success', 'Car created successfully!');
        }
    }
}
