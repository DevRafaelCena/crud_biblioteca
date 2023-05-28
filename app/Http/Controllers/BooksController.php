<?php

namespace App\Http\Controllers;

use App\Http\Requests\BooksFormRequest;
use App\Models\Books;
use App\Models\Loans;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{

              // busca todos os usuarios

            $books = Books::with('genres')->get()->sortBy('name');

            foreach ($books as $book) {
                // verifica se livro está emprestado.

                $bookAvailable = Loans::where('book_id', $book->id)->where('date_returned', null)->first();

                if($bookAvailable){
                    $book->available = false;
                    $book->return_due_date = $bookAvailable->return_due_date;

                }else{
                    $book->available = true;
                }

            }

            return $books;

        }catch (\Exception $e) {

            return response()->json(['error' => 'Erro ao buscar os livros'], 500);
        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(BooksFormRequest $request)
    {
        try{
            // cria novo usuario
            $books = Books::create($request->all());
            return $books;
        }catch (\Exception $e) {

            return response()->json(['error' => 'Erro ao criar o livro'], 500);
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


        try{
            $books = Books::findOrFail($id);
            return $books;

        }catch (\Exception $e) {

            return response()->json(['error' => 'Livro não encontrado'], 404);
        }

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

        try{
            // atualiza um usuario

            $books = Books::findOrFail($id);

            $books->fill($request->all());

            $books->save();

            return $books;
        }catch (\Exception $e) {

            return response()->json(['error' => 'Erro ao atualizar o livro'], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try{
            // apaga um usuario com frag no banco

            $books = Books::findOrFail($id);

            $books->deleted = 1;

            $books->save();

            return $books;

        }catch (\Exception $e) {

            return response()->json(['error' => 'Erro ao atualizar o livro'], 500);
        }


    }
}
