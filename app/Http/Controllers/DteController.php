<?php

namespace App\Http\Controllers;

use App\Dte;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class DteController extends ApiResponseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('backend.index');
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request)
    {
        $id = $request->segment(3);
        $dte = Dte::find($id);
        $url = 'https://libredte.cl';
        $hash = 'JXou3uyrc7sNnP2ewOCX38tWZ6BTm4D1';
        $rut = 76063822;
        $dte_type_id = $dte->dte_type_id;
        $folio = $dte->folio;
        $client_supplier_rut = $dte->rut;
        $papelContinuo = 0; // =75 ó =80 para papel contínuo
        $copias_tributarias = 1;
        $codigo = $dte->dte_type_id;
        $copias_cedibles = 1;
        $cedible = (int) (bool) $copias_cedibles; // =1 genera cedible, =0 no genera cedible
        // incluir autocarga de composer

        // crear cliente
        $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);
        // descargar PDF
        $pdf = $LibreDTE->get('/dte/dte_emitidos/pdf/'.$dte_type_id.'/'.$folio.'/'.$rut.'?formato=general&papelContinuo=0&copias_tributarias=1&copias_cedibles=1&cedible=0&compress=0&base64=0');
        if ($pdf['status']['code'] != 200) {
            exit('Error al descargar el PDF del DTE recibido: '.$pdf['body']."\n");
        }

        file_put_contents('/home/jysparki/www/tmp_document/'.$folio.'.pdf', $pdf['body']);
        $file_url = '/home/jysparki/www/tmp_document/'.$folio.'.pdf';
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: Binary');
        header('Content-disposition: attachment; filename="'.basename($folio.'.pdf').'"');
        readfile($file_url);
    }
}
