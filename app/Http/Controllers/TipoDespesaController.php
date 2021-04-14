<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoDespesa;


class TipoDespesaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index(){
        $results = TipoDespesa::all();
        return $results;
    }

    public function show($id){
        $results = TipoDespesa::find($id);
        return $results;
    }


    public function store(Request $request){

        $vetor_erro = array();
        if(strlen(trim($request->usuario_id)) == 0){
            array_push($vetor_erro, 'usuario');
        }
        if(strlen(trim($request->descricao)) == 0){
            array_push($vetor_erro, 'descricao');
        }

        if(count($vetor_erro)>0){
            return "Preencher todos os campos obrigatórios, ". implode(',', $vetor_erro);
        }

        $tipoDespesa = new TipoDespesa;

        $tipoDespesa->usuario_id = $request->usuario_id;
        $tipoDespesa->descricao = $request->descricao;
        $tipoDespesa->ativo = ($request->ativo == true) ? 1 : 0;

        $tipoDespesa->save();

        return $tipoDespesa;

    }

    public function update($id, Request $request){

        $vetor_erro = array();

        if(strlen(trim($request->descricao)) == 0){
            array_push($vetor_erro, 'descricao');
        }

        if(count($vetor_erro)>0){
            return "Preencher todos os campos obrigatórios, ". implode(',', $vetor_erro);
        }


        $tipoDespesa = TipoDespesa::find($id);

        $tipoDespesa->descricao = $request->descricao;
        $tipoDespesa->ativo = ($request->ativo == 1) ? 1 : 0;
        $tipoDespesa->save();

        return $tipoDespesa;
    }

    public function destroy($id){

        $tipoDespesa = TipoDespesa::find($id);
        $tipoDespesa->delete($id);

        return $tipoDespesa;

    }



}
