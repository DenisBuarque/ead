<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Forum;
use App\Models\Course;
use App\Models\Discipline;
use App\Models\Inscription;
use App\Models\ForumComment;
use App\Models\ForumOpnion;


class ForumController extends Controller
{
    private $forum;
    private $course;
    private $discipline;
    private $inscription;
    private $forumComment;
    private $forumOpnion;

    public function __construct(Forum $forum, Course $course, Discipline $discipline, Inscription $inscription, ForumComment $forumComment, ForumOpnion $forumOpnion)
    {
        $this->middleware('auth');
        $this->forum = $forum;
        $this->course = $course;
        $this->discipline = $discipline;
        $this->inscription = $inscription;
        $this->forumComment = $forumComment;
        $this->forumOpnion = $forumOpnion;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->forum->query();

        $search_title = $request->title;
        $search_course = $request->course;
        $search_discipline = $request->discipline;

        if (isset($search_course)) {
            $query->where('course_id', $search_course);
        }

        if (isset($search_discipline)) {
            $query->where('discipline_id', $search_discipline);
        }

        if (!empty($search_title)) {
            $query->where('title', 'LIKE', '%' . $search_title . '%');
        }

        $inscriptions = $this->inscription->all();
        $courses = $this->course->where('status','active')->get();
        $disciplines = $this->discipline->where('status','active')->get();

        $forums = $query->orderBY('id','DESC')->paginate(10);
        return view('admin.forums.index', [
            'forums' => $forums,
            'courses' => $courses,
            'disciplines' => $disciplines,
            'inscriptions' => $inscriptions,
            'search_title' => $search_title,
            'search_course' => $search_course,
            'search_discipline' => $search_discipline
        ]);
    }

    /**
     * List disciplines for course
     */
    public function list (string $id) 
    {
        $disciplines = $this->discipline->where('status','active')->where('course_id', $id)->get();
        return view('admin.forums.search',['disciplines' => $disciplines]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = $this->course->where('status','active')->get();
        $forums = $this->forum->all();
        return view('admin.forums.create',['forums' => $forums, 'courses' => $courses]);
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
            'title' => 'required|string|max:200',
            'description' => 'required|string',
        ])->validate();

        if($this->forum->create($data)) {
            return redirect('admin/forums')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/forums/create')->with('error', 'Erro ao inserir o registro!');
        }
    }

    /**
     * Store a newly created resource in forum comments.
     */
    public function comments (Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'forum_id' => 'required',
            'comment' => 'required|string|min:10|max:5000',
        ])->validate();

        $data['user_id'] = auth()->user()->id;

        if($this->forumComment->create($data)) {
            return redirect('admin/forum/show/' . $data['forum_id'])->with('success', 'Comentário inserido com sucesso!');
        } else {
            return redirect('admin/forum/show/' . $data['forum_id'])->with('error', 'Erro ao inserir o registro!');
        }
    }

    public function opinions (Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'user_id' => 'required',
            'forum_comment_id' => 'required',
            'opinion' => 'required|string|min:1|max:1000',
        ])->validate();

        if($this->forumOpnion->create($data)) {
            return redirect('admin/forum/show/' . $data['forum_id'])->with('success', 'Opnião inserida com sucesso!');
        } else {
            return redirect('admin/forums')->with('error', 'Erro ao inserir o registro!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $forum = $this->forum->find($id);
        $forumcomments = $this->forumComment->where('forum_id',$id)->get();
        $opnionscomment = $this->forumOpnion->all();

        $inscriptions = $this->inscription->where('discipline_id', $forum->discipline_id)->where('status','pago')->get();

        if ($forum) {
            return view('admin.forums.show',[
                'forum' => $forum, 
                'inscriptions' => $inscriptions, 
                'forumcomments' => $forumcomments, 
                'opnionscomment' => $opnionscomment
            ]);
        }

        return redirect('admin/forums')->with('alert', 'Registro não encontrado!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $courses = $this->course->where('status','active')->get();
        $disciplines = $this->discipline->all();
        
        $forum = $this->forum->find($id);
        if ($forum) {
            return view('admin.forums.edit',['forum' => $forum, 'courses' => $courses, 'disciplines' => $disciplines]);
        }

        return redirect('admin/forums')->with('alert', 'Registro não encontrado!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $forum = $this->forum->find($id);
        if (!$forum) {
            return redirect('admin/forums')->with('alert', 'Registro não encontrado!');
        }

        Validator::make($data, [
            'course_id' => 'required',
            'discipline_id' => 'required',
            'title' => 'required|string|max:100',
            'description' => 'required|string',
        ])->validate();

        if($forum->update($data)) {
            return redirect('admin/forums')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/forums')->with('error', 'Erro ao alterado o registro!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = $this->forum->find($id);
        if($data->delete()) {
            return redirect('admin/forums')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/forums')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
