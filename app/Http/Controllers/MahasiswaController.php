<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\Oracle;
use PDF;

class MahasiswaController extends Controller
{
    public function oracle()
    {
        $data = new Oracle;
        return $data;
    }

    public function uploadFile(Request $request,$oke)
    {
            $result ='';
            $file = $request->file($oke);
            $name = $file->getClientOriginalName();

            $extension = explode('.',$name);
            $extension = strtolower(end($extension));

            $key = rand().'-'.$oke;
            $tmp_file_name = "{$key}.{$extension}";
            $tmp_file_path = "admin/images/mahasiswa/";
            $file->move($tmp_file_path,$tmp_file_name);

            $result = 'admin/images/mahasiswa'.'/'.$tmp_file_name;
        return $result;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mahasiswa = Mahasiswa::with('kelas')->paginate(5);
        return view('mahasiswa.index',['mhs'=>$mahasiswa]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kelas = Kelas::all();
        return view('mahasiswa.create',['kelas'=>$kelas]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required',
            'nama' => 'required',
            'kelas' => 'required',
            'jurusan' => 'required',
            'foto' => 'required'
        ]);

        $foto_mhs = $this->uploadFile($request,'foto');
        $image_name = $foto_mhs;
        $upload = $this->oracle()->upFileOracle($image_name);

        $mahasiswa = new Mahasiswa;
        $mahasiswa->nim = $request->get('nim');
        $mahasiswa->nama = $request->get('nama');
        $mahasiswa->jurusan = $request->get('jurusan');
        $mahasiswa->foto = $upload['message'];
        $mahasiswa->save();

        $kelas = new Kelas;
        $kelas->id = $request->get('kelas');

        $mahasiswa->kelas()->associate($kelas);
        $mahasiswa->save();

        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Mahasiswa = Mahasiswa::with('kelas')->where('id', $id)->first();
        return view('mahasiswa.detail',['Mahasiswa'=>$Mahasiswa]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Mahasiswa = Mahasiswa::with('kelas')->where('id', $id)->first();
        $kelas = Kelas::all();
        return view('mahasiswa.edit', compact('Mahasiswa','kelas'));
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
        $request->validate([
            'nim' => 'required',
            'nama' => 'required',
            'kelas' => 'required',
            'jurusan' => 'required',
            'foto' => 'required'
        ]);

        $mahasiswa = Mahasiswa::with('kelas')->where('id',$id)->first();
        $mahasiswa->nim = $request->get('nim');
        $mahasiswa->nama = $request->get('nama');
        $mahasiswa->jurusan = $request->get('jurusan');

        if($mahasiswa->foto && file_exists(storage_path('app/public/' . $mahasiswa->foto))){
            Storage::delete('public/' . $mahasiswa->foto);
        }
        $image_name = $request->file('foto')->store('images', 'public');
        $mahasiswa->foto = $image_name;

        $mahasiswa->save();

        $kelas = new Kelas;
        $kelas->id = $request->get('kelas');

        //fungsi eloquent untuk menambah data dengan relasi belongTo
        $mahasiswa->kelas()->associate($kelas);
        $mahasiswa->save();

        //jika data berhasil ditambahkan, akan kembali ke halaman utama
        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Mahasiswa::find($id)->delete();
        return redirect()->route('mahasiswa.index')-> with('success', 'Mahasiswa berhasil dihapus');
    }

    public function cari(Request $request){
        //melakukan validasi data
        $cari=$request->cari;

        $Mahasiswa = Mahasiswa::where('nama','like',"%".$cari."%")->paginate(5);

        return view('mahasiswa.index',['mhs'=>$Mahasiswa]);
    }

    public function hasil($id)
    {
        $Mahasiswa = Mahasiswa::find($id);
        return view('mahasiswa.khs',['Mahasiswa'=>$Mahasiswa]);
    }

    public function cetak_pdf($id){
        $mahasiswa = Mahasiswa::find($id);
        $pdf = PDF::loadview('mahasiswa.mahasiswa_pdf',['mhs'=>$mahasiswa]);
        return $pdf->stream();
    }
}
