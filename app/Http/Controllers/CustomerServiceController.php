<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerService;
use App\Models\CustomerServiceComment;
use App\Models\User;

class CustomerServiceController extends Controller
{
    private $customerService;
    private $customerServiceComment;
    private $user;

    public function __construct(CustomerService $customerService, CustomerServiceComment $customerServiceComment, User $user) 
    {
        $this->middleware('auth');
        $this->customerService = $customerService;
        $this->customerServiceComment = $customerServiceComment;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $records = $this->customerService->all();

        $query = $this->customerService->query();

        $search_status = $request->status;
        $search_sector = $request->sector;

        if (isset($search_status)) {
            $query->where('status', $search_status);
        }

        if (isset($search_sector)) {
            $query->where('sector', $search_sector);
        }

        $customerservices = $query->orderBY('id','DESC')->paginate(10);

        return view('admin.customerservices.index', [
            'customerservices' => $customerservices,
            'records' => $records,
            'search_status' => $search_status,
            'search_sector' => $search_sector
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.customerServices.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'user_id' => 'required',
            'subject' => 'required|string',
            'description' => 'required|string',
        ])->validate();

        if($this->customerService->create($data)) {
            return redirect('admin/customerServices')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/customerServices/create')->with('error', 'Erro ao inserir o registro!');
        }
    }

    /**
     * Store comments at customer service
     */
    public function store_comment(Request $request)
    {
        $data = $request->all();
        Validator::make($data, [
            'comment' => 'required|string',
        ])->validate();

        $user = auth()->user()->id;

        $data['user_id'] = $user;
        $data['view_user'] = true;
        $data['view_student'] = false;

        if($this->customerServiceComment->create($data)) {

            //$affected = DB::table('customer_service_comments')->where('user_id', $user)->where('customer_service_id', $data['customer_service_id'])->update(['view_student' => 0]);

            return redirect('admin/customerservice/show/' . $data['customer_service_id'])->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/customerservice/show/' . $data['customer_service_id'])->with('error', 'Erro ao inserir o registro!');
        }
    }

    public static function confirm_view ($id) {
        $affected = DB::table('customer_service_comments')->where('customer_service_id', $id)->update(['view_user' => 0]);
    }

    /**
     * Display the specified resource.
     */
    public function show (string $id)
    {
        $atendiment = $this->customerService->find($id);
        if (!$atendiment) {
            return redirect('admin/customerservices')->with('alert', 'Registro não encontrado!');
        }
        // update view comment student
        $affected = DB::table('customer_service_comments')->where('customer_service_id', $id)->update(['view_student' => 0]);
        
        $records = $this->customerService->where('user_id', $atendiment->user_id)->get();

        $customercomments = $this->customerServiceComment
                                 ->where('customer_service_id', $id)
                                 ->orderBy('id','DESC')->get();

        $customerservices = $this->customerService->orderBY('id','DESC')->paginate(5);

        return view('admin.customerservices.show', [
            'atendiment' => $atendiment,
            'customerservices' => $customerservices,
            'customercomments' => $customercomments,
            'records' => $records
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customerService = $this->customerService->find($id);
        if ($customerService) {
            return view('admin.customerServices.edit',['customerservice' => $customerService]);
        }
        return redirect('admin/customerServices')->with('alert', 'Registro não encontrado!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $customerService = $this->customerService->find($id);

        if (!$customerService) {
            return redirect('admin/customerservices')->with('alert', 'Registro não encontrado!');
        }

        Validator::make($data, [
            'subject' => 'required|string',
            'description' => 'required|string',
        ])->validate();

        if($customerService->update($data)) {
            return redirect('admin/customerservices')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/customerservices')->with('error', 'Erro ao alterado o registro!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = $this->customerService->find($id);
        if($data->delete()) 
        {
            return redirect('admin/customerservices')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/customerservices')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
