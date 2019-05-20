<?php

namespace App\Http\Controllers;
use App\Curso;

use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function getCurso() {
        return Curso::all();
        
    }

    public function getCursoById(Curso $id) {
        return $id;        
    }

    public function contaCursos() {
        $cursos = Curso::all();
        $num = 0;
        foreach($cursos as $curso) {
            $num += 1;
        }
        return $num;
    }

    public function setCurso(Request $request) {
        $curso = Curso::create([
            'id'=>$this->contaCursos(),
            'formacao'=>$request->input('formacao')
        ]);

        return $curso;
    }

    public function delete(Curso $id) {
        $id->delete();

        return $id;
    }

    public function update(Request $request,Curso $id) {
        $id->id = $id['attributes']['id'];
        $id->formacao = $request->input('formacao');
        $id->save();
        
        return $id;
    }
}