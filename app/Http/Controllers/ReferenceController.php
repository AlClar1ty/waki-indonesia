<?php

namespace App\Http\Controllers;

use App\Reference;
use App\HistoryUpdate;
use App\RajaOngkir_City;
use App\ReferenceImage;
use App\ReferencePromo;
use App\ReferenceSouvenir;
use App\Souvenir;
use App\HomeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Menyimpan request ke dalam variabel $url untuk pagination
        $url = $request->all();

        // Query dari tabel references, dan menampilkan 10 data per halaman
        if (
            Auth::user()->roles[0]['slug'] === 'branch'
            || Auth::user()->roles[0]['slug'] === 'area-manager'
        ) {
            $arrbranches = [];
            foreach (Auth::user()->listBranches() as $value) {
                $arrbranches[] = $value['id'];
            }
            $references = Reference::whereIn('submissions.branch_id', $arrbranches)
                ->leftjoin('submissions', 'references.submission_id', '=', 'submissions.id');
        } else if (Auth::user()->roles[0]['slug'] === 'cso') {
            $references = Reference::where('submissions.cso_id', Auth::user()->cso['id'])
                ->leftjoin('submissions', 'references.submission_id', '=', 'submissions.id');
        } else {
            $references = Reference::all();
        }

        $references = $references->paginate(10);

        return view("admin.list_reference", compact("references", "url"));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function storeReferensi(Request $request)
    {
        DB::beginTransaction();

        try {
            $reference = new Reference();
            $reference->fill($request->only(
                "submission_id",
                "name",
                "age",
                "phone",
                "province",
                "city"
            ));
            $reference->save();

            $referenceSouvenir = new ReferenceSouvenir();
            $referenceSouvenir->reference_id = $reference->id;
            $referenceSouvenir->fill($request->only(
                "souvenir_id",
                "status",
                "order_id",
                "prize_id",
            ));
            $referenceSouvenir->link_hs = json_encode(
                explode(", ", $request->link_hs),
                JSON_FORCE_OBJECT|JSON_THROW_ON_ERROR
            );
            $referenceSouvenir->save();

            DB::commit();

            return redirect($request->url)
                ->with("success", "Data referensi berhasil dimasukkan.");
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
            ], 500);
        }
    }

    public function storeReferenceMGM(Request $request)
    {
        DB::beginTransaction();
        try {
            $reference = new Reference();
            $reference->fill($request->only(
                "submission_id",
                "name",
                "age",
                "phone",
                "province",
                "city"
            ));
            $reference->save();

            $referencePromo = new ReferencePromo();
            $referencePromo->reference_id = $reference->id;
            if (isset($request->promo_1)) {
                if ($request->promo_1 !== "other") {
                    $referencePromo->promo_1 = $request->promo_1;
                }
            }

            if (isset($request->promo_2)) {
                if ($request->promo_2 !== "other") {
                    $referencePromo->promo_2 = $request->promo_2;
                }
            }

            $referencePromo->qty_1 = $request->qty_1;

            if (
                isset($request->promo_2)
                || isset($request->other_2)
            ) {
                if (
                    !empty($request->promo_2)
                    || !empty($request->other_2)
                ) {
                    $referencePromo->qty_2 = $request->qty_2;
                }
            }

            $referencePromo->other_1 = $request->other_1;
            $referencePromo->other_2 = $request->other_2;
            $referencePromo->save();

            $path = "sources/registration";
            $referenceImage = new ReferenceImage();
            $referenceImage->reference_id = $reference->id;
            $userId = Auth::user()["id"];
            for ($i = 1; $i <= 2; $i++) {
                $imageInput = "image_" . $i;
                if ($request->hasFile($imageInput)) {
                    $fileName = ((string)time())
                    . "_"
                    . $userId
                    . "_"
                    . $i
                    . "."
                    . $request->file($imageInput)->getClientOriginalExtension();

                    $request->file($imageInput)->move($path, $fileName);

                    $referenceImage["image_" . $i] = $fileName;
                }
            }
            $referenceImage->save();

            DB::commit();

            return redirect($request->url)->with("success", "Data referensi berhasil dimasukkan.");
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "error" => $e,
                "error line" => $e->getLine(),
                "error file" => $e->getFile(),
                "error message" => $e->getMessage(),
            ], 500);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        if(isset($request->submission_id)){
            $request['id'] = $request->submission_id;
        }
        if (!empty($request->id)) {
            $user = Auth::user();

            return $this->updateReference($request, $user["id"]);
        }

        return response()->json([
            "result" => 0,
            "error" => "Data tidak ditemukan.",
        ], 400);
    }

    private function updateReference(Request $request, int $userId)
    {
        DB::beginTransaction();

        try {
            $reference = Reference::find($request->id);
            $reference->fill($request->only(
                "name",
                "age",
                "phone",
                "province",
                "city"
            ));
            $reference->save();

            $referenceSouvenir = ReferenceSouvenir::where("reference_id", $reference->id)->first();
            $referenceSouvenir->fill($request->only(
                "souvenir_id",
                "status",
                "delivery_status_souvenir",
                "order_id",
                "prize_id",
                "status_prize",
                "delivery_status_prize",
            ));

            if (!empty($request->link_hs)) {
                //updating status homeservice
                $homeservices = HomeService::find($request->link_hs);
                $homeservices->status_reference = true;
                $homeservices->save();

                $referenceSouvenir->link_hs = json_encode(
                    explode(", ", $request->link_hs),
                    JSON_FORCE_OBJECT|JSON_THROW_ON_ERROR
                );
            }
            $referenceSouvenir->save();

            $this->historyReference($reference, "update", $userId);
            $this->historyReferenceSouvenir($referenceSouvenir, "update", $userId);

            $city = RajaOngkir_City::select(
                "province AS province",
                DB::raw("CONCAT(type, ' ', city_name) AS city")
            )
            ->where("city_id", $reference->city)
            ->first();

            $souvenir = "";
            if (!empty($referenceSouvenir->souvenir_id)) {
                $souvenir = Souvenir::select("name")
                ->where("id", $referenceSouvenir->souvenir_id)
                ->first();
            }

            DB::commit();
            return redirect($request->url)->with("success", "Data referensi berhasil dimasukkan.");

            // return response()->json([
            //     "result" => 1,
            //     "data" => $reference,
            //     "dataSouvenir" => $referenceSouvenir,
            //     "province" => $city->province,
            //     "city" => $city->city,
            //     "souvenir" => $souvenir->name,
            // ]);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
            ], 500);
        }
    }

    public function updateReferenceMGM(Request $request)
    {
        DB::beginTransaction();

        try {
            $reference = Reference::find($request->id);
            $reference->fill($request->only(
                "name",
                "age",
                "phone",
                "province",
                "city"
            ));
            $reference->save();

            $referencePromo = ReferencePromo::where("reference_id", $request->id)->first();
            if (isset($request->promo_1)) {
                if ($request->promo_1 !== "other") {
                    $referencePromo->promo_1 = $request->promo_1;
                }
            }

            if (isset($request->promo_2)) {
                if ($request->promo_2 !== "other") {
                    $referencePromo->promo_2 = $request->promo_2;
                }
            }

            $referencePromo->qty_1 = $request->qty_1;

            if (
                isset($request->promo_2)
                || isset($request->other_2)
            ) {
                if (
                    !empty($request->promo_2)
                    || !empty($request->other_2)
                ) {
                    $referencePromo->qty_2 = $request->qty_2;
                }
            }

            $referencePromo->other_1 = $request->other_1;
            $referencePromo->other_2 = $request->other_2;

            $path = "sources/registration";
            $referenceImage = ReferenceImage::where("reference_id", $request->id)->first();
            for ($i = 1; $i <= 2; $i++) {
                $imageInput = "image_" . $i;
                if ($request->hasFile($imageInput)) {
                    $fileName = ((string)time())
                    . "_"
                    . Auth::user()["id"]
                    . "_"
                    . $i
                    . "."
                    . $request->file($imageInput)->getClientOriginalExtension();

                    $request->file($imageInput)->move($path, $fileName);

                    $referenceImage["image_" . $i] = $fileName;
                }
            }
            $referenceImage->save();

            DB::commit();

            return redirect($request->url)->with("success", "Data referensi berhasil dimasukkan.");
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
            ], 500);
        }
    }

    private function historyReference(
        Reference $reference,
        string $method,
        int $userId
    ) {
        $historyReference["type_menu"] = "Reference";
        $historyReference["method"] = $method;
        $historyReference["meta"] = json_encode(
            [
                "user" => $userId,
                "createdAt" => date("Y-m-d H:i:s"),
                "dataChange" => $reference->getChanges(),
            ],
            JSON_THROW_ON_ERROR
        );
        $historyReference["user_id"] = $userId;
        $historyReference["menu_id"] = $reference->id;
        HistoryUpdate::create($historyReference);
    }

    private function historyReferenceSouvenir(
        ReferenceSouvenir $referenceSouvenir,
        string $method,
        int $userId
    ) {
        $historyReferenceSouvenir["type_menu"] = "Reference Souvenir";
        $historyReferenceSouvenir["method"] = $method;
        $historyReferenceSouvenir["meta"] = json_encode(
            [
                "user" => $userId,
                "createdAt" => date("Y-m-d H:i:s"),
                "dataChange" => $referenceSouvenir->getChanges(),
            ],
            JSON_THROW_ON_ERROR
        );
        $historyReferenceSouvenir["user_id"] = $userId;
        $historyReferenceSouvenir["menu_id"] = $referenceSouvenir->id;
        HistoryUpdate::create($historyReferenceSouvenir);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $reference = Reference::where("id", $request->id)->first();
            $reference->active = false;
            $reference->save();

            $this->historyReference($reference, "delete", Auth::user()["id"]);

            DB::commit();

            return redirect($request->url)->with("success", "Data referensi berhasil dihapus.");
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                "error" => $e->getMessage(),
            ], 500);
        }
    }

    public function addApi(Request $request)
    {
        DB::beginTransaction();

        try {
            if ($request->type === "mgm") {
                $reference = new Reference();
                $reference->fill($request->only(
                    "submission_id",
                    "name",
                    "age",
                    "phone",
                    "province",
                    "city"
                ));
                $reference->save();

                $referencePromo = new ReferencePromo();
                $referencePromo->reference_id = $reference->id;
                if (isset($request->promo_1)) {
                    if ($request->promo_1 !== "other") {
                        $referencePromo->promo_1 = $request->promo_1;
                    }
                }

                if (isset($request->promo_2)) {
                    if ($request->promo_2 !== "other") {
                        $referencePromo->promo_2 = $request->promo_2;
                    }
                }

                $referencePromo->qty_1 = $request->qty_1;

                if (
                    isset($request->promo_2)
                    || isset($request->other_2)
                ) {
                    if (
                        !empty($request->promo_2)
                        || !empty($request->other_2)
                    ) {
                        $referencePromo->qty_2 = $request->qty_2;
                    }
                }

                $referencePromo->other_1 = $request->other_1;
                $referencePromo->other_2 = $request->other_2;
                $referencePromo->save();

                $path = "sources/registration";
                $referenceImage = new ReferenceImage();
                $referenceImage->reference_id = $reference->id;
                $userId = $request->user_id;
                for ($i = 1; $i <= 2; $i++) {
                    $imageInput = "image_" . $i;
                    if ($request->hasFile($imageInput)) {
                        $fileName = ((string)time())
                            . "_"
                            . $userId
                            . "_"
                            . $i
                            . "."
                            . $request->file($imageInput)->getClientOriginalExtension();

                        $request->file($imageInput)->move($path, $fileName);

                        $referenceImage["image_" . $i] = $fileName;
                    }
                }
                $referenceImage->save();

                DB::commit();

                return response()->json([
                    "result" => 1,
                    "reference" => $reference,
                    "referencePromo" => $referencePromo,
                    "referenceImage" => $referenceImage,
                ]);
            }

            if ($request->type === "referensi") {
                $reference = new Reference();
                $reference->fill($request->only(
                    "name",
                    "age",
                    "phone",
                    "province",
                    "city"
                ));
                $reference->save();

                $referenceSouvenir = new ReferenceSouvenir();
                $reference->reference_id = $reference->id;
                $referenceSouvenir->fill($request->only(
                    "souvenir_id",
                    "link_hs",
                    "status"
                ));
                $referenceSouvenir->save();

                DB::commit();

                return response()->json([
                    "result" => 1,
                    "reference" => $reference,
                    "referenceSouvenir" => $referenceSouvenir,
                ]);
            }
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listApi(Request $request)
    {
        try {
            // Menyimpan request ke dalam variabel $url untuk pagination
            $url = $request->all();

            // Query dari tabel references, dan menampilkan 10 data per halaman
            $references = Reference::select(
                "references.id AS id",
                "references.submission_id AS submission_id",
                "references.name AS name",
                "references.age AS age",
                "references.phone AS phone",
                "references.province AS province_id",
                "raja_ongkir__cities.province AS province",
                "references.city AS city_id",
                DB::raw("CONCAT(raja_ongkir__cities.type, ' ', raja_ongkir__cities.city_name) AS city")
            )
            ->leftJoin(
                "raja_ongkir__cities",
                "raja_ongkir__cities.city_id",
                "=",
                "references.city"
            )
            ->paginate(10);

            return response()->json([
                "result" => 1,
                "data" => $references,
                "url" => $url,
            ]);
        } catch (Exception $e) {
            return response()->json([
                "result" => 0,
                "data" => $e->getMessage(),
            ], 500);
        }
    }

    public function updateApi(Request $request)
    {
        if (!empty($request->id) && !empty($request->user_id)) {
            return $this->updateReference($request, (int) $request->user_id);
        }

        return response()->json([
            "result" => 0,
            "error" => "Data tidak ditemukan.",
        ], 400);
    }

    public function updateMGMApi(Request $request)
    {
        DB::beginTransaction();

        try {
            $reference = Reference::find($request->id);
            $reference->fill($request->only(
                "name",
                "age",
                "phone",
                "province",
                "city"
            ));
            $reference->save();

            $referencePromo = ReferencePromo::where("reference_id", $request->id)->first();
            if (isset($request->promo_1)) {
                if ($request->promo_1 !== "other") {
                    $referencePromo->promo_1 = $request->promo_1;
                }
            }

            if (isset($request->promo_2)) {
                if ($request->promo_2 !== "other") {
                    $referencePromo->promo_2 = $request->promo_2;
                }
            }

            $referencePromo->qty_1 = $request->qty_1;

            if (
                isset($request->promo_2)
                || isset($request->other_2)
            ) {
                if (
                    !empty($request->promo_2)
                    || !empty($request->other_2)
                ) {
                    $referencePromo->qty_2 = $request->qty_2;
                }
            }

            $referencePromo->other_1 = $request->other_1;
            $referencePromo->other_2 = $request->other_2;

            $path = "sources/registration";
            $referenceImage = ReferenceImage::where("reference_id", $request->id)->first();
            for ($i = 1; $i <= 2; $i++) {
                $imageInput = "image_" . $i;
                if ($request->hasFile($imageInput)) {
                    $fileName = ((string)time())
                    . "_"
                    . $request->user_id
                    . "_"
                    . $i
                    . "."
                    . $request->file($imageInput)->getClientOriginalExtension();

                    $request->file($imageInput)->move($path, $fileName);

                    $referenceImage["image_" . $i] = $fileName;
                }
            }
            $referenceImage->save();

            DB::commit();

            return response()->json([
                "result" => 1,
                "reference" => $reference,
                "referencePromo" => $referencePromo,
                "referenceImage" => $referenceImage,
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
            ], 500);
        }
    }
}
