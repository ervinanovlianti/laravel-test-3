<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class UserController extends Controller
{
    public function index()
    {
        // TASK: turn this SQL query into Eloquent
        // select * from users
        //   where email_verified_at is not null
        //   order by created_at desc
        //   limit 3
        $users = User::whereNotNull('email_verified_at')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('users.index', compact('users'));
    }

    public function show($userId)
    {
        // TASK: find user by $userId or show "404 not found" page
        $user = User::findOrFail($userId);


        return view('users.show', compact('user'));
    }

    public function check_create($name, $email)
    {
        // TASK: find a user by $name and $email
        //   if not found, create a user with $name, $email and random password
        $user = User::where('name', $name)->where('email', $email)->first();
        // Jika user tidak ditemukan, buat user baru dengan $name, $email, dan password acak
        if (!$user) {
            $password = Str::random(10); // Password acak, Anda bisa menyesuaikan panjangnya

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
            ]);

            // Lakukan tindakan tambahan jika diperlukan, misalnya, mengirim email notifikasi

            return view('users.show', compact('user'));
        }

        return view('users.show', compact('user'));
    }

    public function check_update($name, $email)
    {
        // TASK: find a user by $name and update it with $email
        //   if not found, create a user with $name, $email and random password
        $user = User::where('name', $name)->first(); // updated or created user
        // Jika user tidak ditemukan, buat user baru dengan $name, $email, dan password acak
        if (!$user) {
            $password = bcrypt(12341234); // Password acak, Anda bisa menyesuaikan panjangnya

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
            ]);
        } else {
            // Jika user ditemukan, update email
            $user->email = $email;
            $user->save();
        }

        return view('users.show', compact('user'));
    }

    public function destroy(Request $request)
    {
        // TASK: delete multiple users by their IDs
        // SQL: delete from users where id in ($request->users)
        // $request->users is an array of IDs, ex. [1, 2, 3]

        // Insert Eloquent statement here
        $user = User::whereIn('id', $request->users)->delete();

        return redirect('/')->with('success', 'Users deleted');
    }

    public function only_active()
    {
        // TASK: That "active()" doesn't exist at the moment.
        //   Create this scope to filter "where email_verified_at is not null"
        $users = User::whereNotNull('email_verified_at')
            ->get();

        return view('users.index', compact('users'));
    }
}
