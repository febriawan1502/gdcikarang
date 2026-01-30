<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori Material MRWI - {{ $serial }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            background-color: #f3f4f6;
            color: #1f2937;
            padding: 1rem;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .header {
            background-color: #2563eb;
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .header h1 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .header p {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        .content {
            padding: 1.5rem;
        }

        .section-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-grid {
            display: grid;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 0.75rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-weight: 500;
            color: #111827;
        }

        .timeline {
            position: relative;
            padding-left: 1rem;
            border-left: 2px solid #e5e7eb;
            margin-left: 0.5rem;
        }

        .timeline-item {
            position: relative;
            padding-left: 1.5rem;
            padding-bottom: 1.5rem;
        }

        .timeline-dot {
            position: absolute;
            left: -0.4rem;
            top: 0.25rem;
            width: 0.8rem;
            height: 0.8rem;
            border-radius: 50%;
            background-color: #3b82f6;
            border: 2px solid white;
            box-shadow: 0 0 0 2px #3b82f6;
        }

        .timeline-date {
            font-size: 0.75rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .timeline-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .timeline-desc {
            font-size: 0.875rem;
            color: #4b5563;
        }

        .timeline-meta {
            margin-top: 0.5rem;
            font-size: 0.75rem;
            color: #6b7280;
            background: #f9fafb;
            padding: 0.5rem;
            border-radius: 0.375rem;
        }


        /* Status Colors */
        .header.status-rusak {
            background-color: #ef4444;
        }

        .header.status-perbaikan {
            background-color: #eab308;
            color: #000;
        }

        .header.status-perbaikan .status-badge {
            background-color: rgba(0, 0, 0, 0.1);
            color: #000;
        }

        .header.status-garansi {
            background-color: #6b7280;
        }

        .header.status-standby {
            background-color: #22c55e;
        }

        /* Fallback / Default Blue */
        .header.status-default {
            background-color: #2563eb;
        }

        .header-material-id {
            font-size: 0.875rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
            font-family: monospace;
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }

        .footer {
            background-color: #f9fafb;
            padding: 1rem;
            text-align: center;
            font-size: 0.75rem;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }

        .not-found {
            text-align: center;
            padding: 3rem 1rem;
            color: #6b7280;
        }

        .btn-back {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background-color: white;
            color: #2563eb;
            border: 1px solid #2563eb;
            border-radius: 0.375rem;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
        }

        /* Mobile Specific Utility */
        @media (max-width: 640px) {
            body {
                padding: 0;
                background-color: #f3f4f6;
            }

            .container {
                border-radius: 0;
                min-height: 100vh;
                box-shadow: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        @if ($notFound)
            <div class="not-found">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🔍</div>
                <h2 style="margin-bottom: 0.5rem; color: #1f2937;">Data Tidak Ditemukan</h2>
                <p>Serial Number <strong>{{ $serial }}</strong> tidak terdaftar dalam sistem.</p>
                <a href="{{ url('/') }}" class="btn-back">Kembali ke Beranda</a>
            </div>
        @else
            @php
                $statusClass = 'status-default';
                $statusText = strtolower($currentStatus ?? ($latest->status_bucket ?? ''));

                if (str_contains($statusText, 'rusak')) {
                    $statusClass = 'status-rusak';
                } elseif (str_contains($statusText, 'perbaikan')) {
                    $statusClass = 'status-perbaikan';
                } elseif (str_contains($statusText, 'garansi') || str_contains($statusText, 'klaim')) {
                    $statusClass = 'status-garansi';
                } elseif (str_contains($statusText, 'standby')) {
                    $statusClass = 'status-standby';
                }
            @endphp
            <div class="header {{ $statusClass }}">
                <h1>{{ $serial }}</h1>
                <p>{{ $material->material_description ?? 'Material MRWI' }}</p>
                <div class="header-material-id">{{ $material->material_code ?? '-' }}</div>
                <div>
                    <div class="status-badge"
                        style="background-color: rgba(255,255,255,0.2); color: inherit; display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 600; font-size: 0.875rem;">
                        {{ $currentStatus ?? ucfirst($latest->status_bucket ?? 'Unknown') }}
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="section-title">Informasi Detail</div>
                <div class="info-grid">
                    {{-- Material ID removed from here as it moved to header --}}
                    @if ($mrwiDetail)
                        <div class="info-item">
                            <span class="info-label">ID Pelanggan (SN Pabrikan)</span>
                            <span class="info-value">{{ $mrwiDetail->id_pelanggan ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Merk / Pabrikan</span>
                            <span class="info-value">{{ $mrwiDetail->nama_pabrikan ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tahun Pembuatan</span>
                            <span class="info-value">{{ $mrwiDetail->tahun_buat ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kategori Kerusakan</span>
                            <span class="info-value">{{ $mrwiDetail->mrwi->kategori_kerusakan ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Asal / ULP</span>
                            <span class="info-value">{{ $mrwiDetail->mrwi->ulp_pengirim ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Ex Gardu</span>
                            <span class="info-value">{{ $mrwiDetail->mrwi->ex_gardu ?? '-' }}</span>
                        </div>
                    @endif
                </div>

                <div class="section-title">Riwayat Perjalanan</div>
                <div class="timeline">
                    @forelse($timeline as $event)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-date">
                                {{ $event['tanggal'] ? \Carbon\Carbon::parse($event['tanggal'])->format('d M Y, H:i') : '-' }}
                            </div>
                            <div class="timeline-title">{{ $event['jenis'] }}</div>
                            <div class="timeline-desc">
                                Status: {{ $event['status'] }}
                            </div>
                            @if (isset($event['referensi']) && $event['referensi'] !== '-')
                                <div class="timeline-meta">
                                    Ref: {{ $event['referensi'] }}<br>
                                    {{ $event['keterangan'] }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="timeline-item">
                            <div class="timeline-dot" style="background-color: #9ca3af; box-shadow: none;"></div>
                            <div class="timeline-desc">Belum ada riwayat tercatat.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="footer">
                &copy; {{ date('Y') }} {{ \App\Models\Setting::get('company_name', 'PLN') }}.<br>
                Sistem Manajemen Gudang & Aset
            </div>
        @endif
    </div>
</body>

</html>
