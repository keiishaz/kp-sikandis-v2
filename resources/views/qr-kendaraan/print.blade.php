<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label QR Kendaraan - SIKANDIS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        /* =================== CONTROL BAR =================== */
        #control-bar {
            position: fixed;
            top: 0; left: 0; right: 0;
            background: #1e3a8a;
            color: #fff;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            gap: 24px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        #control-bar h1 { font-size: 15px; font-weight: 700; flex-shrink: 0; }

        .ctrl-group {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .ctrl-group label { opacity: 0.8; white-space: nowrap; }

        .ctrl-group select, .ctrl-group input[type="number"] {
            padding: 5px 10px;
            border-radius: 6px;
            border: 1px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.15);
            color: #fff;
            font-size: 13px;
            outline: none;
        }

        .ctrl-group select option { color: #1e293b; }

        .btn-print {
            background: #22c55e;
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-left: auto;
        }

        .btn-close {
            background: rgba(255,255,255,0.15);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
        }

        /* =================== PRINT AREA =================== */
        #print-area {
            margin-top: 70px;
            padding: 24px;
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            justify-content: flex-start;
        }

        /* =================== LABEL CARD & WRAPPER =================== */
        .print-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .label-card {
            background: #ffffff;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            display: inline-flex;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 12px;
            position: relative;
        }

        .qr-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-wrapper img.logo-center {
            position: absolute;
            width: 22%;
            height: 22%;
            object-fit: contain;
        }

        .vehicle-code {
            font-size: 11px;
            font-weight: 700;
            color: #1e3a8a;
            letter-spacing: 2px;
            text-align: center;
            text-transform: uppercase;
            background: #eff6ff;
            padding: 4px 10px;
            border-radius: 20px;
            border: 1px solid #bfdbfe;
            margin-top: 4px;
        }

        .vehicle-name {
            font-size: 12px;
            font-weight: 600;
            color: #1e293b;
            text-align: center;
            line-height: 1.3;
        }

        .scan-text {
            font-size: 10px;
            color: #64748b;
            text-align: center;
        }

        /* =================== PRINT STYLES =================== */
        @media print {
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            #control-bar, #print-area { margin: 0; }
            #control-bar { display: none; }
            body { background: white; }

            #print-area {
                margin: 0;
                padding: 8mm;
                display: flex;
                flex-wrap: wrap;
                align-items: flex-start;
            }

            .print-item {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

<!-- Control Bar -->
<div id="control-bar">
    <h1>Preview Cetak Label QR Kendaraan</h1>
    <div class="ctrl-group">
        <label>QR Size:</label>
        <select id="qr-size" onchange="regenerateAll()">
            <option value="150">S (150px)</option>
            <option value="200" selected>M (200px)</option>
            <option value="260">L (260px)</option>
        </select>
    </div>
    <div class="ctrl-group">
        <label>Jarak:</label>
        <select id="label-gap" onchange="applyGap()">
            <option value="4">Rapat (4px)</option>
            <option value="16" selected>Sedang (16px)</option>
            <option value="32">Renggang (32px)</option>
        </select>
    </div>
    <div class="ctrl-group">
        <label>Ukuran Kertas:</label>
        <select id="paper-size" onchange="applyPaper()">
            <option value="A4" selected>A4 (210×297mm)</option>
            <option value="A3">A3 (297×420mm)</option>
            <option value="100x100">Label 100×100mm</option>
        </select>
    </div>
    <button class="btn-print" onclick="window.print()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
        Cetak Sekarang
    </button>
    <button class="btn-close" onclick="window.close()">✕ Tutup</button>
</div>

<!-- Print Area -->
<div id="print-area"></div>

<script>
const rawItems = {!! $itemsJson !!};
const logoUrl  = '{{ asset("assets/images/logobkl.png") }}';
const baseUrl  = '{{ rtrim(url("/"), "/") }}';

function regenerateAll() {
    const area = document.getElementById('print-area');
    area.innerHTML = '';
    const qrSize  = parseInt(document.getElementById('qr-size').value);

    rawItems.forEach(item => {
        // Container Utama (Item)
        const printItem = document.createElement('div');
        printItem.className = 'print-item';

        // Kartu QR (Putih)
        const card = document.createElement('div');
        card.className = 'label-card';

        // QR wrapper
        const wrapper = document.createElement('div');
        wrapper.className = 'qr-wrapper';

        const qrDiv = document.createElement('div');
        qrDiv.style.width = qrSize + 'px';
        qrDiv.style.height = qrSize + 'px';

        // Logo di tengah QR
        const logo = document.createElement('img');
        logo.src = logoUrl;
        logo.className = 'logo-center';
        logo.style.width  = Math.round(qrSize * 0.22) + 'px';
        logo.style.height = Math.round(qrSize * 0.22) + 'px';

        wrapper.appendChild(qrDiv);
        wrapper.appendChild(logo);
        card.appendChild(wrapper);

        // Teks Informasi (Di Luar Kartu Putih)
        const codeEl = document.createElement('div');
        codeEl.className = 'vehicle-code';
        codeEl.textContent = item.token;

        const nameEl = document.createElement('div');
        nameEl.className = 'vehicle-name';
        nameEl.textContent = item.nama;

        const platEl = document.createElement('div');
        platEl.className = 'scan-text';
        platEl.textContent = item.plat;

        printItem.appendChild(card);
        printItem.appendChild(codeEl);
        printItem.appendChild(nameEl);
        printItem.appendChild(platEl);

        // Tempel ke area cetak terlebih dahulu
        area.appendChild(printItem);

        new QRCode(qrDiv, {
            text: baseUrl + '/scan/' + item.token,
            width: qrSize,
            height: qrSize,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.M
        });
    });
}



function applyPaper() {
    const paper = document.getElementById('paper-size').value;
    const styleId = 'paper-style';
    let el = document.getElementById(styleId) || document.createElement('style');
    el.id = styleId;

    const sizes = {
        'A4': '210mm 297mm',
        'A3': '297mm 420mm',
        '100x100': '100mm 100mm',
    };

    el.textContent = `@page { size: ${sizes[paper] || '210mm 297mm'}; margin: 8mm; }`;
    document.head.appendChild(el);
}

function applyGap() {
    const gap = document.getElementById('label-gap').value;
    document.getElementById('print-area').style.gap = gap + 'px';
}

// Init
applyPaper();
applyGap();
regenerateAll();
</script>
</body>
</html>
