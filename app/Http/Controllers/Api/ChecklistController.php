<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Checklist, ChecklistItem};
use Illuminate\Support\Facades\Validator;

class ChecklistController extends Controller
{
    public function index()
    {
        $checklists = Checklist::all();
        return response()->json(['success' => true, 'data' => $checklists, 'message' => 'Get checklist success']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        $checklist = Checklist::create([
            'name' => $request->name,
        ]);

        return response()->json(['success' => true, 'data' => $checklist, 'message' => 'Create new checklist success'], 201);
    }

    public function destroy($id)
    {
        $checklist = Checklist::find($id);
        if (!$checklist) {
            return response()->json(['success' => false, 'errors' => 'Checklist not found'], 404);
        }

        $checklist->delete();
        return response()->json(['success' => true, 'data' => [], 'message' => 'Checklist deleted successfully'], 200);
    }

    public function getChecklistItems($checklistId)
    {
        $checklist = Checklist::find($checklistId);
        if (!$checklist) {
            return response()->json(['success' => false, 'errors' => 'Checklist not found'], 404);
        }

        $items = $checklist->items;
        return response()->json([
            'success' => true,
            'data' => $items,
            'message' => 'Get checklist items success'
        ]);
    }

    public function createChecklistItem(Request $request, $checklistId)
    {
        $validator = Validator::make($request->all(), [
            'itemName' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        $checklist = Checklist::find($checklistId);
        if (!$checklist) {
            return response()->json(['success' => false, 'errors' => 'Checklist not found'], 404);
        }

        $item = ChecklistItem::create([
            'checklist_id' => $checklistId,
            'item_name' => $request->itemName,
        ]);

        return response()->json(['success' => true, 'data' => $item, 'message' => 'Create new cheklist item success'], 201);
    }

    public function getChecklistItem($checklistId, $checklistItemId)
    {
        $checklist = Checklist::find($checklistId);
        // dd($checklist);
        if (!$checklist) {
            return response()->json(['success' => false, 'errors' => 'Checklist not found'], 404);
        }

        $item = $checklist->items()->find($checklistItemId);
        if (!$item) {
            return response()->json(['success' => false, 'errors' => 'Checklist item not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $item, 'message' => 'Get checklist item success']);
    }

    public function updateChecklistItem(Request $request, $checklistId, $checklistItemId)
    {
        $checklist = Checklist::find($checklistId);
        if (!$checklist) {
            return response()->json(['success' => false, 'errors' => 'Checklist not found'], 404);
        }

        $item = $checklist->items()->find($checklistItemId);
        if (!$item) {
            return response()->json(['success' => false, 'errors' => 'Checklist item not found'], 404);
        }

        if ($request->itemName) {

            $validator = Validator::make($request->all(), [
                'itemName' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
            }

            $item->item_name = $request->itemName;
        } else {
            $item->status = 1;
        }

        $item->save();

        return response()->json(['success' => true, 'data' => $item, 'message' => 'Update checklist item success'], 200);
    }

    public function deleteChecklistItem($checklistId, $checklistItemId)
    {
        $checklist = Checklist::find($checklistId);
        if (!$checklist) {
            return response()->json(['success' => false, 'errors' => 'Checklist not found'], 404);
        }

        $item = $checklist->items()->find($checklistItemId);
        if (!$item) {
            return response()->json(['success' => false, 'errors' => 'Checklist item not found'], 404);
        }

        $item->delete();
        return response()->json(['success' => true, 'data' => [], 'message' => 'Checklist item deleted successfully'], 200);
    }
}
