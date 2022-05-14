<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = ['nome','imagem'];

    /**
     * Inicia as validações dos inputs
     */

    public function rules()
    {
        return [
            'nome' => 'required|unique:marcas,nome,'.$this->id.':marcas',
            'imagem' => 'required|file|mimes:png'
        ];
    }   
    public function feedback()
    {
        return [
            'required' =>'O campo :attribute é obrigatório',
            'imagem.mimes' =>'O arquivo deve ser do tipo PNG',
            'nome.unique' => 'O nome da marca já existe'
        ];
    }
    /**
     * Termino validações dos inputs
     */
    
    //  uma marca possui muitos modelos
    public function modelos()
    {
        return $this->hasMany('App\Models\Modelo');
    }
}
