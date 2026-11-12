document.addEventListener("DOMContentLoaded", () => {
    const timers = document.querySelectorAll(".expiry-timer");

    setInterval(() => {
        timers.forEach(timer => {
            const expiryDate = new Date(timer.dataset.expiry).getTime();
            const now = new Date().getTime();
            const distance = expiryDate - now;

            if (distance < 0) {
                timer.innerHTML = "<span style='color: red; font-weight: bold;'>EXPIRED</span>";
            } else {
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                timer.innerHTML = `${hours}h ${minutes}m ${seconds}s left`;
            }
        });
    }, 1000);
});