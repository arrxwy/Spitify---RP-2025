window.addEventListener('DOMContentLoaded', function () {
    const img = document.getElementById('artistPfp');
    if (!img) return;

    img.crossOrigin = "anonymous"; // Only needed if images are from another domain

    img.addEventListener('load', function () {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = img.naturalWidth;
        canvas.height = img.naturalHeight;
        ctx.drawImage(img, 0, 0);

        try {
            const data = ctx.getImageData(0, 0, canvas.width, canvas.height).data;
            let r = 0, g = 0, b = 0, count = 0;
            for (let i = 0; i < data.length; i += 40) { // Sample every 10th pixel
                r += data[i];
                g += data[i + 1];
                b += data[i + 2];
                count++;
            }
            r = Math.round(r / count);
            g = Math.round(g / count);
            b = Math.round(b / count);

            // Set the background gradient
            document.body.style.background = `linear-gradient(90deg, #000 0%, rgb(${r},${g},${b}) 100%)`;

            // Use getElementById for correct selection
            const homeBtn = document.getElementById('home');
            const profileBtn = document.getElementById('uzivatel');
            if (homeBtn) {
                homeBtn.style.background = `linear-gradient(0deg, #000 0%, rgb(${r},${g},${b}) 100%)`;
            }
            if (profileBtn) {
                profileBtn.style.background = `linear-gradient(0deg, #000 0%, rgb(${r},${g},${b}) 100%)`;
            }

        } catch (e) {
            // If image is cross-origin and CORS is not set, this will fail
            console.warn('Could not extract color from image:', e);
        }
    });

    // If the image is already loaded (from cache), trigger the load event manually
    if (img.complete) {
        img.dispatchEvent(new Event('load'));
    }
});