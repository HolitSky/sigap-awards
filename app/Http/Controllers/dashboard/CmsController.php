<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\CardBox;
use App\Models\LaunchDate;
use App\Models\MenuChoice;
use App\Models\ModalInfo;
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
            'date_type' => 'required|in:single,range,month_only,coming_soon',
            'single_date' => 'required_if:date_type,single|nullable|date',
            'start_date' => 'required_if:date_type,range|nullable|date',
            'end_date' => 'required_if:date_type,range|nullable|date|after_or_equal:start_date',
            'month_year' => 'required_if:date_type,month_only|nullable|string',
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
            'date_type' => $request->date_type,
            'is_range_date' => $request->date_type == 'range' ? true : false,
            'order' => $newOrder,
            'is_active' => $request->has('is_active') ? true : false,
        ];

        // Set dates based on type
        switch ($request->date_type) {
            case 'single':
                $data['single_date'] = $request->single_date;
                $data['start_date'] = null;
                $data['end_date'] = null;
                $data['month_year'] = null;
                break;

            case 'range':
                $data['single_date'] = null;
                $data['start_date'] = $request->start_date;
                $data['end_date'] = $request->end_date;
                $data['month_year'] = null;
                break;

            case 'month_only':
                $data['single_date'] = null;
                $data['start_date'] = null;
                $data['end_date'] = null;
                $data['month_year'] = $request->month_year;
                break;

            case 'coming_soon':
                $data['single_date'] = null;
                $data['start_date'] = null;
                $data['end_date'] = null;
                $data['month_year'] = null;
                break;
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
            'date_type' => 'required|in:single,range,month_only,coming_soon',
            'single_date' => 'required_if:date_type,single|nullable|date',
            'start_date' => 'required_if:date_type,range|nullable|date',
            'end_date' => 'required_if:date_type,range|nullable|date|after_or_equal:start_date',
            'month_year' => 'required_if:date_type,month_only|nullable|string',
            'order' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'title' => $request->title,
            'date_type' => $request->date_type,
            'is_range_date' => $request->date_type == 'range' ? true : false,
            'order' => $request->order ? (int)$request->order - 1 : 0,
            'is_active' => $request->has('is_active') ? true : false,
        ];

        // Set dates based on type
        switch ($request->date_type) {
            case 'single':
                $data['single_date'] = $request->single_date;
                $data['start_date'] = null;
                $data['end_date'] = null;
                $data['month_year'] = null;
                break;

            case 'range':
                $data['single_date'] = null;
                $data['start_date'] = $request->start_date;
                $data['end_date'] = $request->end_date;
                $data['month_year'] = null;
                break;

            case 'month_only':
                $data['single_date'] = null;
                $data['start_date'] = null;
                $data['end_date'] = null;
                $data['month_year'] = $request->month_year;
                break;

            case 'coming_soon':
                $data['single_date'] = null;
                $data['start_date'] = null;
                $data['end_date'] = null;
                $data['month_year'] = null;
                break;
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

    /**
     * Display modal info management page
     */
    public function modalInfoIndex()
    {
        $title = 'Manajemen Modal Info';
        $pageTitle = 'Manajemen Modal Info';
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard.index')],
            ['name' => 'CMS', 'url' => '#'],
            ['name' => 'Modal Info', 'active' => true]
        ];

        $modalInfos = ModalInfo::orderBy('id')->get();
        return view('dashboard.pages.cms.modal-info.index', compact('modalInfos', 'title', 'pageTitle', 'breadcrumbs'));
    }

    /**
     * Update modal info
     */
    public function modalInfoUpdate(Request $request, $id)
    {
        $modalInfo = ModalInfo::findOrFail($id);

        // Different validation based on modal type
        if ($modalInfo->modal_type === 'reminder') {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|min:4|max:100',
                'subtitle' => 'required|string|min:10|max:200'
            ]);
        } else { // welcome modal
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|min:4|max:100',
                'intro_text' => 'required|string|min:10|max:200',
                'footer_text' => 'required|string|min:10|max:200'
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update based on modal type
        if ($modalInfo->modal_type === 'reminder') {
            $modalInfo->update([
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'is_show' => $request->has('is_show') ? true : false
            ]);
        } else { // welcome modal
            // Process meta_links JSON
            $metaLinks = [];
            if ($request->has('meta_links')) {
                $links = json_decode($request->meta_links, true);
                if (is_array($links)) {
                    $metaLinks = $links;
                }
            }

            $modalInfo->update([
                'title' => $request->title,
                'intro_text' => $request->intro_text,
                'footer_text' => $request->footer_text,
                'show_form_options' => $request->has('show_form_options') ? true : false,
                'meta_links' => $metaLinks,
                'is_show' => $request->has('is_show') ? true : false
            ]);
        }

        return redirect()->route('dashboard.cms.modal-info.index')
            ->with('success', 'Modal info berhasil diupdate');
    }

    /**
     * Display card box management page
     */
    public function cardBoxIndex()
    {
        $title = 'Manajemen Card Box';
        $pageTitle = 'Manajemen Card Box';
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard.index')],
            ['name' => 'CMS', 'url' => '#'],
            ['name' => 'Card Box', 'active' => true]
        ];

        $cardBoxes = CardBox::ordered()->get();
        return view('dashboard.pages.cms.card-box.index', compact('cardBoxes', 'title', 'pageTitle', 'breadcrumbs'));
    }

    /**
     * Store a new card box
     */
    public function cardBoxStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:4|max:100',
            'description' => 'required|string|min:10|max:500',
            'content_type' => 'required|in:text_only,link,modal',
            'button_text' => 'required_if:content_type,link,modal|nullable|string|max:50',
            'link_url' => 'required_if:content_type,link|nullable|url|max:255',
            'modal_content' => 'required_if:content_type,modal|nullable|string',
            'order' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get max order and set new order
        $maxOrder = CardBox::max('order') ?? -1;
        $newOrder = $request->order ? (int)$request->order - 1 : ($maxOrder + 1);

        $isActive = $request->has('is_active') ? true : false;

        // Jika set aktif, nonaktifkan semua card box lain
        if ($isActive) {
            CardBox::where('is_active', true)->update(['is_active' => false]);
        }

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'content_type' => $request->content_type,
            'button_text' => in_array($request->content_type, ['link', 'modal']) ? $request->button_text : null,
            'link_url' => $request->content_type === 'link' ? $request->link_url : null,
            'modal_content' => $request->content_type === 'modal' ? $request->modal_content : null,
            'order' => $newOrder,
            'is_active' => $isActive,
        ];

        CardBox::create($data);

        return redirect()->route('dashboard.cms.card-box.index')
            ->with('success', 'Card box berhasil ditambahkan');
    }

    /**
     * Update card box
     */
    public function cardBoxUpdate(Request $request, $id)
    {
        $cardBox = CardBox::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:4|max:100',
            'description' => 'required|string|min:10|max:500',
            'content_type' => 'required|in:text_only,link,modal',
            'button_text' => 'required_if:content_type,link,modal|nullable|string|max:50',
            'link_url' => 'required_if:content_type,link|nullable|url|max:255',
            'modal_content' => 'required_if:content_type,modal|nullable|string',
            'order' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $isActive = $request->has('is_active') ? true : false;

        // Jika set aktif, nonaktifkan semua card box lain kecuali yang sedang diedit
        if ($isActive) {
            CardBox::where('is_active', true)
                ->where('id', '!=', $id)
                ->update(['is_active' => false]);
        }

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'content_type' => $request->content_type,
            'button_text' => in_array($request->content_type, ['link', 'modal']) ? $request->button_text : null,
            'link_url' => $request->content_type === 'link' ? $request->link_url : null,
            'modal_content' => $request->content_type === 'modal' ? $request->modal_content : null,
            'order' => $request->order ? (int)$request->order - 1 : 0,
            'is_active' => $isActive,
        ];

        $cardBox->update($data);

        return redirect()->route('dashboard.cms.card-box.index')
            ->with('success', 'Card box berhasil diupdate');
    }

    /**
     * Delete card box
     */
    public function cardBoxDestroy($id)
    {
        $cardBox = CardBox::findOrFail($id);
        $cardBox->delete();

        return response()->json([
            'success' => true,
            'message' => 'Card box berhasil dihapus'
        ]);
    }

    /**
     * Update order of card boxes
     */
    public function cardBoxUpdateOrder(Request $request)
    {
        $orders = $request->input('orders', []);

        foreach ($orders as $order) {
            CardBox::where('id', $order['id'])
                ->update(['order' => $order['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan berhasil diupdate'
        ]);
    }

    /**
     * Display menu choices management page
     */
    public function menuChoiceIndex()
    {
        $title = 'Menu Choices';
        $menuChoices = MenuChoice::latest()->get();
        return view('dashboard.pages.cms.menu-choices.index', compact('title', 'menuChoices'));
    }

    /**
     * Store new menu choice
     */
    public function menuChoiceStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'main_menu_title' => 'nullable|string|max:100',
            'use_main_menu' => 'required|boolean',
            'menu_items' => 'required|json',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Decode and validate menu items
        $menuItems = json_decode($request->menu_items, true);
        if (!is_array($menuItems) || empty($menuItems)) {
            return redirect()->back()
                ->with('error', 'Menu items harus berisi minimal 1 item')
                ->withInput();
        }

        // Auto-deactivate other active menu choices if this one is active
        $isActive = $request->has('is_active');
        if ($isActive) {
            MenuChoice::where('is_active', true)->update(['is_active' => false]);
        }

        MenuChoice::create([
            'main_menu_title' => $request->main_menu_title,
            'use_main_menu' => $request->use_main_menu,
            'menu_items' => $menuItems,
            'is_active' => $isActive,
        ]);

        return redirect()->route('dashboard.cms.menu-choice.index')
            ->with('success', 'Menu choice berhasil ditambahkan');
    }

    /**
     * Update menu choice
     */
    public function menuChoiceUpdate(Request $request, $id)
    {
        $menuChoice = MenuChoice::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'main_menu_title' => 'nullable|string|max:100',
            'use_main_menu' => 'required|boolean',
            'menu_items' => 'required|json',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Decode and validate menu items
        $menuItems = json_decode($request->menu_items, true);
        if (!is_array($menuItems) || empty($menuItems)) {
            return redirect()->back()
                ->with('error', 'Menu items harus berisi minimal 1 item')
                ->withInput();
        }

        // Auto-deactivate other active menu choices if this one is active
        $isActive = $request->has('is_active');
        if ($isActive) {
            MenuChoice::where('id', '!=', $id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $menuChoice->update([
            'main_menu_title' => $request->main_menu_title,
            'use_main_menu' => $request->use_main_menu,
            'menu_items' => $menuItems,
            'is_active' => $isActive,
        ]);

        return redirect()->route('dashboard.cms.menu-choice.index')
            ->with('success', 'Menu choice berhasil diupdate');
    }

    /**
     * Delete menu choice
     */
    public function menuChoiceDestroy($id)
    {
        $menuChoice = MenuChoice::findOrFail($id);
        $menuChoice->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu choice berhasil dihapus'
        ]);
    }
}
