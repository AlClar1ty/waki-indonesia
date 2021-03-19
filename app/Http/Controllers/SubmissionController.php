<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Cso;
use App\DeliveryOrder;
use App\HistoryUpdate;
use App\RajaOngkir_City;
use App\Reference;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Validator;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function listSubmission(Request $request)
    {
        // Menyimpan request ke dalam variabel $url untuk pagination
        $url = $request->all();

        // Memasukkan klausa WHERE yang digunakan berkali-kali ke dalam array
        $whereArray = [];
        $whereArray[] = ["active", true];
        $whereArray[] = ["type_register", "!=", "Normal Register"];

        if (Auth::user()->roles[0]->slug === "cso") {
            // Jika user adalah CSO
            // Push klausa WHERE untuk CSO ke dalam array
            $whereArray[] = [
                "cso_id",
                Auth::user()->cso["id"],
            ];

            $deliveryOrders = DeliveryOrder::where($whereArray)->paginate(10);
        } elseif (
            Auth::user()->roles[0]->slug === "branch"
            || Auth::user()->roles[0]->slug === "area-manager"
        ) {
            // Jika user adalah BRANCH atau AREA MANAGER
            // Inisialisasi variabel $arrBranches untuk menyimpan hasil query
            $arrBranches = [];

            // Menyimpan BRANCH yang dipegang oleh user ke dalam $arrBranches
            foreach (Auth::user()->listBranches() as $value) {
                array_push($arrBranches, $value['id']);
            }

            $deliveryOrders = DeliveryOrder::WhereIn(
                "branch_id",
                $arrBranches
            )
            ->where($whereArray)
            ->paginate(10);
        } else {
            $deliveryOrders = DeliveryOrder::where($whereArray)->paginate(10);
        }

        return view(
            "admin.list_submission_form",
            compact(
                "deliveryOrders",
                "url",
            )
        );
    }

    public function listReference(Request $request)
    {
        // Menyimpan request ke dalam variabel $url untuk pagination
        $url = $request->all();

        // Query dari tabel references, dan menampilkan 10 data per halaman
        $references = Reference::all()->paginate(10);

        return view("admin.list_reference", compact("references", "url"));
    }

    public function detailSubmission(Request $request)
    {
        $deliveryOrder = DeliveryOrder::where("id", $request["id"])->first();
        $deliveryOrder["district"] = array($deliveryOrder->getDistrict());
        $references = Reference::where(
            "deliveryorder_id",
            $request["id"]
        )
        ->orderBy("id", "asc")
        ->get();

        // Melakukan query nama kota dan provinsi untuk referensi
        $referencesCityAndProvince = [];
        $getReferencesCityAndProvince = RajaOngkir_City::leftJoin(
            "references",
            "references.city",
            "=",
            "raja_ongkir__cities.city_id"
        )
        ->select(
            "raja_ongkir__cities.province",
            "raja_ongkir__cities.type",
            "raja_ongkir__cities.city_name",
        )
        ->where("references.deliveryorder_id", $request["id"])
        ->orderBy("references.id")
        ->get();

        foreach ($getReferencesCityAndProvince as $refCityAndProvince) {
            $referencesCityAndProvince[] = $refCityAndProvince->type
                . " "
                . $refCityAndProvince->city_name
                . ", "
                . $refCityAndProvince->province;
        }

        // Melakukan query riwayat dari tabel history_updates
        $historyUpdateDeliveryOrder = HistoryUpdate::leftjoin(
            'users',
            'users.id',
            '=',
            'history_updates.user_id'
        )
        ->select(
            'history_updates.method',
            'history_updates.created_at',
            'history_updates.meta AS meta',
            'users.name AS name'
        )
        ->where('type_menu', 'Delivery Order')
        ->where('menu_id', $deliveryOrder->id)
        ->get();

        return view(
            "admin.detail_submission",
            compact(
                "deliveryOrder",
                "historyUpdateDeliveryOrder",
                "references",
                "referencesCityAndProvince",
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $promos = DeliveryOrder::$Promo;
        $branches = Branch::where('active', true)->get();
        $csos = Cso::all();

        return view('admin.add_submission_form', compact('promos', 'branches', 'csos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->all();

            $data['code'] = "SUB_M/" . strtotime(date("Y-m-d H:i:s")) . "/" . substr($data['phone'], -4);
            $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];

            // Pembentukan array product
            $data['arr_product'] = [];
            foreach ($data as $key => $value) {
                $arrKey = explode("_", $key);
                if($arrKey[0] == 'product'){
                    if(isset($data['qty_'.$arrKey[1]])){
                        $data['arr_product'][$key] = [];
                        $data['arr_product'][$key]['id'] = $value;

                        // {{-- KHUSUS Philiphin --}}
                        if($value == 'other'){
                            $data['arr_product'][$key]['id'] = $data['product_other_'.$arrKey[1]];
                        }
                        // ===========================

                        $data['arr_product'][$key]['qty'] = $data['qty_'.$arrKey[1]];
                    }
                }
            }

            $data['arr_product'] = json_encode($data['arr_product']);

            $data["province"] = $data["province_id"];

            // Mengecek apakah ada gambar yang diupload atau tidak
            if ($request->hasFile("image_proof")) {
                // Inisialisasi variabel data["image"]
                $data["image"] = [];
                // Inisialisasi jalur di mana gambar akan diletakkan
                $path = "sources/registration";

                // Mengecek apakah jalur untuk gambar sudah ada atau belum
                if (!is_dir($path)) {
                    // Jika belum, maka akan membuat folder/directory baru
                    File::makeDirectory($path, 0777, true, true);
                }

                // Inisialisasi variabel $j untuk counter dan sebagai suffix nama file
                $j = 0;
                foreach ($request->file("image_proof") as $image) {
                    // Inisialisasi nama gambar dengan UNIX Timestamp
                    $fileName = strval(time());
                    // Memberikan suffix dengan $j agar nama gambar tidak sama, dan juga menambahkan file extension
                    $fileName .= "_" . $j . "." . $image->getClientOriginalExtension();
                    // Menyimpan gambar
                    $image->move($path, $fileName);
                    // Push nama gambar ke dalam variabel data["image"]
                    $data["image"][] = $fileName;
                    // Increment variabel $j
                    $j++;
                }

                // Convert array pada $data["image"] menjadi JSON
                $data["image"] = json_encode($data["image"], JSON_FORCE_OBJECT);
            }

            // Memasukkan data delivery order ke tabel delivery_orders
            $deliveryOrder = DeliveryOrder::create($data);

            // Memasukkan data referensi ke tabel Reference
            $dataCount = count($data["name_ref"]);
            for ($i = 0; $i < $dataCount; $i++) {
                $reference = new Reference();
                $reference->name = $data["name_ref"][$i];
                $reference->age = $data["age_ref"][$i];
                $reference->phone = $data["phone_ref"][$i];
                $reference->province = $data["province_ref"][$i];
                $reference->city = $data["city_ref"][$i];
                $reference->deliveryorder_id = $deliveryOrder->id;
                $reference->save();
            }

            DB::commit();

            return redirect()->route("detail_submission_form", ["id" => $deliveryOrder->id]);
        } catch (Exception $ex) {
            DB::rollback();

            return response()->json([
                'error' => $ex,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if ($request->has("id")) {
            $branches = Branch::all();
            $csos = Cso::all();
            $deliveryOrders = DeliveryOrder::find($request->get("id"));
            $promos = DeliveryOrder::$Promo;
            $references = Reference::where(
                "deliveryorder_id",
                $request->get("id")
            )
            ->orderBy("id", "asc")
            ->get();

            $arrayReference = [];
            foreach ($references as $key => $reference) {
                $arrayReference[$key]["id"] = $reference->id;
                $arrayReference[$key]["name"] = $reference->name;
                $arrayReference[$key]["age"] = $reference->age;
                $arrayReference[$key]["phone"] = $reference->phone;
                $arrayReference[$key]["province"] = $reference->province;
                $arrayReference[$key]["city"] = $reference->city;
            }

            return view(
                "admin.update_submission_form",
                compact(
                    "branches",
                    "csos",
                    "deliveryOrders",
                    "promos",
                    "arrayReference",
                )
            );
        } else {
            return response()->json(['result' => 'Gagal!!']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Inisialisasi variabel $deliveryOrder untuk memperbarui data
        $deliveryOrder = DeliveryOrder::find($request->input("idDeliveryOrder"));

        DB::beginTransaction();

        try {
            $deliveryOrder->no_member = $request->input("no_member");
            $deliveryOrder->name = $request->input("name");
            $deliveryOrder->address = $request->input("address");
            $deliveryOrder->phone = $request->input("phone");

            // Pembentukan array product
            $data = $request->all();
            $data['arr_product'] = [];
            foreach ($data as $key => $value) {
                $arrKey = explode("_", $key);
                if ($arrKey[0] == 'product') {
                    if (isset($data['qty_'.$arrKey[1]])) {
                        $data['arr_product'][$key] = [];
                        $data['arr_product'][$key]['id'] = $value;
                        $data['arr_product'][$key]['qty'] = $data['qty_'.$arrKey[1]];
                    }
                }
            }

            $deliveryOrder->arr_product = json_encode($data['arr_product']);

            $getCSOid = Cso::where('code', $request->input("cso_id"))->first()['id'];
            $deliveryOrder->cso_id = $getCSOid;
            $deliveryOrder->branch_id = $request->input("branch_id");
            $deliveryOrder->province = $request->input("province_id");
            $deliveryOrder->city = $request->input("city");
            $deliveryOrder->distric = $request->input("distric");
            $deliveryOrder->type_register = $request->input("type_register");

            // Mengecek apakah ada gambar yang diupload atau tidak
            if ($request->hasFile("image_proof")) {
                // Inisialisasi variabel data["image"]
                $data["image"] = [];
                // Inisialisasi jalur di mana gambar akan diletakkan
                $path = "sources/registration";

                // Mengecek apakah jalur untuk gambar sudah ada atau belum
                if (!is_dir($path)) {
                    // Jika belum, maka akan membuat folder/directory baru
                    File::makeDirectory($path, 0777, true, true);
                }

                // Inisialisasi variabel $j untuk counter dan sebagai suffix nama file
                $j = 0;
                foreach ($request->file("image_proof") as $image) {
                    // Inisialisasi nama gambar dengan UNIX Timestamp
                    $fileName = strval(time());
                    // Memberikan suffix dengan $j agar nama gambar tidak sama, dan juga menambahkan file extension
                    $fileName .= "_" . $j . "." . $image->getClientOriginalExtension();
                    // Menyimpan gambar
                    $image->move($path, $fileName);
                    // Push nama gambar ke dalam variabel data["image"]
                    $data["image"][] = $fileName;
                    // Increment variabel $j
                    $j++;
                }

                // Convert array pada $data["image"] menjadi JSON
                $data["image"] = json_encode($data["image"], JSON_FORCE_OBJECT);

                $deliveryOrder->image = $data["image"];
            }

            // Menyimpan pembaruan data delivery_orders
            $deliveryOrder->save();

            //Inisialisasi variabel $user untuk riwayat pembaruan
            $user = Auth::user();

            // Mengisi variabel $historyDeliveryOrder dengan data yang diperbarui
            $historyDeliveryOrder = [];
            $historyDeliveryOrder["type_menu"] = "Delivery Order";
            $historyDeliveryOrder["method"] = "Update";
            $historyDeliveryOrder["meta"] = json_encode(
                [
                    "user" => $user["id"],
                    "createdAt" => date("Y-m-d h:i:s"),
                    "dataChange" => $deliveryOrder->getChanges(),
                ]
            );

            $historyDeliveryOrder["user_id"] = $user["id"];
            $historyDeliveryOrder["menu_id"] = $deliveryOrder->id;

            // Menyimpan riwayat pembaruan data delivery_orders ke tabel history_updates
            HistoryUpdate::create($historyDeliveryOrder);

            $dataCount = count($data["name_ref"]);
            for ($i = 0; $i < $dataCount; $i++) {
                $reference = Reference::find($data["id_reference"][$i]);

                $reference->name = $data["name_ref"][$i];
                $reference->age = $data["age_ref"][$i];
                $reference->phone = $data["phone_ref"][$i];
                $reference->province = $data["province_ref"][$i];
                $reference->city = $data["city_ref"][$i];

                // Menyimpan pembaruan data references
                $reference->save();

                // Mengisi variabel $historyReference dengan data yang diperbarui
                $historyReference = [];
                $historyReference["type_menu"] = "Reference";
                $historyReference["method"] = "Update";
                $historyReference["meta"] = json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d h:i:s"),
                        "dataChange" => $reference->getChanges(),
                    ]
                );

                $historyReference["user_id"] = $user["id"];
                $historyReference["menu_id"] = $reference->id;

                // Menyimpan riwayat pembaruan data references ke tabel history_updates
                HistoryUpdate::create($historyReference);
            }

            DB::commit();

            return redirect()->route('list_submission_form')
                ->with('success', 'Data berhasil diperbarui');
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                "error" => $e,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $deliveryOrder = DeliveryOrder::where("id", $id)->first();
            $deliveryOrder->active = false;
            $deliveryOrder->save();

            $user = Auth::user();
            $historyDeleteDeliveryOrder = [];
            $historyDeleteDeliveryOrder["type_menu"] = "Delivery Order";
            $historyDeleteDeliveryOrder["method"] = "Delete";
            $historyDeleteDeliveryOrder["meta"] = json_encode(
                [
                    "user" => $user["id"],
                    "createdAt" => date("Y-m-d h:i:s"),
                    "dataChange" => $deliveryOrder->getChanges(),
                ]
            );

            $historyDeleteDeliveryOrder["user_id"] = $user["id"];
            $historyDeleteDeliveryOrder["menu_id"] = $id;

            HistoryUpdate::create($historyDeleteDeliveryOrder);

            DB::commit();

            return redirect()->route('list_submission_form')
                ->with('success', 'Data berhasil dihapus');
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                "error" => $e,
            ]);
        }
    }

    public function addApi(Request $request)
    {
        $messages = array(
            'cso_id.required' => 'The CSO Code field is required.',
            'cso_id.exists' => 'Wrong CSO Code.',
            'branch_id.required' => 'The Branch must be selected.',
            'branch_id.exists' => 'Please choose the branch.',
        );

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'cso_id' => ['required', 'exists:csos,code'],
            'branch_id' => ['required', 'exists:branches,id'],
            'product_0' => 'required',
            'qty_0' => 'required'
        ], $messages);

        if ($validator->fails()) {
            $data = [
                'result' => 0,
                'data' => $validator->errors(),
            ];

            return response()->json($data, 401);
        } else {
            DB::beginTransaction();

            try {
                $data = $request->all();
                $data['code'] = "SUB_M/" . strtotime(date("Y-m-d H:i:s")) . "/" . substr($data['phone'], -4);
                $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];

                // Pembentukan array product
                $data['arr_product'] = [];
                foreach ($data as $key => $value) {
                    $arrKey = explode("_", $key);
                    if($arrKey[0] == 'product'){
                        if(isset($data['qty_'.$arrKey[1]])){
                            $data['arr_product'][$key] = [];
                            $data['arr_product'][$key]['id'] = $value;

                            // KHUSUS Philiphin
                            if($value == 'other'){
                                $data['arr_product'][$key]['id'] = $data['product_other_'.$arrKey[1]];
                            }
                            // ===========================

                            $data['arr_product'][$key]['qty'] = $data['qty_'.$arrKey[1]];
                        }
                    }
                }
                $data['arr_product'] = json_encode($data['arr_product']);
                $data["province"] = $data["province_id"];

                // Mengecek apakah ada gambar yang diupload atau tidak
                if ($request->hasFile("image_proof")) {
                    // Inisialisasi variabel data["image"]
                    $data["image"] = [];
                    // Inisialisasi jalur di mana gambar akan diletakkan
                    $path = "sources/registration";

                    // Mengecek apakah jalur untuk gambar sudah ada atau belum
                    if (!is_dir($path)) {
                        // Jika belum, maka akan membuat folder/directory baru
                        File::makeDirectory($path, 0777, true, true);
                    }

                    // Inisialisasi variabel $j untuk counter dan sebagai suffix nama file
                    $j = 0;
                    foreach ($request->file("image_proof") as $image) {
                        // Inisialisasi nama gambar dengan UNIX Timestamp
                        $fileName = strval(time());
                        // Memberikan suffix dengan $j agar nama gambar tidak sama, dan juga menambahkan file extension
                        $fileName .= "_" . $j . "." . $image->getClientOriginalExtension();
                        // Menyimpan gambar
                        $image->move($path, $fileName);
                        // Push nama gambar ke dalam variabel data["image"]
                        $data["image"][] = $fileName;
                        // Increment variabel $j
                        $j++;
                    }

                    // Convert array pada $data["image"] menjadi JSON
                    $data["image"] = json_encode($data["image"], JSON_FORCE_OBJECT);
                }

                // Memasukkan data delivery order ke tabel delivery_orders
                $deliveryOrder = DeliveryOrder::create($data);

                // Memasukkan data referensi ke tabel Reference
                $referenceArray = [];
                $dataCount = count($data["name_ref"]);
                for ($i = 0; $i < $dataCount; $i++) {
                    $reference = new Reference();
                    $reference->name = $data["name_ref"][$i];
                    $reference->age = $data["age_ref"][$i];
                    $reference->phone = $data["phone_ref"][$i];
                    $reference->province = $data["province_ref"][$i];
                    $reference->city = $data["city_ref"][$i];
                    $reference->deliveryorder_id = $deliveryOrder->id;
                    $reference->save();

                    $referenceArray[] = $reference;
                }

                DB::commit();

                $dataSubmission = $deliveryOrder;

                $dataSubmission->created_at = date(
                    "Y-m-d",
                    strtotime($dataSubmission->created_at)
                );

                $dataSubmission->customer_name = $dataSubmission->name;
                $dataSubmission->customer_phone = $dataSubmission->phone;

                $getSubmissionCityAndProvince = RajaOngkir_City::leftJoin(
                    "raja_ongkir__subdistricts",
                    "raja_ongkir__subdistricts.city_id",
                    "=",
                    "raja_ongkir__cities.city_id"
                )
                ->select(
                    "raja_ongkir__cities.province",
                    "raja_ongkir__cities.type",
                    "raja_ongkir__cities.city_name",
                    "raja_ongkir__subdistricts.subdistrict_name",
                )
                ->where(
                    "raja_ongkir__subdistricts.subdistrict_id",
                    $dataSubmission->distric
                )
                ->first();

                // Provinsi
                $dataSubmission->province = $getSubmissionCityAndProvince->province;

                // Kota
                $dataSubmission->city = $getSubmissionCityAndProvince->type
                    . " "
                    . $getSubmissionCityAndProvince->city_name;

                // District
                $dataSubmission->district = $getSubmissionCityAndProvince->subdistrict_name;

                // Promo
                $dataSubmission->promo = json_decode($dataSubmission->arr_product);
                $arrayPromo = (Array) $dataSubmission->promo;

                foreach ($arrayPromo as $key => $promoItem) {
                    $getPromo = DeliveryOrder::$Promo[$promoItem->id];
                    $arrayPromo[$key]->name = $getPromo["name"];
                }

                $dataSubmission->promo = $arrayPromo;

                // Branch
                $getBranch = Branch::where("id", $dataSubmission->branch_id)->first();
                $dataSubmission->branch_code = $getBranch->code;
                $dataSubmission->branch_name = $getBranch->name;

                // CSO
                $getCSO = Cso::where("id", $dataSubmission->cso_id)->first();
                $dataSubmission->cso_code = $getCSO->code;
                $dataSubmission->cso_name = $getCSO->name;

                $getReferencesCityAndProvince = RajaOngkir_City::leftJoin(
                    "references",
                    "references.city",
                    "=",
                    "raja_ongkir__cities.city_id"
                )
                ->select(
                    "raja_ongkir__cities.province",
                    "raja_ongkir__cities.type",
                    "raja_ongkir__cities.city_name",
                )
                ->where("references.deliveryorder_id", $dataSubmission->id)
                ->orderBy("references.id")
                ->get();

                foreach ($getReferencesCityAndProvince as $i => $refCityAndProvince) {
                    $referenceArray[$i]->province = $refCityAndProvince->province;
                    $referenceArray[$i]->city = $refCityAndProvince->type
                        . " "
                        . $refCityAndProvince->city_name;

                    unset(
                        $referenceArray[$i]->id,
                        $referenceArray[$i]->deliveryorder_id,
                        $referenceArray[$i]->created_at,
                        $referenceArray[$i]->updated_at,
                    );
                }

                unset(
                    $dataSubmission->name,
                    $dataSubmission->phone,
                    $dataSubmission->arr_product,
                    $dataSubmission->updated_at,
                    $dataSubmission->cso_id,
                    $dataSubmission->branch_id,
                    $dataSubmission->active,
                    $dataSubmission->distric,
                );

                $data = [
                    "result" => 1,
                    "dataSubmission" => $dataSubmission,
                    "dataReference" => $referenceArray,
                ];

                return response()->json($data, 201);
            } catch (Exception $e) {
                DB::rollback();

                $data = [
                    "result" => 0,
                    "error" => $e,
                    "error message" => $e->getMessage(),
                    "error file" => $e->getFile(),
                    "error line" => $e->getLine(),
                ];

                return response()->json($data, 501);
            }

        }
    }

    public function updateApi(Request $request)
    {
        $messages = array(
            'cso_id.required' => 'The CSO Code field is required.',
            'cso_id.exists' => 'Wrong CSO Code.',
            'branch_id.required' => 'The Branch must be selected.',
            'branch_id.exists' => 'Please choose the branch.',
        );

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'cso_id' => ['required', 'exists:csos,code'],
            'branch_id' => ['required', 'exists:branches,id'],
            'product_0' => 'required',
            'qty_0' => 'required'
        ], $messages);

        if ($validator->fails()) {
            $data = [
                'result' => 0,
                'data' => $validator->errors(),
            ];

            return response()->json($data, 401);
        } else {
            // Inisialisasi variabel $deliveryOrder untuk memperbarui data
            $deliveryOrder = DeliveryOrder::find($request->input("idDeliveryOrder"));

            DB::beginTransaction();

            try {
                $deliveryOrder->no_member = $request->input("no_member");
                $deliveryOrder->name = $request->input("name");
                $deliveryOrder->address = $request->input("address");
                $deliveryOrder->phone = $request->input("phone");

                // Pembentukan array product
                $data = $request->all();
                $data['arr_product'] = [];
                foreach ($data as $key => $value) {
                    $arrKey = explode("_", $key);
                    if ($arrKey[0] == 'product') {
                        if (isset($data['qty_'.$arrKey[1]])) {
                            $data['arr_product'][$key] = [];
                            $data['arr_product'][$key]['id'] = $value;
                            $data['arr_product'][$key]['qty'] = $data['qty_'.$arrKey[1]];
                        }
                    }
                }

                $deliveryOrder->arr_product = json_encode($data['arr_product']);

                $getCSOid = Cso::where('code', $request->input("cso_id"))->first()['id'];
                $deliveryOrder->cso_id = $getCSOid;
                $deliveryOrder->branch_id = $request->input("branch_id");
                $deliveryOrder->province = $request->input("province_id");
                $deliveryOrder->city = $request->input("city");
                $deliveryOrder->distric = $request->input("distric");
                $deliveryOrder->type_register = $request->input("type_register");

                // Mengecek apakah ada gambar yang diupload atau tidak
                if ($request->hasFile("image_proof")) {
                    // Inisialisasi variabel data["image"]
                    $data["image"] = [];
                    // Inisialisasi jalur di mana gambar akan diletakkan
                    $path = "sources/registration";

                    // Mengecek apakah jalur untuk gambar sudah ada atau belum
                    if (!is_dir($path)) {
                        // Jika belum, maka akan membuat folder/directory baru
                        File::makeDirectory($path, 0777, true, true);
                    }

                    // Inisialisasi variabel $j untuk counter dan sebagai suffix nama file
                    $j = 0;
                    foreach ($request->file("image_proof") as $image) {
                        // Inisialisasi nama gambar dengan UNIX Timestamp
                        $fileName = strval(time());
                        // Memberikan suffix dengan $j agar nama gambar tidak sama, dan juga menambahkan file extension
                        $fileName .= "_" . $j . "." . $image->getClientOriginalExtension();
                        // Menyimpan gambar
                        $image->move($path, $fileName);
                        // Push nama gambar ke dalam variabel data["image"]
                        $data["image"][] = $fileName;
                        // Increment variabel $j
                        $j++;
                    }

                    // Convert array pada $data["image"] menjadi JSON
                    $data["image"] = json_encode($data["image"], JSON_FORCE_OBJECT);

                    $deliveryOrder->image = $data["image"];
                }

                // Menyimpan pembaruan data delivery_orders
                $deliveryOrder->save();

                // Mengisi variabel $historyDeliveryOrder dengan data yang diperbarui
                $historyDeliveryOrder = [];
                $historyDeliveryOrder["type_menu"] = "Delivery Order";
                $historyDeliveryOrder["method"] = "Update";
                $historyDeliveryOrder["meta"] = json_encode(
                    [
                        "user" => $request->input("user_id"),
                        "createdAt" => date("Y-m-d h:i:s"),
                        "dataChange" => $deliveryOrder->getChanges(),
                    ]
                );

                $historyDeliveryOrder["user_id"] = $request->input("user_id");
                $historyDeliveryOrder["menu_id"] = $deliveryOrder->id;

                // Menyimpan riwayat pembaruan data delivery_orders ke tabel history_updates
                HistoryUpdate::create($historyDeliveryOrder);

                $referenceArray = [];
                $dataCount = count($data["name_ref"]);
                for ($i = 0; $i < $dataCount; $i++) {
                    $reference = Reference::find($data["id_reference"][$i]);

                    $reference->name = $data["name_ref"][$i];
                    $reference->age = $data["age_ref"][$i];
                    $reference->phone = $data["phone_ref"][$i];
                    $reference->province = $data["province_ref"][$i];
                    $reference->city = $data["city_ref"][$i];

                    // Menyimpan pembaruan data references
                    $reference->save();

                    $referenceArray[] = $reference;

                    // Mengisi variabel $historyReference dengan data yang diperbarui
                    $historyReference = [];
                    $historyReference["type_menu"] = "Reference";
                    $historyReference["method"] = "Update";
                    $historyReference["meta"] = json_encode(
                        [
                            "user" => $request->input("user_id"),
                            "createdAt" => date("Y-m-d h:i:s"),
                            "dataChange" => $reference->getChanges(),
                        ]
                    );

                    $historyReference["user_id"] = $request->input("user_id");
                    $historyReference["menu_id"] = $reference->id;

                    // Menyimpan riwayat pembaruan data references ke tabel history_updates
                    HistoryUpdate::create($historyReference);
                }

                DB::commit();

                $dataSubmission = $deliveryOrder;

                $dataSubmission->created_at = date(
                    "Y-m-d",
                    strtotime($dataSubmission->created_at)
                );

                $dataSubmission->customer_name = $dataSubmission->name;
                $dataSubmission->customer_phone = $dataSubmission->phone;

                $getSubmissionCityAndProvince = RajaOngkir_City::leftJoin(
                    "raja_ongkir__subdistricts",
                    "raja_ongkir__subdistricts.city_id",
                    "=",
                    "raja_ongkir__cities.city_id"
                )
                ->select(
                    "raja_ongkir__cities.province",
                    "raja_ongkir__cities.type",
                    "raja_ongkir__cities.city_name",
                    "raja_ongkir__subdistricts.subdistrict_name",
                )
                ->where(
                    "raja_ongkir__subdistricts.subdistrict_id",
                    $dataSubmission->distric
                )
                ->first();

                // Provinsi
                $dataSubmission->province = $getSubmissionCityAndProvince->province;

                // Kota
                $dataSubmission->city = $getSubmissionCityAndProvince->type
                    . " "
                    . $getSubmissionCityAndProvince->city_name;

                // District
                $dataSubmission->district = $getSubmissionCityAndProvince->subdistrict_name;

                // Promo
                $dataSubmission->promo = json_decode($dataSubmission->arr_product);
                $arrayPromo = (Array) $dataSubmission->promo;

                foreach ($arrayPromo as $key => $promoItem) {
                    $getPromo = DeliveryOrder::$Promo[$promoItem->id];
                    $arrayPromo[$key]->name = $getPromo["name"];
                }

                $dataSubmission->promo = $arrayPromo;

                // Branch
                $getBranch = Branch::where("id", $dataSubmission->branch_id)->first();
                $dataSubmission->branch_code = $getBranch->code;
                $dataSubmission->branch_name = $getBranch->name;

                // CSO
                $getCSO = Cso::where("id", $dataSubmission->cso_id)->first();
                $dataSubmission->cso_code = $getCSO->code;
                $dataSubmission->cso_name = $getCSO->name;

                $getReferencesCityAndProvince = RajaOngkir_City::leftJoin(
                    "references",
                    "references.city",
                    "=",
                    "raja_ongkir__cities.city_id"
                )
                ->select(
                    "raja_ongkir__cities.province",
                    "raja_ongkir__cities.type",
                    "raja_ongkir__cities.city_name",
                )
                ->where("references.deliveryorder_id", $dataSubmission->id)
                ->orderBy("references.id")
                ->get();

                foreach ($getReferencesCityAndProvince as $i => $refCityAndProvince) {
                    $referenceArray[$i]->province = $refCityAndProvince->province;
                    $referenceArray[$i]->city = $refCityAndProvince->type
                        . " "
                        . $refCityAndProvince->city_name;

                    unset(
                        $referenceArray[$i]->id,
                        $referenceArray[$i]->deliveryorder_id,
                        $referenceArray[$i]->created_at,
                        $referenceArray[$i]->updated_at,
                    );
                }

                unset(
                    $dataSubmission->name,
                    $dataSubmission->phone,
                    $dataSubmission->arr_product,
                    $dataSubmission->updated_at,
                    $dataSubmission->cso_id,
                    $dataSubmission->branch_id,
                    $dataSubmission->active,
                    $dataSubmission->distric,
                );

                $data = [
                    "result" => 1,
                    "dataSubmission" => $dataSubmission,
                    "dataReference" => $referenceArray,
                ];


                return response()->json($data, 200);
            } catch (Exception $e) {
                DB::rollback();

                $data = [
                    "result" => 0,
                    "error" => $e,
                    "error line" => $e->getLine(),
                    "error messages" => $e->getMessage(),
                ];

                return response()->json($data, 501);
            }
        }
    }

    public function listSubmissionApi(Request $request)
    {
        try {
            // Menyimpan request ke dalam variabel $url untuk pagination
            $url = $request->all();

            // Query dari tabel delivery_orders, dan menampilkan 10 data per halaman
            $deliveryOrders = DeliveryOrder::select(
                "delivery_orders.id AS id",
                "delivery_orders.code AS code",
                "delivery_orders.name AS customer_name",
                "delivery_orders.created_at",
                "branches.code AS branch_code",
                "branches.name AS branch_name",
                "csos.code AS cso_code",
                "csos.name AS cso_name"
            )
            ->leftJoin(
                "branches",
                "branches.id",
                "=",
                "delivery_orders.branch_id"
            )
            ->leftJoin(
                "csos",
                "csos.id",
                "=",
                "delivery_orders.cso_id"
            )
            ->where(
                [
                    ['delivery_orders.active', true],
                    ['delivery_orders.type_register', '!=', 'Normal Register'],
                ]
            )
            ->paginate(10);

            $data = [
                "data" => $deliveryOrders,
                "url" => $url,
            ];

            return response()->json($data, 200);
        } catch (Exception $e) {
            $data = [
                "result" => 0,
                "data" => $e->getMessage(),
            ];

            return response()->json($data, 401);
        }
    }

    public function listReferenceApi(Request $request)
    {
        try {
            // Menyimpan request ke dalam variabel $url untuk pagination
            $url = $request->all();

            // Query dari tabel references, dan menampilkan 10 data per halaman
            $references = Reference::select(
                "references.name AS name",
                "references.age AS age",
                "references.phone AS phone",
                "raja_ongkir__cities.province AS province",
                "raja_ongkir__cities.type AS type",
                "raja_ongkir__cities.city_name AS city_name",
            )
            ->leftJoin(
                "raja_ongkir__cities",
                "raja_ongkir__cities.city_id",
                "=",
                "references.city"
            )
            ->paginate(10);

            $i = 0;
            foreach ($references as $refItem) {
                $references[$i]->city = $refItem->type . " " . $refItem->city_name;

                unset(
                    $references[$i]->type,
                    $references[$i]->city_name,
                );

                $i++;
            }

            $data = [
                "result" => 1,
                "data" => $references,
                "url" => $url,
            ];

            return response()->json($data, 200);
        } catch (Exception $e) {
            $data = [
                "result" => 0,
                "data" => $e->getMessage(),
            ];

            return response()->json($data, 401);
        }
    }

    public function deleteApi(Request $request)
    {
        $messages = array(
            'id.required' => "There's an error with the data.",
        );

        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
            ]
        ], $messages);

        if ($validator->fails()){
            $data = [
                'result' => 0,
                'data' => $validator->errors(),
            ];

            return response()->json($data, 401);
        } else {
            DB::beginTransaction();

            try {
                $deliveryOrder = DeliveryOrder::where("id", $request->id)->first();
                $deliveryOrder->active = false;
                $deliveryOrder->save();

                $historyDeleteDeliveryOrder = [];
                $historyDeleteDeliveryOrder["type_menu"] = "Delivery Order";
                $historyDeleteDeliveryOrder["method"] = "Delete";
                $historyDeleteDeliveryOrder["meta"] = json_encode(
                    [
                        "user" => $request->user_id,
                        "createdAt" => date("Y-m-d h:i:s"),
                        "dataChange" => $deliveryOrder->getChanges(),
                    ]
                );

                $historyDeleteDeliveryOrder["user_id"] = $request->user_id;
                $historyDeleteDeliveryOrder["menu_id"] = $request->id;

                // dd($historyDeleteDeliveryOrder);

                HistoryUpdate::create($historyDeleteDeliveryOrder);

                DB::commit();

                $data = [
                    "result" => 1,
                ];

                return response()->json($data, 200);

            } catch (Exception $e) {
                DB::rollback();

                $data = [
                    "result" => 0,
                    "data" => $e->getMessage(),
                ];

                return response()->json($data, 501);
            }
        }
    }
}