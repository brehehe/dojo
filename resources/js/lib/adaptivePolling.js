export function createAdaptivePolling({
    fetchNow,
    normalInterval = 5000,
    healthyInterval = 15000,
    staleAfter = 15000,
    immediate = true,
} = {}) {
    let timeoutId = null;
    let destroyed = false;
    let inFlight = false;
    let queued = false;
    let lastRealtimeAt = 0;

    const clearTimer = () => {
        if (timeoutId) {
            clearTimeout(timeoutId);
            timeoutId = null;
        }
    };

    const realtimeHealthy = () => Boolean(window.Echo) && Date.now() - lastRealtimeAt <= staleAfter;
    const nextInterval = () => (realtimeHealthy() ? healthyInterval : normalInterval);

    const schedule = (delay = nextInterval()) => {
        if (destroyed) return;
        clearTimer();
        timeoutId = setTimeout(run, Math.max(0, delay));
    };

    const run = async () => {
        if (destroyed) return;
        clearTimer();

        if (inFlight) {
            queued = true;
            return;
        }

        inFlight = true;
        try {
            await fetchNow?.();
        } finally {
            inFlight = false;
            if (destroyed) {
                queued = false;
                return;
            }

            if (queued) {
                queued = false;
                schedule(0);
                return;
            }

            schedule();
        }
    };

    const handleVisibility = () => {
        if (!document.hidden) {
            schedule(0);
        }
    };

    return {
        start() {
            destroyed = false;
            document.addEventListener('visibilitychange', handleVisibility);
            schedule(immediate ? 0 : nextInterval());
        },
        stop() {
            destroyed = true;
            queued = false;
            clearTimer();
            document.removeEventListener('visibilitychange', handleVisibility);
        },
        refresh(delay = 0) {
            schedule(delay);
        },
        markRealtimeHealthy() {
            lastRealtimeAt = Date.now();
        },
    };
}
