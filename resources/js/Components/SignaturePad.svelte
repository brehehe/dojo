<script>
    import { onMount, untrack } from 'svelte';

    // Svelte 5 bindable props
    let { value = $bindable(null), name = '' } = $props();

    let canvas = $state(null);
    let ctx = null;
    let drawing = false;
    let hasDrawing = $state(false);

    // Coordinate mapping helper to translate viewport touch coordinates to fixed backing store resolution
    function getEventPos(e) {
        if (!canvas) return { x: 0, y: 0 };
        const rect = canvas.getBoundingClientRect();
        
        let clientX = 0;
        let clientY = 0;
        
        // Touch events
        if (e.touches && e.touches.length > 0) {
            clientX = e.touches[0].clientX;
            clientY = e.touches[0].clientY;
        } else if (e.changedTouches && e.changedTouches.length > 0) {
            clientX = e.changedTouches[0].clientX;
            clientY = e.changedTouches[0].clientY;
        } else {
            // MouseEvent or PointerEvent
            clientX = e.clientX;
            clientY = e.clientY;
        }
        
        const canvasX = clientX - rect.left;
        const canvasY = clientY - rect.top;
        
        // Calculate scaling ratio between backing store (800x300) and CSS display size
        const scaleX = rect.width > 0 ? (canvas.width / rect.width) : 1;
        const scaleY = rect.height > 0 ? (canvas.height / rect.height) : 1;
        
        return {
            x: canvasX * scaleX,
            y: canvasY * scaleY
        };
    }

    function handleStart(e) {
        if (e.cancelable) {
            e.preventDefault();
        }
        drawing = true;
        
        const pos = getEventPos(e);
        if (ctx) {
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
        }

        // Capture pointer if PointerEvent is supported (to handle dragging off the canvas)
        if (e.pointerId !== undefined && canvas && canvas.setPointerCapture) {
            try {
                canvas.setPointerCapture(e.pointerId);
            } catch (err) {
                // ignore
            }
        }
    }

    function handleMove(e) {
        if (!drawing) return;
        if (e.cancelable) {
            e.preventDefault();
        }
        
        const pos = getEventPos(e);
        if (ctx) {
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            hasDrawing = true;
        }
    }

    function handleEnd(e) {
        if (!drawing) return;
        drawing = false;

        if (e.pointerId !== undefined && canvas && canvas.releasePointerCapture) {
            try {
                canvas.releasePointerCapture(e.pointerId);
            } catch (err) {
                // ignore
            }
        }
        
        save();
    }

    function clear() {
        if (ctx && canvas) {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
        hasDrawing = false;
        value = null;
        lastRenderedValue = null;
    }

    let lastRenderedValue = $state(null);

    function save() {
        if (!hasDrawing || !canvas) {
            value = null;
            lastRenderedValue = null;
            return;
        }
        // Save as base64 PNG image
        value = canvas.toDataURL('image/png');
        lastRenderedValue = value;
    }

    // Single Svelte 5 reactive effect to sync values passed from the parent.
    // We only track "value" reactively, everything else is untracked to prevent self-clearing loops.
    $effect(() => {
        const currentValue = value;
        
        untrack(() => {
            if (!canvas) return;
            
            if (!ctx) {
                // Set backing store dimensions once
                canvas.width = 800;
                canvas.height = 300;

                ctx = canvas.getContext('2d');
                ctx.strokeStyle = '#1e293b'; // Slate 800 color for smooth pen
                ctx.lineWidth = 4;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';
            }

            if (currentValue !== lastRenderedValue) {
                if (currentValue === null || currentValue === '') {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    hasDrawing = false;
                    lastRenderedValue = null;
                } else {
                    hasDrawing = true;
                    lastRenderedValue = currentValue;
                    const img = new Image();
                    img.onload = () => {
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    };
                    img.src = currentValue;
                }
            }
        });
    });

    onMount(() => {
        if (canvas) {
            // Check for PointerEvent support
            const hasPointerEvents = window.PointerEvent !== undefined;

            if (hasPointerEvents) {
                // Modern unified pointer events with passive: false to prevent scrolling
                canvas.addEventListener('pointerdown', handleStart, { passive: false });
                canvas.addEventListener('pointermove', handleMove, { passive: false });
                canvas.addEventListener('pointerup', handleEnd, { passive: false });
                canvas.addEventListener('pointercancel', handleEnd, { passive: false });
            } else {
                // Fallback for older legacy browsers
                canvas.addEventListener('touchstart', handleStart, { passive: false });
                canvas.addEventListener('touchmove', handleMove, { passive: false });
                canvas.addEventListener('touchend', handleEnd, { passive: false });
                canvas.addEventListener('touchcancel', handleEnd, { passive: false });

                canvas.addEventListener('mousedown', handleStart);
                canvas.addEventListener('mousemove', handleMove);
                canvas.addEventListener('mouseup', handleEnd);
                canvas.addEventListener('mouseleave', handleEnd);
            }
        }
        
        return () => {
            if (canvas) {
                canvas.removeEventListener('pointerdown', handleStart);
                canvas.removeEventListener('pointermove', handleMove);
                canvas.removeEventListener('pointerup', handleEnd);
                canvas.removeEventListener('pointercancel', handleEnd);

                canvas.removeEventListener('touchstart', handleStart);
                canvas.removeEventListener('touchmove', handleMove);
                canvas.removeEventListener('touchend', handleEnd);
                canvas.removeEventListener('touchcancel', handleEnd);

                canvas.removeEventListener('mousedown', handleStart);
                canvas.removeEventListener('mousemove', handleMove);
                canvas.removeEventListener('mouseup', handleEnd);
                canvas.removeEventListener('mouseleave', handleEnd);
            }
        };
    });
</script>

<div class="sig-pad-wrapper">
    {#if name}
        <label class="sig-pad-label">{name}</label>
    {/if}
    <div class="sig-canvas-container">
        <canvas bind:this={canvas}></canvas>
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
        touch-action: none !important;
    }

    canvas {
        display: block;
        width: 100%;
        height: 100%;
        border-radius: 8px;
        touch-action: none !important;
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
