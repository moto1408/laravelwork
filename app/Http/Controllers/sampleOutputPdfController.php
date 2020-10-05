<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCPDF;
use TCPDF_FONTS;

class sampleOutputPdfController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $pageName = '帳票';
        
		return view('sampleOutputPdf.index',compact('pageName'));
    }

    public function downloadPdf()
    {
        
        // ダミーデータ設定
        $data['test01'] = "01 - あいうえお - left";
        $data['test02'] = "02 - あいうえお - center";
        $data['test03'] = "03 - あいうえお - right";

        // PDF 生成メイン　－　A4 縦に設定
        $pdf = new TCPDF("P", "mm", "A4", true, "UTF-8" );
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // PDF プロパティ設定
        $pdf->SetTitle('Title aiueo あいうえお');  // PDFドキュメントのタイトルを設定  http://tcpdf.penlabo.net/method/s/SetTitle.html
        $pdf->SetAuthor('Author aiueo あいうえお');  // PDFドキュメントの著者名を設定  http://tcpdf.penlabo.net/method/s/SetAuthor.html
        $pdf->SetSubject('Subject aiueo あいうえお');  // PDFドキュメントのサブジェクト(表題)を設定  http://tcpdf.penlabo.net/method/s/SetSubject.html
        $pdf->SetKeywords('KEY1 KEY2 KEY3 あいうえお'); // PDFドキュメントのキーワードを設定 http://tcpdf.penlabo.net/method/s/SetKeywords.html
        $pdf->SetCreator('Creator aiueo あいうえお');  // PDFドキュメントの製作者名を設定  http://tcpdf.penlabo.net/method/s/SetCreator.html
        
        // 日本語フォント設定
        $pdf->setFont('kozminproregular','',10);

        // ページ追加
        $pdf->addPage();

        // HTMLを描画、viewの指定と変数代入 - pdf_test.blade.php
        $pdf->writeHTML(view("pdf_test", $data)->render());
        
        // 出力指定 ファイル名、拡張子、D(ダウンロード)
        // $pdf->output('test' . '.pdf', 'D');
        $pdf->output('test' . '.pdf', 'I');
        return;
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
}
