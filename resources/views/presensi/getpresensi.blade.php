<?php
    function selisih($start, $end)
    {
        list($h, $m, $s) = explode(":", $start);
        $dtAwal = mktime($h, $m, $s, 1, 1, 1);
        list($h, $m, $s) = explode(":", $end);
        $dtAkhir = mktime($h, $m, $s, 1, 1, 1);
        $dtSelisih = $dtAkhir - $dtAwal;
        $totalMenit = $dtSelisih / 60;
        $jam = floor($totalMenit / 60);
        $sisaMenit = $totalMenit % 60;
        return $jam . ":" . round($sisaMenit);
    }
?>
@foreach ($presensi as $p)
    @php
        $foto_in = Storage::url('uploads/presensi/'.$p->foto_in);
        $foto_out = Storage::url('uploads/presensi/'.$p->foto_out);
    @endphp
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $p->nik }}</td>
        <td>{{ $p->name }}</td>
        <td>{{ $p->in_hour }}</td>
        <td>
            <img src="{{ url($foto_in) }}" class="avatar" alt="">
        </td>
        <td>{!! $p->out_hour != null ? $p->out_hour : '<span class="badge text-bg-danger">Belum Pulang</span>' !!}</td>
        <td>
            @if ($p->out_hour != null)
                <img src="{{ url($foto_out) }}" class="avatar" alt="">
            @else
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-hourglass-high"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6.5 7h11" /><path d="M6 20v-2a6 6 0 1 1 12 0v2a1 1 0 0 1 -1 1h-10a1 1 0 0 1 -1 -1z" /><path d="M6 4v2a6 6 0 1 0 12 0v-2a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1z" /></svg>
            @endif
        </td>
        <td>
            @if ($p->in_hour <= '07:00:00')
                <span class="badge text-bg-success">Tepat Waktu</span>
            @elseif ($p->in_hour > '07:00:00' && $p->in_hour <= '07:15:00')
            @php
                $jamTerlambat = selisih('07:00:00', $p->in_hour);
            @endphp
                <span class="badge text-bg-danger">Terlambat {{ $jamTerlambat }}</span>
            @elseif ($p->in_hour > '07:15:00' && $p->in_hour < '12:00:00')
                <span class="badge text-bg-danger">Tidak Masuk</span>
            @elseif ($p->in_hour >= '14:00:00' && $p->in_hour <= '14:15:00')
            @php
                $jamTerlambat = selisih('14:00:00', $p->in_hour);
            @endphp
            <span class="badge text-bg-danger">Terlambat {{ $jamTerlambat }}</span>
                @elseif ($p->in_hour > '14:15:00')
            <span class="badge text-bg-danger">Tidak Masuk</span>
            @else
                <span class="badge text-bg-warning">Tidak dapat menentukan shift</span>
            @endif
        </td>
        <td>
            <button class="btn btn-primary showmap" id="{{ $p->id }}">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v7" /><path d="M9 4v13" /><path d="M15 7v5" /><path d="M21.121 20.121a3 3 0 1 0 -4.242 0c.418 .419 1.125 1.045 2.121 1.879c1.051 -.89 1.759 -1.516 2.121 -1.879z" /><path d="M19 18v.01" /></svg>
            </button>
        </td>
    </tr>
@endforeach

<script>
    $(function(){
        $(".showmap").click(function(e){
            var id = $(this).attr("id");
            $.ajax({
                type: 'POST',
                url: '/showmap',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                cache : false,
                success:function(respond){
                    $("#loadmap").html(respond);
                }
            });
            $("#modal-showmap").modal("show");
        });
    });
</script>