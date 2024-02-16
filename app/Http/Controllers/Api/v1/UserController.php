<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();

        return $user;

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
        $user = User::findOrFail($validated['id']);

        $message = " selected user ";
        $success = $user;
        $notifications_En_Server = " selected role ";
        $notifications_Fa_Server = " نقش کاربری ";
        $responseCode = 200;
        return response()->json([
            'message' => $message,
            'success' => $success,
            "data" => $user,
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

        $user = User::find($validated['id']);
        if ($user) {
            $user->delete();
            $message = "Role " . $validated['id'] . " deleted.";
            $success = $validated['id'];
            $notifications_En_Server = "user " . $validated['id'] . " deleted.";
            $notifications_Fa_Server = "کاربر " . $validated['id'] . " حذف شد";
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
                        "user" => "user " . $validated['id'] . " Not Found!",
                    ],
                ],
                404
            );
        }
    }

    public function allRoles(Request $request, string $id)
    {
        // id validate user
        $validator = Validator::make(
            ['id' => $id],
            ['id' => ['required', 'integer']]
        );
        $validated = $validator->validated();
        $user = User::find($validated['id']);

        $roles = user::find($validated['id'])->roles()->get();


        $message = "roles of user " . $validated['id'] . " .";
        $success = $user;
        $notifications_En_Server = "roles of users " . $validated['id'] . " .";
        $notifications_Fa_Server = "نقشهای کاربر " . $validated['id'] . " . ";
        return response()->json(
            [
                'message' => $message,
                'success' => $success,
                "data" => $roles,
                'NotificationsEnServer' => $notifications_En_Server,
                'NotificationsFaServer' => $notifications_Fa_Server,
            ],
            200
        ); // 204 (No Content) - 202 (Accepted)

    }

    public function attachRole(Request $request, string $id)
    {
        // id validate user
        $validator = Validator::make(
            ['id' => $id],
            ['id' => ['required', 'integer']]
        );
        $validated = $validator->validated();
        $user = User::findOrFail($validated['id']);

        // request validate roles
        $request->validate([
                'roles' => ['nullable'],
                'roles.*' => ['nullable', 'integer', 'exists:users,id'],
            ]
        );
        $roles = Role::whereIn('id', $request->roles)
            ->select(['id', 'role_name'])
            ->get();
        foreach ($roles as $key => $role) {
            if ($user->hasRole($role->name)) {
                unset($roles[$key]);
            }
        }

        $user->roles()->attach($roles);

        $message = "for user " . $validated['id'] . " roles assigned.";
        $success = $user;
        $notifications_En_Server = "for user " . $validated['id'] . " roles assigned.";
        $notifications_Fa_Server = "برای کاربر " . $validated['id'] . " نقشها اضافه شد ";
        return response()->json(
            [
                'message' => $message,
                'success' => $success,
                "data" => $roles,
                'NotificationsEnServer' => $notifications_En_Server,
                'NotificationsFaServer' => $notifications_Fa_Server,
            ],
            200
        ); // 204 (No Content) - 202 (Accepted)

    }

    public function detachRole(Request $request, string $id)
    {
        // id validate user
        $validator = Validator::make(
            ['id' => $id],
            ['id' => ['required', 'integer']]
        );
        $validated = $validator->validated();
        $user = User::findOrFail($validated['id']);

        // request validate roles
        $request->validate([
                'roles' => ['nullable'],
                'roles.*' => ['nullable', 'integer', 'exists:users,id'],
            ]
        );
        $roles = Role::whereIn('id', $request->roles)
            ->select(['id', 'role_name'])
            ->get();

        $roles->each(function (int $item, int $key) use ($user) {
            if (!$user->hasRole($item->name)) {
                $message = " user " . $user['id'] . "do not have this roles";
                $success = $item;
                $notifications_En_Server = " user " . $user['id'] . "do not have this roles";
                $notifications_Fa_Server = " کاربر " . $user['id'] . " این نقشهای کاربری را ندارد ";
                return response()->json(
                    [
                        'message' => $message,
                        'success' => $success,
                        "data" => $user,
                        'NotificationsEnServer' => $notifications_En_Server,
                        'NotificationsFaServer' => $notifications_Fa_Server,
                    ],
                    200
                ); // 204 (No Content) - 202 (Accepted)
            }
        }
        );
        foreach ($roles->all() as $role) {
            if (!$user->hasRole($role->name)) {
                $message = " user " . $validated['id'] . "do not have this roles";
                $success = $roles;
                $notifications_En_Server = " user " . $validated['id'] . "do not have this roles";
                $notifications_Fa_Server = " کاربر " . $validated['id'] . " این نقشهای کاربری را ندارد ";
                return response()->json(
                    [
                        'message' => $message,
                        'success' => $success,
                        "data" => $user,
                        'NotificationsEnServer' => $notifications_En_Server,
                        'NotificationsFaServer' => $notifications_Fa_Server,
                    ],
                    200
                ); // 204 (No Content) - 202 (Accepted)
            }
        }

        $user->roles()->detach($roles);

        $message = " user " . $validated['id'] . " roles removed.";
        $success = $roles;
        $notifications_En_Server = " user " . $validated['id'] . " roles removed.";
        $notifications_Fa_Server = "از کاربر " . $validated['id'] . " نقش های کاربری حذف شد ";
        return response()->json(
            [
                'message' => $message,
                'success' => $success,
                "data" => $user,
                'NotificationsEnServer' => $notifications_En_Server,
                'NotificationsFaServer' => $notifications_Fa_Server,
            ],
            200
        ); // 204 (No Content) - 202 (Accepted)

    }

}
