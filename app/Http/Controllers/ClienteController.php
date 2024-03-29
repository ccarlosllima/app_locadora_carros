<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Repositores\ClienteRepository;

class ClienteController extends Controller
{
    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);

        if($request->has('filtro')) {
            $clienteRepository->filtro($request->filtro);
        }
        if($request->has('atributos')) {
            $clienteRepository->selectAtributos($request->atributos);
        } 

        return response()->json($clienteRepository->getResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreClienteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->cliente->rules());

        $cliente =  $this->cliente->create([
            'nome' => $request->nome
        ]);  
       return response()->json($cliente, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = $this->cliente->find($id);

        if ($cliente === null) {

            return response()->json(["error" => "Recurso não existe"],404);
        }
        return response()->json($cliente, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarroRequest  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cliente = $this->cliente->find($id);

        if ($cliente === null) {
            return response()->json(["error" => "Imposivel realizar atualização. O recurso solicitado não existe"], 404);
        }       
        if ($request->method() === 'PATCH') {
            $regras_patch = [];
            foreach ($cliente->rules() as $key => $regra) {
                if (array_key_exists($key, $request->all())) {
                    $regras_patch[$key] = $regra;
                }
            }
            $request->validate($regras_patch);
        }else{
            $request->validate($cliente->rules());
        }

        $cliente->fill($request->all());    
        $cliente->save();
       
        return response()->json($cliente, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente = $this->cliente->find($id);

        if($cliente === null){
            return response()->json(["error" => "Imposivel realizar a remoção. O recurso solicitado não existe"], 404);
        }
    
        $cliente->delete();
        return response()->json(["msg" => "cliente excluido com sucesso"], 200);
    }
}
