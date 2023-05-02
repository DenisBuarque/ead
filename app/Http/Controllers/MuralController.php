<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Mural;
use App\Models\Course;
use App\Models\Discipline;

class MuralController extends Controller
{
    private $mural;
    private $course;
    private $discipline;

    public function __construct(Mural $mural, Course $course, Discipline $discipline) 
    {
        $this->middleware('auth');
        $this->mural = $mural;
        $this->course = $course;
        $this->discipline = $discipline;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->mural->query();

        $search_discipline = $request->discipline;
        $search_title = $request->title;

        if (isset($search_discipline)) {
            $query->where('discipline_id', $search_discipline);
        }

        if (isset($search_title)) {
            $query->where('title', 'LIKE', '%' . $search_title . '%');
        }

        $disciplines = $this->discipline->where('status','sim')->get();

        $murals = $query->orderBY('id','DESC')->paginate(10);
        return view('admin.murals.index', [
            'murals' => $murals,
            'disciplines' => $disciplines,
            'search_discipline' => $search_discipline,
            'search_title' => $search_title,
        ]);
    }

     /**
     * Display a listing of the resource.
     */
    public function list (string $id) 
    {
        $disciplines = $this->discipline->where('status','active')->where('course_id', '=', $id)->get();
        return view('admin.murals.search',['disciplines' => $disciplines]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = $this->course->where('status','active')->get();
        $murals = $this->mural->all();
        return view('admin.murals.create',['murals' => $murals, 'courses' => $courses]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'course_id' => 'required',
            'discipline_id' => 'required',
            'date' => 'required',
            'title' => 'required|string|max:100',
            'description' => 'required|string',
        ])->validate();

        if($this->mural->create($data)) {
            return redirect('admin/murals')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/mural/create')->with('error', 'Erro ao inserir o registro!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mural = $this->mural->find($id);

        $courses = $this->course->where('status','active')->get();
        $disciplines = $this->discipline->where('status','active')->where('course_id', $mural->course_id)->get();

        if ($mural) {
            return view('admin.murals.edit',[
                'mural' => $mural, 
                'courses' => $courses, 
                'disciplines' => $disciplines
            ]);
        }

        return redirect('admin/murals')->with('alert', 'Registro não encontrado!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $mural = $this->mural->find($id);

        if (!$mural) {
            return redirect('admin/murals')->with('alert', 'Registro não encontrado!');
        }

        Validator::make($data, [
            'course_id' => 'required',
            'discipline_id' => 'required',
            'title' => 'required|string|max:100',
            'description' => 'required|string',
        ])->validate();

        if($mural->update($data)) {
            return redirect('admin/murals')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/murals')->with('error', 'Erro ao alterado o registro!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = $this->mural->find($id);
        if($data->delete()) 
        {
            return redirect('admin/murals')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/murals')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
