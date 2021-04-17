@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="text-center mt-2">
            <h2>JURUSAN TEKNOLOGI INFORMASI-POLITEKNIK NEGERI MALANG</h2>
        </div>
        <div class="float-right my-2">
            <a class="btn btn-success" href="{{route('mahasiswa.create')}}"> Input Mahasiswa</a>
        </div>
        <nav class="navbar navbar-light bg-light">
            <form class="form-inline" action="{{ route('mahasiswa.cari') }}" method="GET">
              <input class="form-control mr-sm-2" type="search" name="cari" placeholder="Cari mahasiswa.." aria-label="Search">
              <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
          </nav>
    </div>
</div>

@if ($message=Session::get('success'))
<div class="alert alert-success">
    <p>{{$message}}</p>
</div>
@endif

<div class="table-responsive">
<table class="table">
   <tr>
       <th>Nim</th>
       <th>Nama</th>
       <th >Foto</th>
       <th>Kelas</th>
       <th>Jurusan</th>
       <th>Action</th>
    </tr>

    @foreach ($mhs as $Mahasiswa)
        <tr>
            <td>{{$Mahasiswa->nim}}</td>
            <td>{{$Mahasiswa->nama}}</td> 
            <td><img width="100px" height="100px" src="{{asset('storage/'.$Mahasiswa->foto)}}"></td>
            <td>{{$Mahasiswa->kelas->nama_kelas}}</td>
            <td>{{$Mahasiswa->jurusan}}</td>
            <td> 
                <form action="{{route('mahasiswa.destroy',$Mahasiswa->id)}}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data?')">
                    <a class="btn btn-info" href="{{route('mahasiswa.show',$Mahasiswa->id)}}">Show</a>
                    <a class="btn btn-primary" href="{{route('mahasiswa.edit',$Mahasiswa->id)}}">Edit</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <a class="btn btn-warning" href="{{route('mahasiswa.nilai',$Mahasiswa->id)}}">Nilai</a>
                </form>
            </td>
        </tr>
    @endforeach
</table>
{{-- <div class="d-flex justify-content-center">
    {{ $pg->links() }}  
</div>   --}}
</div>
@endsection