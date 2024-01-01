<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ClienteController extends Controller
{
    public function index() // GET
    {
        $clientes = User::all();
        return $clientes;
    }

    public function store(Request $request) // POST
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        return response()->json(['message' => 'Usuario creado correctamente'], 201);
    }

    public function show($id) // GET
    {
        $cliente = User::find($id);
        return $cliente;
    }

    public function update(Request $request, $id) // PUT
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'password' => ['sometimes', 'required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // Actualizar la contraseña solo si se proporciona
        if ($request->has('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->save();
        return response()->json(['message' => 'Usuario actualizado correctamente'], 200);
    }

    public function destroy($id) // DELETE
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'Usuario eliminado correctamente'], 200);
    }
}
