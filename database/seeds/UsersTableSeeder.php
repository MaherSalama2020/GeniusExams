<?php

use Illuminate\Database\Seeder;
use App\User;
use App\VerifyUser;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = new User;
        $user->name = "Maher Salama";
        $user->email = "maher_se84@hotmail.com";
        $user->country="Algeria";
        $user->verified=true;
        $user->password = bcrypt('maher');
        $user->is_admin = true;
        $user->save();
        $verifyuser= new VerifyUser;
        $verifyuser->user_id=$user->id;
        $verifyuser->token=sha1(time());
        $verifyuser->save();

        $user = new User;
        $user->name = "Admin";
        $user->email = "admin@test.com";
        $user->country="Belgium";
        $user->verified=true;
        $user->password = bcrypt('admin');
        $user->is_admin = true;
        $user->save();
        $verifyuser= new VerifyUser;
        $verifyuser->user_id=$user->id;
        $verifyuser->token=sha1(time());
        $verifyuser->save();

        $user = new User;
        $user->name = "vvvvvv";
        $user->email = "vv@vv.vv";
        $user->country="China";
        $user->verified=true;
        $user->password = bcrypt('vvvvvv');
        $user->is_admin = false;
        $user->save();
        $verifyuser= new VerifyUser;
        $verifyuser->user_id=$user->id;
        $verifyuser->token=sha1(time());
        $verifyuser->save();
    }
}
