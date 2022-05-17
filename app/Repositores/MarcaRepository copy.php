<?php

namespace App\Repositores;

use Illuminate\Database\Eloquent\Model;

class MarcaRepository
{
    public function __construct(Marca $model)
    {
        $this->model = $marca;
    }

    public function selectAtributosRegistrosRelacionados($atributos)
    {
        $this->model = $model = $this->model->with($atributos);
    }

    public function filtros($filtros)
    {   
        $filtros = explode(';',$request->filtro);

        foreach ($filtros as $key => $condicao) {
            $c = explode(':',$condicao);
            $this->model = $this->model->where($c[0],$c[1],$c[2]);
        }
        
    }

    public function selectAtributo($atributos)
    {   
        $this->model = $model->selectRaw($atributos);
    }

    public function getResultado()
    {
        return $this->model->get();
    }
}

?>