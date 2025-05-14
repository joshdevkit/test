<?php

namespace App\Http\Controllers;

use App\Models\LaboratoryEquipmentItem;
use Illuminate\Http\Request;

class LaboratoryItemsController extends Controller
{
    public function check(Request $request)
    {
        $serial = $request->input('serial');
        $exactMatch = LaboratoryEquipmentItem::where("serial_no", $serial)->first();
        $similarItems = LaboratoryEquipmentItem::where("serial_no", 'like', '%' . $serial . '%')->get();
        if ($exactMatch) {
            return response()->json([
                'exist' => true,
            ]);
        } elseif ($similarItems->count() > 0) {
            return response()->json([
                'exist' => true,
            ]);
        }
    }
}
