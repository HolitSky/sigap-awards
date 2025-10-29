<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\LaunchDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CmsController extends Controller
{
    /**
     * Display launch date management page
     */
    public function launchDateIndex()
    {
        $title = 'Manajemen Launch Date';
        $pageTitle = 'Manajemen Launch Date';
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard.index')],
            ['name' => 'CMS', 'url' => '#'],
            ['name' => 'Launch Date', 'active' => true]
        ];
        
        $launchDates = LaunchDate::orderBy('order')->orderBy('created_at', 'desc')->get();
        return view('dashboard.pages.cms.launch-date.index', compact('launchDates', 'title', 'pageTitle', 'breadcrumbs'));
    }

    /**
     * Store a new launch date
     */
    public function launchDateStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:4|max:50',
            'is_range_date' => 'required',
            'single_date' => 'required_if:is_range_date,0|nullable|date',
            'start_date' => 'required_if:is_range_date,1|nullable|date',
            'end_date' => 'required_if:is_range_date,1|nullable|date|after_or_equal:start_date',
            'order' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get max order and set new order
        $maxOrder = LaunchDate::max('order') ?? -1;
        $newOrder = $request->order ? (int)$request->order - 1 : ($maxOrder + 1);
        
        $data = [
            'title' => $request->title,
            'is_range_date' => $request->is_range_date == '1' ? true : false,
            'order' => $newOrder, // Auto increment if not specified
            'is_active' => $request->has('is_active') ? true : false,
        ];

        // Clear single_date if range_date is true
        if ($data['is_range_date']) {
            $data['single_date'] = null;
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
        } else {
            // Clear range dates if single date
            $data['single_date'] = $request->single_date;
            $data['start_date'] = null;
            $data['end_date'] = null;
        }

        LaunchDate::create($data);

        return redirect()->route('dashboard.cms.launch-date.index')
            ->with('success', 'Launch date berhasil ditambahkan');
    }

    /**
     * Update launch date
     */
    public function launchDateUpdate(Request $request, $id)
    {
        $launchDate = LaunchDate::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:4|max:50',
            'is_range_date' => 'required',
            'single_date' => 'required_if:is_range_date,0|nullable|date',
            'start_date' => 'required_if:is_range_date,1|nullable|date',
            'end_date' => 'required_if:is_range_date,1|nullable|date|after_or_equal:start_date',
            'order' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'title' => $request->title,
            'is_range_date' => $request->is_range_date == '1' ? true : false,
            'order' => $request->order ? (int)$request->order - 1 : 0, // Convert visual number (1,2,3) to database order (0,1,2)
            'is_active' => $request->has('is_active') ? true : false,
        ];

        // Clear single_date if range_date is true
        if ($data['is_range_date']) {
            $data['single_date'] = null;
            $data['start_date'] = $request->start_date;
            $data['end_date'] = $request->end_date;
        } else {
            // Clear range dates if single date
            $data['single_date'] = $request->single_date;
            $data['start_date'] = null;
            $data['end_date'] = null;
        }

        $launchDate->update($data);

        return redirect()->route('dashboard.cms.launch-date.index')
            ->with('success', 'Launch date berhasil diupdate');
    }

    /**
     * Delete launch date
     */
    public function launchDateDestroy($id)
    {
        $launchDate = LaunchDate::findOrFail($id);
        $launchDate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Launch date berhasil dihapus'
        ]);
    }

    /**
     * Update order of launch dates
     */
    public function launchDateUpdateOrder(Request $request)
    {
        $orders = $request->input('orders', []);

        foreach ($orders as $order) {
            LaunchDate::where('id', $order['id'])
                ->update(['order' => $order['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan berhasil diupdate'
        ]);
    }
}
