<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositores\MarcaRepository;

class MarcaController extends Controller
{
    public function __construct(Marca $marca){
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $marcaRepsitory = new MarcaRepository($this->marca);
        
        if ($request->has('atributos_modelos')) {
            $atributos_modelos = 'modelos:id,'.$request->atributos_modelos;
            $marcaRepsitory->selectAtributosRegistrosRelacionados($atributos_modelos);
        }else{

            $marcaRepsitory->selectAtributosRegistrosRelacionados('modelos');
        }
        if ($request->has('filtro')) {

            $marcaRepsitory->filtro($filtros);
        }

        if ($request->has('atributos')) {

            $marcaRepsitory->selectAtributos($request->atributos);
           
        }

        return response()->json($marcaRepsitory->getResultado(), 200);

    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());

        $imagem = $request->file('imagem'); 
        $imagem_urn = $imagem->store('imagem', 'public');
       
        $marca =  $this->marca->create([
            'nome' => $request->nome,
            'imagem'=> $imagem_urn
        ]);
       
       return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);

        if ($marca === null) {

            return response()->json(["error" => "Recurso não existe"],404);
        }
        return response()->json($marca, 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);


        if ($marca === null) {
            return response()->json(["error" => "Imposivel realizar atualização. O recurso solicitado não existe"], 404);
        }
        
        if ($request->method() === 'PATCH') {

            $regras_patch = [];

            foreach ($marca->rules() as $key => $regra) {
                if (array_key_exists($key, $request->all())) {
                    $regras_patch[$key] = $regra;
                }
            }
            $request->validate($regras_patch, $this->marca->feedback());
        }else{

            $request->validate($marca->rules(), $marca->feedback());
        }
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);
        }

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagem','public');

        $marca->fill($request->all());
        $marca->imagem = $imagem_urn;
        
        $marca->save();
        /*
            $marca->update([
                'nome' => $request->nome,
                'imagem'=> $imagem_urn
            ]);
        */
        
        return response()->json($marca, 200);        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);

        if($marca === null){
            return response()->json(["error" => "Imposivel realizar a remoção. O recurso solicitado não existe"], 404);
        }
        
        // Remove o arquivo caso ele exista na basa de dados
        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();
        return response()->json(["msg" => "recurso excluido com sucesso"], 200);
    }
}
