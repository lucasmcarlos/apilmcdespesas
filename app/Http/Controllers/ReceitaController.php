<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receita;


class ReceitaController extends Controller
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

        $results = Receita::all();
        return $results;

    }

    public function show($id){

        $results = Receita::find($id);
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

        $receita = new Receita;

        $receita->usuario_id = $request->usuario_id;
        $receita->descricao = $request->descricao;
        $receita->valor = $request->valor;
        $receita->fixo = $request->fixo;

        $receita->save();

        return $receita;

    }

    public function update($id, Request $request){


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


        $receita = Receita::find($id);

        $receita->usuario_id = $request->usuario_id;
        $receita->descricao = $request->descricao;
        $receita->save();

        return $receita;
    }

    public function destroy($id){

        $receita = Receita::find($id);
        $receita->delete($id);

        return $receita;

    }



}
