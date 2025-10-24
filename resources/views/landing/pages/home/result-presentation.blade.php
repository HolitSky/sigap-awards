@extends('landing.layouts.app')
@section('content')

@push('styles')
<style>
    /* FORCE override all container styles */
    html, body {
        height: auto !important;
        min-height: 100vh !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
    }

    body {
        display: flex !important;
        flex-direction: column !important;
    }

    #primary {
        flex: 1 0 auto !important;
        width: 100% !important;
        height: auto !important;
        overflow: visible !important;
    }

    .wrapper {
        min-height: auto !important;
        height: auto !important;
        max-height: none !important;
        display: block !important;
        grid-template-columns: 1fr !important;
        overflow: visible !important;
    }

    .announcement-wrapper {
        padding: 80px 20px 100px;
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
        overflow-y: visible;
        min-height: auto;
        height: auto;
    }

    .announcement-header {
        text-align: center;
        margin-bottom: 60px;
        animation: fadeInDown 0.8s ease-out;
    }

    .announcement-title {
        color: var(--white);
        font-size: 42px;
        font-weight: 700;
        margin-bottom: 16px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        line-height: 1.2;
    }

    .announcement-subtitle {
        color: var(--white);
        font-size: 20px;
        opacity: 0.9;
    }

    .announcement-icon {
        font-size: 80px;
        line-height: 1;
        margin: 0 auto 20px;
        animation: bounce 2s infinite;
        display: block;
        text-align: center;
    }

    .categories-container {
        max-width: 1400px;
        margin: 0 auto 80px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(550px, 1fr));
        gap: 40px;
    }

    .category-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 32px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        animation: fadeInUp 0.8s ease-out;
        display: flex;
        flex-direction: column;
        height: auto;
    }

    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 48px rgba(0, 0, 0, 0.2);
        border-color: var(--sigap-color);
    }

    .category-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    }

    .category-icon {
        width: 48px;
        height: 48px;
        background: var(--sigap-color);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .category-title {
        color: var(--white);
        font-size: 22px;
        font-weight: 700;
        margin: 0;
    }

    .category-count {
        background: var(--sigap-color);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        margin-left: auto;
    }

    .participants-list {
        list-style: none;
        padding: 0;
        margin: 0;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .participant-item {
        background: rgba(255, 255, 255, 0.05);
        padding: 16px 20px;
        margin-bottom: 0;
        border-radius: 12px;
        border-left: 4px solid var(--sigap-color);
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.3s ease;
        animation: slideInLeft 0.5s ease-out;
        flex-shrink: 0;
    }

    .participant-item:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateX(8px);
    }

    .participant-number {
        background: var(--sigap-color);
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }

    .participant-name {
        color: var(--white);
        font-size: 16px;
        font-weight: 500;
        flex: 1;
    }

    .participant-badge {
        background: rgba(52, 195, 143, 0.2);
        color: #34c38f;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .btn-back-home {
        display: inline-block;
        background-color: var(--sigap-color);
        color: #fff;
        padding: 14px 32px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 16px;
        margin-top: 48px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    }

    .btn-back-home:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.3);
        opacity: 0.95;
    }

    .back-home-container {
        text-align: center;
        margin-top: 60px;
        padding-bottom: 20px;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    @media (max-width: 1400px) {
        .categories-container {
            max-width: 1200px;
            gap: 32px;
        }
    }

    @media (max-width: 1200px) {
        .categories-container {
            grid-template-columns: 1fr;
            max-width: 800px;
            gap: 32px;
        }

        .announcement-wrapper {
            padding: 60px 20px 80px;
        }
    }

    @media (max-width: 1024px) {
        .categories-container {
            grid-template-columns: 1fr;
            max-width: 700px;
            gap: 28px;
        }
    }

    @media (max-width: 768px) {
        .announcement-wrapper {
            padding: 50px 16px 60px;
        }

        .announcement-title {
            font-size: 32px;
        }

        .announcement-subtitle {
            font-size: 16px;
        }

        .announcement-icon {
            font-size: 64px;
        }

        .categories-container {
            grid-template-columns: 1fr;
            max-width: 100%;
            gap: 24px;
            margin-bottom: 60px;
        }

        .category-card {
            padding: 24px;
        }

        .category-title {
            font-size: 20px;
        }

        .participant-name {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .announcement-title {
            font-size: 26px;
        }

        .categories-container {
            gap: 20px;
        }

        .category-card {
            padding: 20px;
        }
    }

    /* Sheet table styles */
    .table-wrapper { width: 100%; overflow-x: auto; }
    table.sheet-table { width: 100%; border-collapse: collapse; }
    table.sheet-table thead th {
        position: sticky; top: 0; z-index: 1;
        background: rgba(255,255,255,0.15);
        color: var(--white);
        text-align: left;
        padding: 12px 14px;
        border-bottom: 2px solid rgba(255,255,255,0.2);
        font-weight: 700; font-size: 14px;
    }
    table.sheet-table tbody td {
        color: var(--white);
        padding: 12px 14px;
        border-bottom: 1px solid rgba(255,255,255,0.12);
        vertical-align: top; font-size: 14px;
    }
    table.sheet-table tbody tr:hover { background: rgba(255,255,255,0.06); }
    .sheet-link { color: #34c38f; font-weight: 600; text-decoration: none; }
    .sheet-link:hover { text-decoration: underline; }
    td.col-no { width: 56px; }
    td.is-empty, td.loading { text-align: center; color: rgba(255,255,255,0.8); font-style: italic; }
</style>
@endpush

<div class="wrapper">
    <div class="announcement-wrapper">
        <!-- Header -->
        <div class="announcement-header">
            <div class="announcement-icon">📑</div>
            <h1 class="announcement-title">Rekapan Presentasi Peserta BPKH dan Produsen Data Geospasial</h1>
            <p class="announcement-subtitle">SIGAP Award 2025</p>
        </div>

        <!-- Result Tables (loaded from Google Sheets) -->
        <div class="categories-container">
            <!-- BPKH Table -->
            <div class="category-card">
                <div class="category-header">
                    <div class="category-icon">&#127963;</div>
                    <h2 class="category-title">BPKH</h2>
                    <span class="category-count" id="count-bpkh">0</span>
                </div>

                <div class="table-wrapper">
                    <table class="sheet-table" id="table-bpkh">
                        <thead>
                            <tr>
                                <th style="width:56px;">No</th>
                                <th>Nama BPKH</th>
                                <th>Petugas BPKH</th>
                                <th>File Presentasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="4" class="loading">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Produsen Table -->
            <div class="category-card">
                <div class="category-header">
                    <div class="category-icon">&#127970;</div>
                    <h2 class="category-title">Produsen Data Geospasial</h2>
                    <span class="category-count" id="count-produsen">0</span>
                </div>

                <div class="table-wrapper">
                    <table class="sheet-table" id="table-produsen">
                        <thead>
                            <tr>
                                <th style="width:56px;">No</th>
                                <th>Nama Instansi</th>
                                <th>Petugas Produsen</th>
                                <th>File Presentasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="4" class="loading">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="back-home-container">
            <a href="{{ route('home') }}" class="btn-back-home">
                ← Kembali ke Home
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const SHEET_BPKH_ID = "{{ env('result_presentasi_bpkh') }}";
    const SHEET_PRODUSEN_ID = "{{ env('result_presentasi_produsen') }}";

    // Fetch Google Sheets via Visualization API (public sheets)
    async function fetchSheet(spreadsheetId, sheetName = '') {
        if (!spreadsheetId) return { headers: [], rows: [] };
        const base = `https://docs.google.com/spreadsheets/d/${spreadsheetId}/gviz/tq?tqx=out:json`;
        const url = sheetName ? `${base}&sheet=${encodeURIComponent(sheetName)}` : base;
        const res = await fetch(url, { cache: 'no-cache' });
        const text = await res.text();
        const jsonStr = text.substring(text.indexOf('{'), text.lastIndexOf('}') + 1);
        const data = JSON.parse(jsonStr);
        const headers = (data.table.cols || []).map(c => (c.label || '').trim());
        const rows = (data.table.rows || []).map(r => (r.c || []).map(c => (c && (c.f || c.v)) ? String(c.f || c.v) : ''));
        return { headers, rows };
    }

    function renderTable(tbody, countBadge, headers, rows, mapping) {
        // mapping: { nameKey: 'Nama BPKH', officerKey: 'Petugas BPKH', fileKey: 'File Presentasi' }
        const nameIdx = headers.findIndex(h => h.toLowerCase() === mapping.nameKey.toLowerCase());
        const officerIdx = headers.findIndex(h => h.toLowerCase() === mapping.officerKey.toLowerCase());
        const fileIdx = headers.findIndex(h => h.toLowerCase() === mapping.fileKey.toLowerCase());

        tbody.innerHTML = '';
        let count = 0;
        rows.forEach((r, i) => {
            // Skip empty rows
            const name = nameIdx >= 0 ? (r[nameIdx] || '').trim() : '';
            const officer = officerIdx >= 0 ? (r[officerIdx] || '').trim() : '';
            const file = fileIdx >= 0 ? (r[fileIdx] || '').trim() : '';
            if (!name && !officer && !file) return;
            count++;

            const tr = document.createElement('tr');
            const tdNo = document.createElement('td');
            tdNo.className = 'col-no';
            tdNo.textContent = String(count);

            const tdName = document.createElement('td');
            tdName.textContent = name;

            const tdOfficer = document.createElement('td');
            tdOfficer.textContent = officer;

            const tdFile = document.createElement('td');
            if (file && /^https?:\/\//i.test(file)) {
                const a = document.createElement('a');
                a.href = file; a.target = '_blank'; a.rel = 'noopener';
                a.className = 'sheet-link';
                a.textContent = 'Unduh File';
                tdFile.appendChild(a);
            } else {
                tdFile.textContent = file || '-';
            }

            tr.appendChild(tdNo);
            tr.appendChild(tdName);
            tr.appendChild(tdOfficer);
            tr.appendChild(tdFile);
            tbody.appendChild(tr);
        });

        if (!count) {
            tbody.innerHTML = `<tr><td colspan="4" class="is-empty">Belum ada data</td></tr>`;
        }
        if (countBadge) countBadge.textContent = String(count);
    }

    // Load BPKH
    (async () => {
        try {
            const { headers, rows } = await fetchSheet(SHEET_BPKH_ID);
            renderTable(
                document.querySelector('#table-bpkh tbody'),
                document.querySelector('#count-bpkh'),
                headers,
                rows,
                { nameKey: 'Nama BPKH', officerKey: 'Petugas BPKH', fileKey: 'File Presentasi' }
            );
        } catch (e) {
            document.querySelector('#table-bpkh tbody').innerHTML = `<tr><td colspan="4" class="is-empty">Gagal memuat data</td></tr>`;
        }
    })();

    // Load Produsen
    (async () => {
        try {
            const { headers, rows } = await fetchSheet(SHEET_PRODUSEN_ID);
            renderTable(
                document.querySelector('#table-produsen tbody'),
                document.querySelector('#count-produsen'),
                headers,
                rows,
                { nameKey: 'Nama Instansi', officerKey: 'Petugas Produsen', fileKey: 'File Presentasi' }
            );
        } catch (e) {
            document.querySelector('#table-produsen tbody').innerHTML = `<tr><td colspan="4" class="is-empty">Gagal memuat data</td></tr>`;
        }
    })();
});
</script>
@endpush



@endsection
