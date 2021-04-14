<?php

namespace App\Http\Controllers;


use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
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
        $results = Usuario::all();
        //$results = app('db')->select("SELECT * FROM usuario");
        return response()->json($results, 200);
    }

    public function show($id){
        $results = Usuario::find($id);
        //$results = app('db')->select("SELECT * FROM usuario");
        return response()->json($results, 200);
    }

    public function login(Request $request){

        $results = Usuario::where('login', $request->login)
                           ->where('senha', $request->senha)
                           ->where('ativos', true)
            ->first();

        if(empty($results)){
            return response()->json(["message" => "usuario não encontrado"], 401);
        }

        return response()->json($results, 200);
    }


    public function store(Request $request){

        $vetor_erro = array();
        if(strlen(trim($request->login)) == 0){
            array_push($vetor_erro, 'login');
        }
        if(strlen(trim($request->senha)) == 0){
            array_push($vetor_erro, 'senha');
        }
        if(strlen(trim($request->nome)) == 0){
            array_push($vetor_erro, 'nome');
        }

        if(count($vetor_erro)>0){
            return "Preencher todos os campos obrigatórios, ". implode(',', $vetor_erro);
        }

        $usuario = new Usuario;
        $usuario->login = $request->login;
        $usuario->senha = $request->senha;
        $usuario->nome = $request->nome;

        $usuario->save();

        return $usuario;

    }

    public function update($id, Request $request){


        $vetor_erro = array();
        if(strlen(trim($request->login)) == 0){
            array_push($vetor_erro, 'login');
        }
        if(strlen(trim($request->senha)) == 0){
            array_push($vetor_erro, 'senha');
        }
        if(strlen(trim($request->nome)) == 0){
            array_push($vetor_erro, 'nome');
        }

        if(count($vetor_erro)>0){
            return "Preencher todos os campos obrigatórios, ". implode(',', $vetor_erro);
        }


        $usuario = Usuario::find($id);
        $usuario->login = $request->login;
        $usuario->senha = $request->senha;
        $usuario->nome = $request->nome;

        $usuario->save();

        return $usuario;

    }


    public function destroy($id){

        $usuario = Usuario::find($id);
        $usuario->delete($id);

        return $usuario;
    }



}
