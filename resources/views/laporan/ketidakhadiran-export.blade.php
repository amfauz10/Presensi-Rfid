<table>
    <thead>
        <tr>
            <th colspan="5" style="text-align:center; font-weight:bold;">
                Rekap Ketidakhadiran - Kelas {{ $kelas_terpilih->nama_kelas ?? '-' }} -
                {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Sakit</th>
            <th>Izin</th>
            <th>Alpa</th>
        </tr>
    </thead>
    <tbody>
        @forelse($rekap as $i => $siswa)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $siswa->nama_siswa }}</td>
                <td>{{ $siswa->sakit }}</td>
                <td>{{ $siswa->izin }}</td>
                <td>{{ $siswa->alpa }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Tidak ada data siswa aktif di kelas ini.</td>
            </tr>
        @endforelse
    </tbody>
</table>
