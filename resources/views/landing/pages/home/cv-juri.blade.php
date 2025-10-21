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

    .cv-juri-wrapper {
        padding: 40px 20px 60px;
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
        overflow-y: visible;
        min-height: auto;
        height: auto;
    }

    .cv-juri-header {
        text-align: center;
        margin-bottom: 30px;
        animation: fadeInDown 0.8s ease-out;
    }

    .cv-juri-title {
        color: var(--white);
        font-size: 38px;
        font-weight: 700;
        margin-bottom: 12px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        line-height: 1.2;
    }

    .cv-juri-subtitle {
        color: var(--white);
        font-size: 18px;
        opacity: 0.9;
    }

    .cv-juri-icon {
        font-size: 70px;
        line-height: 1;
        margin: 0 auto 20px;
        animation: bounce 2s infinite;
        display: block;
        text-align: center;
    }

    .pdf-viewer-container {
        max-width: 1400px;
        margin: 0 auto 40px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        animation: fadeInUp 0.8s ease-out;
    }

    .pdf-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .pdf-controls-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .pdf-controls-right {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .pdf-btn {
        background: var(--sigap-color);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pdf-btn:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    .pdf-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .pdf-page-info {
        color: var(--white);
        font-weight: 600;
        background: rgba(255, 255, 255, 0.1);
        padding: 8px 16px;
        border-radius: 8px;
    }

    .pdf-zoom-controls {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pdf-zoom-btn {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .pdf-zoom-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .pdf-canvas-container {
        background: white;
        border-radius: 12px;
        overflow: auto;
        min-height: 85vh;
        max-height: 90vh;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 20px;
    }

    #pdf-canvas {
        max-width: 100%;
        height: auto;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    }

    .loading-spinner {
        text-align: center;
        padding: 40px;
        color: var(--white);
        font-size: 18px;
    }

    .back-home-container {
        text-align: center;
        margin-top: 40px;
        padding-bottom: 20px;
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
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    }

    .btn-back-home:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.3);
        opacity: 0.95;
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

    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    @media (max-width: 768px) {
        .cv-juri-wrapper {
            padding: 40px 16px 60px;
        }

        .cv-juri-title {
            font-size: 28px;
        }

        .cv-juri-subtitle {
            font-size: 16px;
        }

        .cv-juri-icon {
            font-size: 56px;
        }

        .pdf-viewer-container {
            padding: 16px;
        }

        .pdf-controls {
            flex-direction: column;
            align-items: stretch;
        }

        .pdf-controls-left,
        .pdf-controls-right {
            justify-content: center;
        }

        .pdf-canvas-container {
            max-height: 60vh;
            padding: 12px;
        }
    }
</style>
@endpush

<div class="wrapper">
    <div class="cv-juri-wrapper">
        <!-- Header -->
        <div class="cv-juri-header">
            <div class="cv-juri-icon">&#128196;</div>
            <h1 class="cv-juri-title">CV Juri SIGAP Award 2025</h1>
            <p class="cv-juri-subtitle">Profil Tim Juri Penilaian</p>
        </div>

        <!-- PDF Viewer -->
        <div class="pdf-viewer-container">
            <div class="pdf-controls">
                <div class="pdf-controls-left">
                    <button id="prev-page" class="pdf-btn">
                        <span>&#9664;</span> Previous
                    </button>
                    <span class="pdf-page-info">
                        Page <span id="page-num">1</span> / <span id="page-count">0</span>
                    </span>
                    <button id="next-page" class="pdf-btn">
                        Next <span>&#9654;</span>
                    </button>
                </div>
                <div class="pdf-controls-right">
                    <div class="pdf-zoom-controls">
                        <button id="zoom-out" class="pdf-zoom-btn">-</button>
                        <span class="pdf-page-info"><span id="zoom-level">100</span>%</span>
                        <button id="zoom-in" class="pdf-zoom-btn">+</button>
                    </div>
                    <a href="{{ asset($pdfPath) }}" download="CV Juri SIGAP Award 2025.pdf" class="pdf-btn" style="text-decoration: none;">
                        <span>&#8595;</span> Download PDF
                    </a>
                </div>
            </div>

            <div id="loading" class="loading-spinner">
                <div>Loading PDF...</div>
            </div>

            <div id="pdf-canvas-container" class="pdf-canvas-container" style="display: none;">
                <canvas id="pdf-canvas"></canvas>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    // PDF.js configuration
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    const pdfPath = '{{ asset($pdfPath) }}';
    let pdfDoc = null;
    let pageNum = 1;
    let pageRendering = false;
    let pageNumPending = null;
    let scale = 1.5;

    const canvas = document.getElementById('pdf-canvas');
    const ctx = canvas.getContext('2d');
    const loading = document.getElementById('loading');
    const canvasContainer = document.getElementById('pdf-canvas-container');

    // Render the page
    function renderPage(num) {
        pageRendering = true;
        
        pdfDoc.getPage(num).then(function(page) {
            const viewport = page.getViewport({ scale: scale });
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };

            const renderTask = page.render(renderContext);

            renderTask.promise.then(function() {
                pageRendering = false;
                if (pageNumPending !== null) {
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }
            });
        });

        // Update page counters
        document.getElementById('page-num').textContent = num;
    }

    // Queue page rendering
    function queueRenderPage(num) {
        if (pageRendering) {
            pageNumPending = num;
        } else {
            renderPage(num);
        }
    }

    // Previous page
    function onPrevPage() {
        if (pageNum <= 1) {
            return;
        }
        pageNum--;
        queueRenderPage(pageNum);
        updateButtons();
    }

    // Next page
    function onNextPage() {
        if (pageNum >= pdfDoc.numPages) {
            return;
        }
        pageNum++;
        queueRenderPage(pageNum);
        updateButtons();
    }

    // Zoom in
    function onZoomIn() {
        scale += 0.25;
        if (scale > 3) scale = 3;
        queueRenderPage(pageNum);
        updateZoomLevel();
    }

    // Zoom out
    function onZoomOut() {
        scale -= 0.25;
        if (scale < 0.5) scale = 0.5;
        queueRenderPage(pageNum);
        updateZoomLevel();
    }

    // Update button states
    function updateButtons() {
        document.getElementById('prev-page').disabled = pageNum <= 1;
        document.getElementById('next-page').disabled = pageNum >= pdfDoc.numPages;
    }

    // Update zoom level display
    function updateZoomLevel() {
        document.getElementById('zoom-level').textContent = Math.round(scale * 100);
    }

    // Load PDF
    pdfjsLib.getDocument(pdfPath).promise.then(function(pdf) {
        pdfDoc = pdf;
        document.getElementById('page-count').textContent = pdf.numPages;
        
        // Hide loading, show canvas
        loading.style.display = 'none';
        canvasContainer.style.display = 'flex';
        
        // Initial render
        renderPage(pageNum);
        updateButtons();
        updateZoomLevel();
    }).catch(function(error) {
        loading.innerHTML = '<div style="color: #ff6b6b;">Error loading PDF: ' + error.message + '</div>';
    });

    // Event listeners
    document.getElementById('prev-page').addEventListener('click', onPrevPage);
    document.getElementById('next-page').addEventListener('click', onNextPage);
    document.getElementById('zoom-in').addEventListener('click', onZoomIn);
    document.getElementById('zoom-out').addEventListener('click', onZoomOut);
</script>
@endpush

@endsection
