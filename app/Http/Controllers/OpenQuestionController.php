<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\OpenQuestion;
use App\Models\Discipline;
use App\Models\Course;

class OpenQuestionController extends Controller
{
    private $openquestion;
    private $discipline;
    private $course;

    public function __construct(OpenQuestion $openquestion, Course $course, Discipline $discipline) 
    {
        $this->middleware('auth');
        $this->openquestion = $openquestion;
        $this->discipline = $discipline;
        $this->course = $course;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->openquestion->query();

        $search_course = $request->course;
        $search_discipline = $request->discipline;

        if (isset($search_course)) {
            $query->where('course_id', $search_course);
        }

        if (isset($search_discipline)) {
            $query->where('discipline_id', $search_discipline);
        }
        
        $courses = $this->course->where('status','active')->get();
        $disciplines = $this->discipline->where('status','active')->get();

        $openquestions = $query->orderBY('id','DESC')->paginate(10);

        return view('admin.openquestions.index',[
            'openquestions' => $openquestions,
            'courses' => $courses,
            'disciplines' => $disciplines,
            'search_discipline' => $search_discipline,
            'search_course' => $search_course
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function list (string $id) 
    {
        $disciplines = $this->discipline->where('status','active')->where('course_id', '=', $id)->get();
        return view('admin.openquestions.search',['disciplines' => $disciplines]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = $this->course->where('status','active')->get();
        return view('admin.openquestions.create',['courses' => $courses]);
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
            'title' => 'required|string'
        ])->validate();

        if($this->openquestion->create($data)) {
            return redirect('admin/openquestions')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/openquestions/create')->with('error', 'Erro ao inserir o registro!');
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
        $courses = $this->course->where('status','active')->get();
        $question = $this->openquestion->find($id);
        $disciplines = $this->discipline->where('status','active')->where('course_id', $question['course_id'])->get();
        
        if (!$question) {
            return redirect('admin/openquestions')->with('alert', 'Registro não encontrado!');
        }
        
        return view('admin.openquestions.edit',['question' => $question, 'courses' => $courses, 'disciplines' => $disciplines]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $question = $this->openquestion->find($id);

        if (!$question) {
            return redirect('admin/openquestions')->with('alert', 'Registro não encontrado!');
        }

        Validator::make($data, [
            'course_id' => 'required',
            'discipline_id' => 'required',
            'title' => 'required|string',
        ])->validate();

        if($question->update($data)) {
            return redirect('admin/openquestions')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/openquestions')->with('error', 'Erro ao alterado o registro!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = $this->openquestion->find($id);
        if($data->delete()) {
            return redirect('admin/openquestions')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/openquestions')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
