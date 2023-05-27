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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UsersLibraryFormRequest $request)
    {
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
