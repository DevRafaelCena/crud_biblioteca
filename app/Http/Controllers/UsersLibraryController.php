<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersLibraryFormRequest;
use App\Models\UsersLibrary;
use Illuminate\Http\Request;

class UsersLibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // busca todos os usuarios

        $users = UsersLibrary::all();

        return $users;

    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        // Obtém o termo de busca do request
        $term = $request->term;

        $users = UsersLibrary::where(function ($query) use ($term) {
            $query->where('name', 'LIKE', '%' . $term . '%')
                  ->orWhere('email', 'LIKE', '%' . $term . '%')
                  ->orWhere('id', 'LIKE', '%' . $term . '%');
        })
        ->where('deleted', 0)
        ->get();

        return $users;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UsersLibraryFormRequest $request)
    {

        $existingUser = UsersLibrary::where('email', $request->email)->first();

        if ($existingUser) {
            return response()->json(['error' => 'E-mail já cadastrado'], 400);
        }

        // cria novo usuario

        $user = UsersLibrary::create($request->all());
        return $user;


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // returna um usuario

        $user = UsersLibrary::findOrFail($id);
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // atualiza um usuario

        $user = UsersLibrary::findOrFail($id);

        $user->fill($request->all());

        $user->save();

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // apaga um usuario com frag no banco

        $user = UsersLibrary::findOrFail($id);

        $user->deleted = 1;

        $user->save();

        return $user;

    }
}
