<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Establishment; 
use App\Models\ScanEstablishment; 
use App\Models\ScanUser; 
use App\Models\User; 
use App\Http\Requests\Scan\StoreScanRequest;
use App\Http\Requests\Scan\GetScanRequest;
use Illuminate\Support\Str;

class ScanController extends Controller
{
    public function index(GetScanRequest $request) { 
        $validated = $request->validated();
        $user_id = auth()->user()->id; 
        $item = $request->validated ?? 10;
        
        if($request->type == 'EST') { 
            $data = ScanEstablishment::where('user_id', $user_id)->paginate($item);
        }else { 
            $data = ScanUser::where('user_id', $user_id)->paginate($item); 
        }

        return response()->json($data);
    }

    public function store(StoreScanRequest $request) { 
        try { 
            $validated = $request->validated(); 
            
            $isEstablishment = Str::contains($request->code, 'EST-');

            if($isEstablishment) { 
                $data['establishment'] = Establishment::where('establishment_code', $request->code)->first();

                if(isset($data['establishment'])) { 
                    ScanEstablishment::create([
                        'user_id' => auth()->user()->id, 
                        'establishment_id' => $data['establishment']->id,
                    ]);
                }
            } else { 
                $data['user'] = User::where('id', $request->code)->first();

                if(isset($data['user'])) { 
                    ScanUser::create([
                        'user_id' => auth()->user()->id, 
                        'scanned_user_id' => $data['user']->id,
                    ]);
                }
            }

            return response()->json($data);
        } catch (\Exception $e) { 
            return response()->json([
                'message' => 'Something went wrong'
            ], 500);
        }
    }
}
