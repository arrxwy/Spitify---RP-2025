const favBtn = document.getElementById("favourite");
const starIcon = favBtn.querySelector(".star");
const albumId = favBtn.getAttribute("data-album-id");

favBtn.addEventListener("click", function (e) {
    e.stopPropagation();
    e.preventDefault();

    // Add animation class
    favBtn.classList.add("favourite-animate");

    // Remove animation class after animation ends (300ms)
    setTimeout(() => {
        favBtn.classList.remove("favourite-animate");
    }, 300);

    fetch("toggle_favourite.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "album_id=" + encodeURIComponent(albumId)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            starIcon.style.color = data.isFavourite ? "yellow" : "white";
            favBtn.setAttribute("data-fav", data.isFavourite ? "1" : "0");
        }
    })
    .catch(() => {
        // Optionally handle error
    });
});

window.addEventListener('DOMContentLoaded', function () {
    const img = document.getElementById('albumPfp');
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
            const favouriteBtn = document.getElementById('favourite');

            if (homeBtn) {
                homeBtn.style.background = `linear-gradient(0deg, #000 0%, rgb(${r},${g},${b}) 100%)`;
            }
            if (profileBtn) {
                profileBtn.style.background = `linear-gradient(0deg, #000 0%, rgb(${r},${g},${b}) 100%)`;
            }

            if (favouriteBtn) {
                favouriteBtn.style.background = `linear-gradient(0deg, #000 0%, rgb(${r},${g},${b}) 100%)`;
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