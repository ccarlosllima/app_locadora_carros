<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{   
    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $modelos = array();

        if ($request->has('atributos_marca')) {
            $modelos = $this->modelo->with('marca:id,'.$request->atributos_marca);
        }else{
            $modelos = $this->modelo->with('marca');
        }
        
        if ($request->has('filtro')) {
            $filtros = explode(';',$request->filtro);

            foreach ($filtros as $key => $condicao) {
                $c = explode(':',$condicao);
                $modelos = $modelos->where($c[0],$c[1],$c[2]);
            }

        }

        if ($request->has('atributos')) {
            $atributos = $request->atributos;   
            $modelos = $modelos->selectRaw($atributos)->get();
        }else{
            $modelos = $modelos->get();
        }
        return response()->json($modelos, 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->modelo->rules());

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagem/modelo', 'public');

        $modelo = $this->modelo->create([

            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs            
        ]);

        return response()->json($modelo,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);
        
        if ($modelo === null) {
            return response()->json(["error" => "O recurso solicitado não existe"], 404);
        }
        return response()->json($modelo, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);

        if ($modelo === null) {
            return response()->json(["error" => "Imposivel realizar atualização. O recurso solicitado não existe"], 404);
        }
        
        if ($request->method() === 'PATCH') {

            $regras_patch = [];

            foreach ($modelo->rules() as $key => $regra) {
                if (array_key_exists($key, $request->all())) {
                    $regras_patch[$key] = $regra;
                }
            }
            $request->validate($regras_patch);

        }else{

            $request->validate($modelo->rules());
        }

        if ($request->file('imagem')) {
            Storage::disk('public')->delete($modelo->imagem);
        }

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagem/modelo','public');

        $modelo->fill($request->all());
        $modelo->imagem = $imagem_urn;
        $modelo->save();
     
        return response()->json($modelo, 200);    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);
        if ($modelo === null) {
            return response()->json(['error' => 'O recurso solicitado não existe'], 404);
        }
        Storage::disk('public')->delete($modelo->imagem);
        $modelo->delete();

        return response()->json(['msg' => 'Recurso excluido com sucesso'], 200);
    }
}
