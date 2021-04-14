<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Lancamento;
use App\Models\Receita;


class LancamentoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() { }

    public function index(){

        $results = Lancamento::all();
        return $results;
    }

    public function listar(Request $request,$usuario_id,$mes,$ano,$tipo){

        $lancamento = Lancamento::join('tipodespesa', 'tipodespesa.tipodespesa', '=', 'lancamento.tipo_lancamento')
                                    ->where('lancamento.usuario_id', $usuario_id)
                                    ->where('mes', $mes)
                                    ->where('ano', $ano)
                                    ->where('debito_credito', $tipo)
                                    ->get(['lancamento.lancamento', 'lancamento.usuario_id', 'lancamento.tipo_lancamento', 'lancamento.descricao', 'lancamento.valor', 'lancamento.debito_credito', 'tipodespesa.descricao as tipo']);


        // print_r($request->header('token'));
        // exit;


        return response()->json($lancamento, 200);
    }


    public function show($id){


        $results = Lancamento::find($id);
        return $results;

    }

    public function store(Request $request){

        $vetor_erro = array();
        if(strlen(trim($request->usuario_id)) == 0){
            array_push($vetor_erro, 'usuario');
        }
        if(strlen(trim($request->tipo_lancamento)) == 0 && $request->debito_credito == 'D'){
            array_push($vetor_erro, 'tipo de lancamento');
        }

        if(strlen(trim($request->valor)) == 0){
            array_push($vetor_erro, 'valor');
        }

        if(strlen(trim($request->valor)) == 0){
            array_push($vetor_erro, 'valor');
        }

        if(strlen(trim($request->descricao)) == 0){
            array_push($vetor_erro, 'descricao');
        }

        if(count($vetor_erro)>0){
            return "Preencher todos os campos obrigatórios, ". implode(',', $vetor_erro);
        }

        $lancamento = new Lancamento;

        $lancamento->usuario_id = $request->usuario_id;
        $lancamento->tipo_lancamento = $request->tipo_lancamento;
        $lancamento->mes = date('m');
        $lancamento->ano = date('Y');
        $lancamento->descricao = $request->descricao;
        $lancamento->valor = $request->valor;
        $lancamento->debito_credito = $request->debito_credito;

        $lancamento->save();

        return $lancamento;

    }

    public function update($id, Request $request){

        $vetor_erro = array();
        if(strlen(trim($request->usuario_id)) == 0){
            array_push($vetor_erro, 'usuario');
        }
        if(strlen(trim($request->tipo_lancamento)) == 0 && $request->debito_credito == 'D'){
            array_push($vetor_erro, 'tipo de lancamento');
        }
        if(strlen(trim($request->valor)) == 0){
            array_push($vetor_erro, 'valor');
        }
        // if(strlen(trim($request->mes)) == 0){
        //     array_push($vetor_erro, 'mes');
        // }
        if(strlen(trim($request->valor)) == 0){
            array_push($vetor_erro, 'valor');
        }
        if(strlen(trim($request->descricao)) == 0){
            array_push($vetor_erro, 'descricao');
        }
        if(count($vetor_erro)>0){
            return "Preencher todos os campos obrigatórios, ". implode(',', $vetor_erro);
        }

        $lancamento = Lancamento::find($id);

        $lancamento->usuario_id = $request->usuario_id;
        $lancamento->tipo_lancamento = $request->tipo_lancamento;
        //$lancamento->receita_id = $request->receita_id;
        // $lancamento->mes = $request->mes;
        // $lancamento->ano = $request->ano;
        $lancamento->descricao = $request->descricao;
        $lancamento->valor = $request->valor;
        $lancamento->debito_credito = $request->debito_credito;

        $lancamento->save();

        return $lancamento;
    }

    public function destroy($id){

        $lancamento = Lancamento::find($id);
        $lancamento->delete($id);

        return $lancamento;
    }

    public function saldo($usuario_id,$mes,$ano){

        $saldo = array();

        $despesas = $this->totaldespesas($usuario_id,$mes,$ano);
        $receitas = $this->totalreceita($usuario_id,$mes,$ano);

        $dadosDepesas = $despesas->original;
        $dadosReceitas = $receitas->original;

        $saldo['Despesas']          =  $dadosDepesas['lancamentos'];
        $saldo['Receitas']          =  $dadosReceitas['lancamentos'];
        $saldo['totalReceitas']     =  $dadosReceitas['total'];
        $saldo['totalDespesas']     =  $dadosDepesas['total'];

        $saldo['saldo'] = $saldo['totalReceitas'] - $saldo['totalDespesas'];

        return $saldo;

    }

    public function totaldespesas($usuario_id,$mes,$ano){

        $lancamento = Lancamento::where('usuario_id', $usuario_id)
                                    ->where('mes', $mes)
                                    ->where('ano', $ano)
                                    ->where('debito_credito', 'D')
                                    ->get();

            $total = 0;
    foreach($lancamento as $linha){
        $total += $linha->valor;
    }



        return response()->json(['lancamentos' => $lancamento, 'total' => $total], 200);
    }

    public function totalreceita($usuario_id,$mes,$ano){

        $lancamento = Lancamento::where('usuario_id', $usuario_id)
                                    ->where('mes', $mes)
                                    ->where('ano', $ano)
                                    ->where('debito_credito', 'C')
                                    ->get();

        $total = 0;
        foreach($lancamento as $linha){
            $total += $linha->valor;
        }

        return response()->json(['lancamentos' => $lancamento, 'total' => $total], 200);
    }


    public function importarReceitas($usuario_id){

        $receita = Receita::where('usuario_id', $usuario_id)
                                ->where('fixo', true)
                                ->get();

        foreach($receita as $linha){

            $verificaCadastrados = Lancamento::where('usuario_id', $usuario_id)
                                    ->where('mes', date('m'))
                                    ->where('ano', date('Y'))
                                    ->where('receita_id', $linha->receita)
                                    ->first();

            if(!empty($verificaCadastrados->lancamento)){
                continue;
            }

            $lancamento = new Lancamento;

            $lancamento->usuario_id = $usuario_id;
            $lancamento->tipo_lancamento = 1;
            $lancamento->receita_id = $linha->receita;
            $lancamento->mes = date('m');
            $lancamento->ano = date('Y');
            $lancamento->descricao = $linha->descricao;
            $lancamento->valor = $linha->valor;
            $lancamento->debito_credito = 'C';

            $lancamento->save();
        }

        $lancamentos = Lancamento::where('usuario_id', $usuario_id)
                                    ->where('mes', date('m'))
                                    ->where('ano', date('Y'))
                                    ->where('debito_credito', 'C')
                                    ->get();
        return $lancamentos;

    }

    public function listaReceitas($usuario_id,$mes,$ano){

        $lancamento = Lancamento::where('usuario_id', $usuario_id)
                                    ->where('mes', $mes)
                                    ->where('ano', $ano)
                                    ->where('debito_credito', 'C')
                                    ->get();


        return response()->json(['receitas' => $lancamento], 200);
    }




}
