<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = Role::all();

        return $role;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_name' => ['required', 'string', 'unique:roles']
        ]);

        $role = Role::create([
            'role_name' => $request->input('role_name')
        ]);

        $message = " new role created. ";
        $success = $role;
        $notifications_En_Server = " new role created. ";
        $notifications_Fa_Server = "نقش کاربری جدید افزوده شد";
        $responseCode = 201;
        return response()->json([
            'message' => $message,
            'success' => $success,
            "data" => $role->id,
            'NotificationsEnServer' => $notifications_En_Server,
            'NotificationsFaServer' => $notifications_Fa_Server,
        ], $responseCode);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => ['required', 'integer']]
        );
        $validated = $validator->validated();
        $role = Role::findOrFail($validated['id']);

        $message = " selected role ";
        $success = $role;
        $notifications_En_Server = " selected role ";
        $notifications_Fa_Server = " نقش کاربری ";
        $responseCode = 200;
        return response()->json([
            'message' => $message,
            'success' => $success,
            "data" => $role,
            'NotificationsEnServer' => $notifications_En_Server,
            'NotificationsFaServer' => $notifications_Fa_Server,
        ], $responseCode);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // request validate
        $request->validate([
            'role_name' => ['required', 'string']
        ]);

        // id validate
        $validator = Validator::make(
            ['id' => $id],
            ['id' => ['required', 'integer']]
        );
        $validated = $validator->validated();

        $role = Role::findOrFail($validated['id']);

        $role->name = $request->input('role_name');
        $role->save();

        $message = " selected role ";
        $success = $role;
        $notifications_En_Server = " selected role ";
        $notifications_Fa_Server = " نقش کاربری ";
        $responseCode = 200;
        return response()->json([
            'message' => $message,
            'success' => $success,
            "data" => $role->id,
            'NotificationsEnServer' => $notifications_En_Server,
            'NotificationsFaServer' => $notifications_Fa_Server,
        ], $responseCode);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // id validate
        $validator = Validator::make(
            ['id' => $id],
            ['id' => ['required', 'integer']]
        );
        $validated = $validator->validated();

        $role = Role::find($validated['id']);
        if ($role) {
            $role->delete();
            $message = "Role " . $validated['id'] . " deleted.";
            $success = $validated['id'];
            $notifications_En_Server = "role " . $validated['id'] . " deleted.";
            $notifications_Fa_Server = "نقش " . $validated['id'] . " حذف شد";
            return response()->json(
                [
                    'message' => $message,
                    'success' => $success,
                    'NotificationsEnServer' => $notifications_En_Server,
                    'NotificationsFaServer' => $notifications_Fa_Server,
                ],
                200
            ); // 204 (No Content) - 202 (Accepted)
        }
        else {
            return response()->json(
                [
                    "errors" => [
                        "role" => "role " . $validated['id'] . " Not Found!",
                    ],
                ],
                404
            );
        }
    }

    public function allUser(Request $request, string $id)
    {
        // id validate
        $validator = Validator::make(
            ['id' => $id],
            ['id' => ['required', 'integer']]
        );
        $validated = $validator->validated();
        $role = Role::find($validated['id']);

        $users = Role::find($validated['id'])->users()->get();


        $message = "users of role " . $validated['id'] . " .";
        $success = $role;
        $notifications_En_Server = "users of role " . $validated['id'] . " .";
        $notifications_Fa_Server = "کاربران صاحب نقش " . $validated['id'] . " . ";
        return response()->json(
            [
                'message' => $message,
                'success' => $success,
                "data" => $users,
                'NotificationsEnServer' => $notifications_En_Server,
                'NotificationsFaServer' => $notifications_Fa_Server,
            ],
            200
        ); // 204 (No Content) - 202 (Accepted)
    }

    public function attachRole(Request $request, string $id)
    {
        // id validate role
        $validator = Validator::make(
            ['id' => $id],
            ['id' => ['required', 'integer']]
        );
        $validated = $validator->validated();
        $role = Role::findOrFail($validated['id']);

        // request validate users
        $request->validate([
                'users' => ['nullable'],
                'users.*' => ['nullable', 'integer', 'exists:users,id'],
            ]
        );

        $users = User::whereIn('id', $request->users)
            ->select(['id', 'name', 'email'])
            ->get();
        foreach ($users as $key => $user) {
            if($user->hasRole($role->name)){
                unset($users[$key]);
            }
        }
        $role->users()->attach($users);

        $message = "Role " . $validated['id'] . " assigned.";
        $success = $role;
        $notifications_En_Server = "role " . $validated['id'] . " assigned.";
        $notifications_Fa_Server = "نقش کاربری " . $validated['id'] . " به کابران اضافه شد ";
        return response()->json(
            [
                'message' => $message,
                'success' => $success,
                "data" => $users,
                'NotificationsEnServer' => $notifications_En_Server,
                'NotificationsFaServer' => $notifications_Fa_Server,
            ],
            200
        ); // 204 (No Content) - 202 (Accepted)
    }

    public function detachRole(Request $request, string $id)
    {
        // id validate role
        $validator = Validator::make(
            ['id' => $id],
            ['id' => ['required', 'integer']]
        );
        $validated = $validator->validated();
        $role = Role::findOrFail($validated['id']);

        // request validate users
        $request->validate([
                'users' => ['nullable'],
                'users.*' => ['nullable', 'integer', 'exists:users,id'],
            ]
        );
        $users = User::whereIn('id', $request->users)
            ->select(['id', 'name', 'email'])
            ->get();
        foreach ($users as $user) {
            if(!$user->hasRole($role->name)){
                return 'not';
            }
        }

        $role->users()->detach($users);

        $message = " Role " . $validated['id'] . " assigned.";
        $success = $users;
        $notifications_En_Server = "role " . $validated['id'] . " assigned.";
        $notifications_Fa_Server = "نقش کاربری " . $validated['id'] . " از کابران حذف شد ";
        return response()->json(
            [
                'message' => $message,
                'success' => $success,
                "data" => $validated['id'],
                'NotificationsEnServer' => $notifications_En_Server,
                'NotificationsFaServer' => $notifications_Fa_Server,
            ],
            200
        ); // 204 (No Content) - 202 (Accepted)

    }
}
