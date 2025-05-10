<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Service\UserService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(private UserService $userService)
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return $this->userService->index();
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $data = $request->validated();
        try {
            $this->userService->store($data);
            return response()->json(['message' => 'User Baru Berhasil dibuat']);
        } catch (\Exception $error) {
            return response()->json(['message' => $error->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        return response()->json(['data' => $this->userService->show($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $data = $request->validated();
        try {
            return $this->userService->update($data, $id);
        } catch (\Exception $error) {
            return response()->json(['message' => $error->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->userService->delete($id);
        return response()->json(['message' => 'Data berhasil di hapus']);
    }

    public function updateLock(Request $request, $id)
    {
        $user = User::find($id);

        try {
            return $this->userService->updateLock($user);
        } catch (\Exception $error) {
            return response()->json(['message' => $error->getMessage()]);
        }
    }
}
