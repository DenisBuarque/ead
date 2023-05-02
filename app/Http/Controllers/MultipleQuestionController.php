<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\MultipleQuestion;
use App\Models\Discipline;
use App\Models\Course;

class MultipleQuestionController extends Controller
{
    private $multiplequestion;
    private $discipline;
    private $course;

    public function __construct(MultipleQuestion $multiplequestion, Discipline $discipline, Course $course) 
    {
        $this->middleware('auth');
        $this->multiplequestion = $multiplequestion;
        $this->discipline = $discipline;
        $this->course = $course;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->multiplequestion->query();

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

        $multiplequestions = $query->orderBY('id','DESC')->paginate(10);

        return view('admin.multiplequestions.index',[
            'multiplequestions' => $multiplequestions,
            'courses' => $courses,
            'disciplines' => $disciplines,
            'search_course' => $search_course,
            'search_discipline' => $search_discipline
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function list ($id) 
    {
        $disciplines = $this->discipline->where('status','active')->where('course_id', '=', $id)->get();
        return view('admin.multiplequestions.search',['disciplines' => $disciplines]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = $this->course->where('status','active')->get();
        return view('admin.multiplequestions.create',['courses' => $courses]);
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
            'title' => 'required|string',
            'response_one' => 'required|string',
            'response_two' => 'required|string',
            'response_tree' => 'required|string',
            'response_four' => 'required|string',
            'gabarito' => 'required|integer',
            'punctuation' => 'required|integer',
        ])->validate();

        if($this->multiplequestion->create($data)) {
            return redirect('admin/multiplequestions')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/multiplequestions/create')->with('error', 'Erro ao inserir o registro!');
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
        
        $question = $this->multiplequestion->find($id);
        $courses = $this->course->where('status','active')->get();
        $disciplines = $this->discipline->where('course_id', $question['course_id'])->get();
        
        if ($question) {
            return view('admin.multiplequestions.edit',[
                'question' => $question, 
                'courses' => $courses, 
                'disciplines' => $disciplines]);
        }

        return redirect('admin/multiplequestions')->with('alert', 'Registro não encontrado!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $question = $this->multiplequestion->find($id);

        if (!$question) {
            return redirect('admin/multiplequestions')->with('alert', 'Registro não encontrado!');
        }

        Validator::make($data, [
            'course_id' => 'required',
            'discipline_id' => 'required',
            'title' => 'required|string',
            'response_one' => 'required|string',
            'response_two' => 'required|string',
            'response_tree' => 'required|string',
            'response_four' => 'required|string',
            'gabarito' => 'required|integer',
            'punctuation' => 'required|integer',
        ])->validate();

        if($question->update($data)) {
            return redirect('admin/multiplequestions')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/multiplequestions')->with('error', 'Erro ao alterado o registro!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = $this->multiplequestion->find($id);
        if($data->delete()) {
            return redirect('admin/multiplequestions')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/multiplequestions')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
