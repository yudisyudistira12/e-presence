@if ($history->isEmpty())
    <div class="alert alert-outline-warning">
        <p>Data Belum Ada</p>
    </div>
@endif
@foreach ($history as $h )
    <ul class="listview image-listview">
        <li>
            <div class="item">
                @php
                    $path = Storage::url('uploads/presensi/'.$h->foto_in);
                @endphp
                <img src="{{ url($path) }}" alt="image" class="image">
                <div class="in">
                    <div>
                        <b>{{ date("d-m-Y",strtotime($h->date_attendance)) }}</b>
                        <br>
                    </div>
                    <span class="badge {{ $h->in_hour < "07:00" ? "class=badge bg-success" : "class= badge bg-danger" }}">
                        {{ $h->in_hour }}
                    </span>
                    <span class="badge bg-primary">{{ $h->out_hour }}</span>
                </div>
            </div>
        </li>
    </ul>
@endforeach