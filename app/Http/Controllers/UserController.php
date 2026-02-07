<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Str;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::query()
        ->when(
            $request->has('username'),
            fn ($query) =>$query->where('username','like','%' . $request->input('username') . '%')
            )
        ->when(
            $request->has('email'),
            fn ($query) =>$query->where('email','like','%' . $request->input('email') . '%')
        )
        ->when(
            $request->boolean('trashed'), // Devuelve true si envían ?trashed=true o ?trashed=1
            fn ($query) => $query->onlyTrashed() // Trae SOLO los eliminados
        )
        ->get();

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Str::random(8); // Le colocamos una contraseña por defecto

        // SI viene una hiring date que se le asigne esa, sino se llama a la funcion now()
        $data['hiring_date'] = $data['hiring_date'] ?? now();

        $user = User::create($data);
        
        return response()->json(UserResource::make($user), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json(UserResource::make($user), 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $user->update($data);

        return response()->json(UserResource::make($user), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user) // ya contiene la logica de validacion. 
    {
        $user->delete();

        return response()->json([
            'message' => 'El usuario ha sido eliminado correctamente.'
        ], 200);
    }

    public function restore($id)
    {
        // Buscamos el usuario, INCLUYENDO los eliminados (withTrashed)
        $user = User::withTrashed()->find($id);

        if (!$user) {
            return response()->json(['message' => 'El usuario no existe.'], 404);
        }

        if (!$user->trashed()) {
            return response()->json(['message' => 'El usuario ya está activo.'], 409); // 409 Conflict
        }

        $user->restore();
        
        return response()->json(['message' => 'Usuario restaurado correctamente.']);
    }
}
