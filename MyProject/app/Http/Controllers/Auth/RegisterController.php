<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\ChatRoom;
use App\ChatRoomUser;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'profile-image' => ['required', 'image'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $my_uuid = (string) Str::uuid();
        Storage::putFileAs(
            'public/images', $data['profile-image'], $my_uuid . '.jpg'
        );
        $my_user = User::create([
            'id' => $my_uuid,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'api_token' => Str::uuid()
        ]);

        $users = User::select('id')->where('id', '!=', $my_uuid)->get();

        foreach ($users as $user) {
            $room = [
                'id' => (string) Str::uuid(),
                'group_name' => null,
                'is_group' => false,
                'admin' => null,
                'created_at' => Carbon::now('Asia/Tokyo')
            ];

            ChatRoom::create($room);
            ChatRoomUser::create([
                'room_id' => $room['id'],
                'user_id' => $user->id,
                'checked_at' => Carbon::now('Asia/Tokyo')
            ]);
            ChatRoomUser::create([
                'room_id' => $room['id'],
                'user_id' => $my_uuid,
                'checked_at' => Carbon::now('Asia/Tokyo')
            ]);
        }

        return $my_user;
    }
}
