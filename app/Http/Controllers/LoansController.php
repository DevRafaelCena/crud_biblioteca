<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoansFormRequest;
use App\Models\Books;
use App\Models\Loans;
use App\Models\UsersLibrary;
use Illuminate\Http\Request;

class LoansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // busca todos os emprestimos

        $loans = Loans::all();

        return $loans;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoansFormRequest $request)
    {

        // verifica se usuario existe

        $user = UsersLibrary::find($request->user_id);

        if(!$user){
            return response()->json(['error' => 'Usuário não encontrado'], 400);
        }

        // verifica se livro existe na base de dados

        $book = Books::find($request->book_id);

        if(!$book){
            return response()->json(['error' => 'Livro não encontrado'], 400);
        }

        // verifica se o livro ja esta emprestado

        $bookAvailable = Loans::where('book_id', $request->book_id)->where('date_returned', null)->first();

        if($bookAvailable){
            return response()->json(['error' => 'Livro já emprestado'], 400);
        }

        // valida se a data de devolução é maior que a data atual



        $dateReturn = strtotime($request->date_return);

        if($dateReturn < strtotime(date('Y-m-d')) ){
            return response()->json(['error' => 'Data de devolução inválida'], 400);
        }

        // cria novo emprestimo
        $loan = Loans::create([
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'return_due_date' => $request->date_return
        ]);

        return $loan;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // returna um emprestimo
        $loan = Loans::findOrFail($id);

        return $loan;
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
        // Busca o empréstimo do livro informado
        $loan = Loans::where('book_id', $id)
                    ->whereNull('date_returned')
                    ->first();

        // Verifica se o livro já foi devolvido
        if (!$loan) {
            return response()->json(['error' => 'Livro já devolvido'], 400);
        }

        $loan->update([
            'date_returned' => date('Y-m-d')
        ]);

        return response()->json(['success' => 'Livro devolvido com sucesso'], 200);
    }


}
