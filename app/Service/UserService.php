<?php

namespace App\Service;

use App\Enums\RoleEnum;
use App\Models\User;
use Yajra\DataTables\DataTables;

class UserService
{
    public function index()
    {
        $users = User::get();
        $role = RoleEnum::cases();
        if (request()->ajax()) {
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<button class="btn btn-sm btn-transparent" onclick="showEditModal(this)" data-id="' . $row->id . '"><i class="mdi mdi-pencil"></i></button>';
                    $btn = $btn . '<button class="btn btn-sm btn-transparent" onclick="deleteModal(this)" data-id="' . $row->id . '"><i class="mdi mdi-delete text-danger"></i></button>';
                    if ($row->lock == "unlocked") {
                        $btn = $btn . '<button class="btn btn-sm btn-transparent lock-toggle" onclick="lockToggle(this)" data-id="' . $row->id . '"><i class="mdi mdi-lock-outline text-danger"></i></button>';
                    } else {
                        $btn = $btn . '<button class="btn btn-sm btn-transparent lock-toggle" onclick="lockToggle(this)" data-id="' . $row->id . '"><i class="mdi mdi-lock-open-outline text-dark"></i></button>';
                    }

                    return $btn;
                })->make(true);
        }
        return view('users.index', compact('role'));
    }
    public function store(array $data)
    {
        User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'name' => $data['name'],
            'password' => $data['password'],
            'role' => $data['role'],
            'lock' => 'unlocked',
        ]);
    }
    public function show($id)
    {
        $data = User::find($id);
    }
    public function update(array $data, $id)
    {
        if (!$data['password']) {
            # code...
            User::find($id)->update([
                'username' => $data['username'],
                'email' => $data['email'],
                'name' => $data['name'],
                'role' => $data['role'],

            ]);
            return response()->json(['message' => 'User  Berhasil diedit']);
        } else {

            User::find($id)->update([
                'username' => $data['username'],
                'email' => $data['email'],
                'name' => $data['name'],
                'password' => $data['password'],
                'role' => $data['role'],
            ]);
            return response()->json(['message' => 'User  Berhasil diedit']);
        }
    }
    public function delete($id)
    {
        $data = User::find($id);
        $data->delete();
    }
    public function updateLock($user)
    {
        if ($user->lock == 'unlocked') {
            $user->update([
                'lock' => 'locked'
            ]);
            return response()->json(['message' => 'Kunci User Tertutup']);
        } else {
            $user->update([
                'lock' => 'unlocked'
            ]);
            return response()->json(['message' => 'Kunci User terbuka']);
        }
    }
}
