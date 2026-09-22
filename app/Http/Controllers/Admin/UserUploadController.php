<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Admin\Ebook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class UserUploadController extends Controller
{
    public function index()
    {
        $user=User::where('level','=','SUPERUSER')->orWhere('level','=','ADMIN')
            ->orWhere('level','=','OPERATOR')->get();
        return view('admin.user-upload.index',compact('user'));
    }
    
    public function details(Request $request)
    {
        $iduser=$request->id;
        $user=User::find($iduser);
        //$data=Ebook::where('id_user','=',$iduser)->orderBy('created_at','DESC')->get();
        return view('admin.user-upload.details-upload',compact('user'));
    }
    
    public function ajax(Request $request, $id = null)
    {
        $iduser = $id ?: $request->route('id') ?: $request->id;
        
        if (empty($iduser)) {
            return response()->json([
                'draw'            => intval($request->input('draw', 0)),
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
                'error'           => 'Missing user id',
            ]);
        }
        
        // Columns expected by the DataTable (matches details-upload.blade.php)
        $columns = ['DT_Row_Index', 'judul', 'penulis', 'tahun', 'created_at', 'action'];
        
        // Base query
        $baseQuery = Ebook::where('id_user', '=', $iduser);
        
        // Total records (without filtering)
        $totalRecords = $baseQuery->count();
        
        // Search value
        $searchValue = $request->input('search.value', '');
        
        // Filtered query
        $filteredQuery = Ebook::where('id_user', '=', $iduser);
        if (!empty($searchValue)) {
            $filteredQuery->where(function($q) use ($searchValue) {
                $q->where('judul',   'like', '%'.$searchValue.'%')
                  ->orWhere('penulis','like', '%'.$searchValue.'%')
                  ->orWhere('tahun',  'like', '%'.$searchValue.'%')
                  ->orWhere('created_at','like', '%'.$searchValue.'%');
            });
        }
        
        $filteredRecords = $filteredQuery->count();
        
        // Ordering
        $orderColumnIdx = (int) $request->input('order.0.column', 4);
        $orderDir       = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $orderColumn    = $columns[$orderColumnIdx] ?? 'created_at';
        if (!in_array($orderColumn, ['judul', 'penulis', 'tahun', 'created_at'], true)) {
            $orderColumn = 'created_at';
        }
        $filteredQuery->orderBy($orderColumn, $orderDir);
        
        // Pagination
        $start  = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        if ($length !== -1) {
            $filteredQuery->offset($start)->limit($length);
        }
        
        $ebooks = $filteredQuery->get();
        
        // Build data payload
        $data = [];
        foreach ($ebooks as $i => $ebook) {
            $data[] = [
                'DT_Row_Index' => $start + $i + 1,
                'judul'        => $ebook->judul,
                'penulis'      => $ebook->penulis,
                'tahun'        => $ebook->tahun,
                'created_at'   => date('d-m-Y', strtotime($ebook->created_at)),
                'action'       => '<a href="'.route('gen.ebook.detail', ['id' => $ebook->id_ebook]).'" class="btn btn-sm btn-warning"><i data-feather="eye"></i> Detail</a>',
            ];
        }
        
        return response()->json([
            'draw'            => intval($request->input('draw', 0)),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data'            => $data,
        ]);
    }
}