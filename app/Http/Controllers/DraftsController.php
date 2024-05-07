<?php

namespace App\Http\Controllers;

use App\Helpers\CleanInputs;
use App\Http\Requests\DraftRequest;
use App\Models\DraftsModel;

use App\Models\ServiceCredentialsModel;
use Illuminate\Support\Facades\Auth;

class DraftsController extends Controller
{
    private int $id = 0;
    public function store(DraftRequest $request)
    {
        $notification = $request->validated();
        cleanInputs($notification);
        $notification['user_id'] = Auth::id();
        if (isset($notification['service']))  $notification['service_id'] = ServiceCredentialsModel::where('user_id', Auth::id())->where('service_name', $notification['service'])->select('id')->first()->id ?? null;
        if (isset($notification['scheduled_date'], $notification['scheduled_time']))
            $notification['scheduled_at'] = $notification['scheduled_date'] . ' ' . $notification['scheduled_time'];
        if (is_array($notification['to'])) $notification['to'] = json_encode($notification['to']);

        $draft = DraftsModel::create($notification);

        $this->id = $draft->id;
        return response()->json(['status' => 'success', 'id' => $this->id]);
    }

    public function index()
    {
        $drafts = DraftsModel::where('user_id', Auth::id())->paginate(6);
        foreach ($drafts as $draft) {
            if (is_array(array($draft['to']))) $draft['to'] = json_decode($draft['to']);
        }
        return response()->json($drafts);
    }



    public function show($id)
    {
        $draft = DraftsModel::find($id);
        if (!empty($draft))
            $draft['server'] = ServiceCredentialsModel::where('user_id', Auth::id())->where('id', $draft['service_id'])->first()->service_name ?? null;

        if (is_array($draft['to'])) $draft['to'] = json_decode($draft['to']);



        return response()->json(['status' => 'success', 'draft' => $draft]);
    }

    public function destroy($id)
    {
        $current_draft = DraftsModel::find($id);
        if (!$current_draft)            return response()->json(['status' => 'error'], 420);

        $current_draft->delete();
        return response()->json(['status' => 'success', 'message' => 'Draft deleted successfully !!']);
    }
    public function update(DraftRequest $request, $id)
    {
        $notification = $request->validated();
        cleanInputs($notification);


        if ($id) {
            $notification['user_id'] = Auth::id();

            if (isset($notification['service']))  $notification['service_id'] = ServiceCredentialsModel::where('user_id', Auth::id())->where('service_name', $notification['service'])->select('id')->first()->id ?? null;
            if (isset($notification['scheduled_date'], $notification['scheduled_time']))
                $notification['scheduled_at'] = $notification['scheduled_date'] . ' ' . $notification['scheduled_time'];

            $current_draft = DraftsModel::find($id);
            $current_draft->update($notification);
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error'], 420);
        }
    }
}
