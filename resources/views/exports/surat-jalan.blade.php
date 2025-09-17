<table>
    <thead>
        <tr>
            <th colspan="6" style="text-align: center; font-size: 16px; font-weight: bold;">
                SURAT JALAN
            </th>
        </tr>
        <tr>
            <th colspan="6"></th>
        </tr>
        <tr>
            <th>Nomor Surat</th>
            <th colspan="2">{{ $suratJalan->nomor_surat }}</th>
            <th>Tanggal</th>
            <th colspan="2">{{ $suratJalan->tanggal->format('d/m/Y') }}</th>
        </tr>
        <tr>
            <th>Jenis Surat Jalan</th>
            <th colspan="2">{{ $suratJalan->jenis_surat_jalan ?? 'Normal' }}</th>
            <th>Kepada</th>
            <th colspan="2">{{ $suratJalan->kepada }}</th>
        </tr>
        <tr>
            <th>Alamat</th>
            <th colspan="5">{{ $suratJalan->alamat }}</th>
        </tr>
        <tr>
            <th>Alamat</th>
            <th colspan="5">{{ $suratJalan->alamat }}</th>
        </tr>
        <tr>
            <th>Berdasarkan</th>
            <th colspan="5">{{ $suratJalan->berdasarkan }}</th>
        </tr>
        <tr>
            <th>Security</th>
            <th colspan="5">{{ $suratJalan->security ?? '-' }}</th>
        </tr>
        <tr>
            <th colspan="6"></th>
        </tr>
        <tr>
            <th style="background-color: #f0f0f0; font-weight: bold;">No</th>
            <th style="background-color: #f0f0f0; font-weight: bold;">Nama Material</th>
            <th style="background-color: #f0f0f0; font-weight: bold;">Quantity</th>
            <th style="background-color: #f0f0f0; font-weight: bold;">Satuan</th>
            <th style="background-color: #f0f0f0; font-weight: bold;">Keterangan</th>
            <th style="background-color: #f0f0f0; font-weight: bold;">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($suratJalan->details as $index => $detail)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $detail->material->material_description }}</td>
            <td>{{ $detail->quantity }}</td>
            <td>{{ $detail->satuan }}</td>
            <td>{{ $detail->keterangan ?? '-' }}</td>
            <td>{{ $suratJalan->status }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td><strong>Keterangan:</strong></td>
            <td colspan="5">{{ $suratJalan->keterangan ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td><strong>Dibuat oleh:</strong></td>
            <td colspan="2">{{ $suratJalan->creator->name }}</td>
            <td><strong>Disetujui oleh:</strong></td>
            <td colspan="2">{{ $suratJalan->approver->name ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Dibuat:</strong></td>
            <td colspan="2">{{ $suratJalan->created_at->format('d/m/Y H:i') }}</td>
            <td><strong>Tanggal Disetujui:</strong></td>
            <td colspan="2">{{ $suratJalan->approved_at ? $suratJalan->approved_at->format('d/m/Y H:i') : '-' }}</td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td><strong>Kendaraan</strong></td>
            <td>:</td>
            <td colspan="4">{{ $suratJalan->kendaraan ?? '' }}</td>
        </tr>
        <tr>
            <td><strong>No. Polisi</strong></td>
            <td>:</td>
            <td colspan="4">{{ $suratJalan->no_polisi ?? '' }}</td>
        </tr>
        <tr>
            <td><strong>Pengemudi</strong></td>
            <td>:</td>
            <td colspan="4">{{ $suratJalan->pengemudi ?? '' }}</td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center; font-weight: bold; background-color: #f0f0f0;">
                SEMUA RESIKO SETELAH BARANG KELUAR GUDANG
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center; font-weight: bold; background-color: #f0f0f0;">
                MENJADI TANGGUNG JAWAB PENGAMBIL BARANG
            </td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td style="text-align: center; font-weight: bold;">Yang menerima</td>
            <td></td>
            <td style="text-align: center; font-weight: bold;">Security</td>
            <td></td>
            <td></td>
            <td style="text-align: center; font-weight: bold;">Admin Gudang</td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td style="text-align: center;">( {{ $suratJalan->kepada ?? '' }} )</td>
            <td></td>
            <td style="text-align: center;">(                    )</td>
            <td></td>
            <td></td>
            <td style="text-align: center;">NINDYA APRIETA S.</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: center;">NIP. 8609353Z</td>
        </tr>
    </tbody>
</table>