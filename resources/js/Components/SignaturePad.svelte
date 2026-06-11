<script>
    import { onMount } from 'svelte';

    // Svelte 5 bindable props
    let { value = $bindable(null), name = '' } = $props();

    let canvas = $state(null);
    let ctx = null;
    let drawing = false;
    let hasDrawing = $state(false);

    function getMousePos(e) {
        const rect = canvas.getBoundingClientRect();
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        return {
            x: clientX - rect.left,
            y: clientY - rect.top
        };
    }

    function startDrawing(e) {
        e.preventDefault();
        drawing = true;
        ctx.beginPath();
        const pos = getMousePos(e);
        ctx.moveTo(pos.x, pos.y);
    }

    function draw(e) {
        if (!drawing) return;
        e.preventDefault();
        const pos = getMousePos(e);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
        hasDrawing = true;
    }

    function stopDrawing() {
        if (!drawing) return;
        drawing = false;
        save();
    }

    function clear() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hasDrawing = false;
        value = null;
    }

    function save() {
        if (!hasDrawing) {
            value = null;
            return;
        }
        // Save as base64 PNG image
        value = canvas.toDataURL('image/png');
    }

    function resizeCanvas() {
        if (!canvas) return;
        const rect = canvas.getBoundingClientRect();
        
        // Keep existing drawing data if any
        const currentData = value;
        
        canvas.width = rect.width || 300;
        canvas.height = rect.height || 120;
        
        ctx = canvas.getContext('2d');
        ctx.strokeStyle = '#2c3e50';
        ctx.lineWidth = 3;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        
        if (currentData) {
            hasDrawing = true;
            const img = new Image();
            img.onload = () => {
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            };
            img.src = currentData;
        }
    }

    onMount(() => {
        // Run resize canvas on mount
        resizeCanvas();
        
        window.addEventListener('resize', resizeCanvas);
        return () => {
            window.removeEventListener('resize', resizeCanvas);
        };
    });
</script>

<div class="sig-pad-wrapper">
    {#if name}
        <label class="sig-pad-label">{name}</label>
    {/if}
    <div class="sig-canvas-container">
        <canvas
            bind:this={canvas}
            onmousedown={startDrawing}
            onmousemove={draw}
            onmouseup={stopDrawing}
            onmouseleave={stopDrawing}
            ontouchstart={startDrawing}
            ontouchmove={draw}
            ontouchend={stopDrawing}
        ></canvas>
        {#if !hasDrawing}
            <div class="sig-placeholder">Tanda Tangan Di Sini</div>
        {/if}
    </div>
    <div class="sig-pad-actions">
        <button type="button" class="sig-btn-clear" onclick={clear}>
            <i class="fas fa-eraser"></i> Bersihkan
        </button>
    </div>
</div>

<style>
    .sig-pad-wrapper {
        display: flex;
        flex-direction: column;
        gap: 6px;
        background: #fff;
        border-radius: 12px;
        padding: 8px;
        border: 1px solid #e0dcd3;
    }

    .sig-pad-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        color: #7f8c8d;
        letter-spacing: 0.05em;
    }

    .sig-canvas-container {
        position: relative;
        background: #faf8f5;
        border: 1px dashed #c0b9a6;
        border-radius: 8px;
        height: 120px;
        cursor: crosshair;
        touch-action: none;
    }

    canvas {
        display: block;
        width: 100%;
        height: 100%;
        border-radius: 8px;
    }

    .sig-placeholder {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 12px;
        color: #b0aa99;
        font-style: italic;
        pointer-events: none;
        font-weight: 500;
    }

    .sig-pad-actions {
        display: flex;
        justify-content: flex-end;
    }

    .sig-btn-clear {
        background: none;
        border: none;
        color: #c0392b;
        font-size: 10px;
        font-weight: 700;
        cursor: pointer;
        padding: 4px 8px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transition: opacity 0.15s;
    }

    .sig-btn-clear:hover {
        opacity: 0.8;
    }
</style>
