@extends('layouts.main')

@section('title', 'Ölçü Vahidləri')

@section('content')
    <div class="container">
        <div class="card mb-4">
            <div class="card-header">
                <h4>Yeni Ölçü Vahidi Əlavə Et</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('units.store') }}" method="POST" class="d-flex gap-2">
                    @csrf
                    <input type="text" name="name" class="form-control" placeholder="Ölçü vahidinin adı" required maxlength="500">
                    <button type="submit" class="btn btn-success ml-2">Əlavə et</button>
                </form>
                @if($errors->has('name'))
                    <div class="text-danger mt-2">{{ $errors->first('name') }}</div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>Ölçü Vahidləri ({{ $units->count() }})</h4>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0" id="units-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ölçü Vahidinin Adı</th>
                            <th style="width: 100px;">Əməliyyat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($units as $i => $unit)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $unit->name }}</td>
                                <td>
                                    <form action="{{ route('units.destroy', $unit) }}" method="POST"
                                          onsubmit="return confirm('Silmək istədiyinizə əminsiniz?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#units-table').DataTable({ "order": [[0, "asc"]], pageLength: 25 });
    </script>
@endsection
