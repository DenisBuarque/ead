<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Inscription;
use App\Models\Course;
use App\Models\Discipline;
use App\Models\User;

class InscriptionController extends Controller
{
    private $inscription;
    private $course;
    private $discipline;
    private $user;

    public function __construct (Inscription $inscription, Course $course, Discipline $discipline, User $user) 
    {
        $this->middleware('auth');
        $this->inscription = $inscription;
        $this->course = $course;
        $this->discipline = $discipline;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $records = $this->inscription->all();
        $disciplines = $this->discipline->where('status','active')->get();
        $courses = $this->course->all();

        $query = $this->inscription->query();

        $search_course = $request->course;
        $search_discipline = $request->discipline;
        $search_status = $request->status;

        if (!empty($search_course)) {
            $query->where('course_id', $search_course);
        }

        if (!empty($search_discipline)) {
            $query->where('discipline_id', $search_discipline);
        }

        if (!empty($search_status)) {
            $query->where('status', $search_status);
        }

        $inscriptions = $query->orderBy('id','DESC')->paginate(10);
        return view('admin.inscriptions.index',[
            'disciplines' => $disciplines,
            'inscriptions' => $inscriptions,
            'records' => $records,
            'courses' => $courses,
            'search_course' => $search_course,
            'search_discipline' => $search_discipline,
            'search_status' => $search_status,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = $this->course->where('status','active')->get();
        $disciplines = $this->discipline->where('status','active')->get();

        $students = DB::table('users')
                    ->where('users.nivel','student')
                    ->where('users.access','active')
                    ->join('registrations', 'users.id', '=', 'registrations.user_id')
                    ->where('registrations.payment','yes')
                    ->select(['users.*'])
                    ->get();

        return view('admin.inscriptions.create', [
            'courses' => $courses,
            'disciplines' => $disciplines,
            'students' => $students,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function list ($id) 
    {
        $disciplines = $this->discipline->where('course_id', $id)->where('status','active')->get();
        return view('admin.inscriptions.search',['disciplines' => $disciplines]);
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
            'user_id' => 'required',
            'date_inscription' => 'required',
            'closing_date' => 'required'
        ])->validate();

        if($this->inscription->create($data)) {
            return redirect('admin/inscriptions')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/inscriptions')->with('error', 'Erro ao inserir o registro!');
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
        $inscription = $this->inscription->find($id);

        $courses = $this->course->where('status','active')->get();
        $disciplines = $this->discipline->where('status','active')->where('course_id', $inscription->course_id)->get();

        $students = DB::table('users')
                    ->where('users.nivel','student')
                    ->where('users.access','active')
                    ->join('registrations', 'users.id', '=', 'registrations.user_id')
                    ->where('registrations.payment','yes')
                    ->select(['users.*'])
                    ->get();

        if ($inscription) {
            return view('admin.inscriptions.edit', [
                'courses' => $courses, 
                'disciplines' => $disciplines,
                'students' => $students,
                'inscription' => $inscription
            ]);

        } else {
            return redirect('admin/inscriptions')->with('alert', 'Registro não encontrado!'); 
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $inscription = $this->inscription->find($id);

        if (!$inscription) {
            return redirect('admin/inscriptions')->with('alert', 'Registro não encontrado!');
        }

        Validator::make($data, [
            'course_id' => 'required',
            'discipline_id' => 'required',
            'user_id' => 'required',
            'date_inscription' => 'required',
            'closing_date' => 'required'
        ])->validate();

        if($inscription->update($data)) {
            return redirect('admin/inscriptions')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/inscriptions')->with('error', 'Erro ao alterado o registro!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = $this->inscription->find($id);
        if ($data->delete()) {
            return redirect('admin/inscriptions')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/inscriptions')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
